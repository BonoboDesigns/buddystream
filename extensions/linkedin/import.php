<?php

/**
 * Import starter
 */

function BuddystreamLinkedinImportStart()
{
    $importer = new BuddyStreamLinkedinImport();
    return $importer->doImport();
}

/**
 * Linkedin Import Class
 */

class BuddyStreamLinkedinImport
{

    //do the import
    public function doImport()
    {

        global $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        if (get_site_option("buddystream_linkedin_consumer_key")) {
            if (get_site_option('buddystream_linkedin_user_settings_syncbp') == 0) {

                $user_metas = $wpdb->get_results(
                    "SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_linkedin_token'"
                );

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {
                        //check for daylimit
                        $limitReached = $buddyStreamFilters->limitReached('linkedin', $user_meta->user_id);

                        if (!$limitReached && get_user_meta($user_meta->user_id, 'buddystream_linkedin_synctoac', 1) && ! get_user_meta($user_meta->user_id, 'buddystream_linkedin_reauth', 1)) {

                            //Handle the OAuth requests
                            $buddystreamOAuth = new BuddyStreamOAuth();

                            $buddystreamOAuth->setParameters(array(
                                'client_id' => get_site_option("buddystream_linkedin_consumer_key"),
                                'client_secret' => get_site_option("buddystream_linkedin_consumer_secret"),
                                'oauth2_access_token' => get_user_meta($user_meta->user_id, 'buddystream_linkedin_token', 1)
                            ));

                            $items = $buddystreamOAuth->executeRequest('https://api.linkedin.com/v1/people/~/network/updates?type=SHAR&scope=self&oauth2_access_token='.get_user_meta($user_meta->user_id, 'buddystream_linkedin_token', 1));
                            $items = simplexml_load_string($items);

                            if(strpos(" " . $items->message, "Invalid")){
                                update_user_meta($user_meta->user_id, "buddystream_linkedin_reauth", true);
                            }

                            if($items->status == "401"){
                                update_user_meta($user_meta->user_id, "buddystream_linkedin_reauth", true);
                            }

                            if ($items) {

                                //go through items
                                foreach ($items as $item) {

                                    //check for daylimit
                                    $limitReached = $buddyStreamFilters->limitReached('linkedin', $user_meta->user_id);

                                    //get the content
                                    if ($item->{'update-type'} == 'SHAR') {
                                        $content = $item->{'update-content'}->{'person'}->{'current-share'}->{'comment'};
                                    }

                                    //check if good filter passes
                                    $goodFilters = get_site_option('buddystream_linkedin_filter');
                                    $goodFilter = $buddyStreamFilters->searchFilter($content, $goodFilters, false, true, true);

                                    //check if bad filter passes
                                    $badFilters = get_site_option('buddystream_linkedin_filterexplicit');
                                    $badFilter = $buddyStreamFilters->searchFilter($content, $badFilters, true);

                                    //no filters set so just import everything
                                    if (!get_site_option('buddystream_linkedin_filter')) {
                                        $goodFilter = true;
                                    }

                                    if (!get_site_option('buddystream_linkedin_filterexplicit')) {
                                        $badFilter = false;
                                    }

                                    if (!$limitReached && $goodFilter && !$badFilter) {

                                        //convert timestamp
                                        $timeStamp = $item->timestamp;
                                        $timeStamp = substr($timeStamp, 0, 10);

                                        $returnCreate = buddystreamCreateActivity(array(
                                            'user_id' => $user_meta->user_id,
                                            'extension' => 'linkedin',
                                            'type' => 'status',
                                            'content' => $content,
                                            'item_id' => "linkedin_" . $item->{'update-key'},
                                            'raw_date' => gmdate('Y-m-d H:i:s', $timeStamp),
                                            'actionlink' => trim($item->{'update-content'}->{'person'}->{'site-standard-profile-request'}->{'url'} . "")
                                        ));

                                        if ($returnCreate) {
                                            $itemCounter++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //add record to the log
        $buddyStreamLog->log("LinkedIn imported " . $itemCounter . " items for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}