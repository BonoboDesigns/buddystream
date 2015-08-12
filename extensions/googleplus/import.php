<?php
/**
 * Import starter
 */

function BuddystreamGoogleplusImportStart()
{
    $importer = new BuddyStreamGoogleplusImport();
    return $importer->doImport();
}

/**
 * Google+ Import Class
 */

class BuddyStreamGoogleplusImport
{

    //do the import
    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        if (get_site_option("buddystream_googleplus_consumer_key")) {
            if (get_site_option('buddystream_googleplus_user_settings_syncbp') == "on") {

                $user_metas = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_googleplus_token'");

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //always start with import = true
                        $import = true;

                        //check for daylimit
                        $import = $buddyStreamFilters->limitReached('googleplus', $user_meta->user_id);

                        if (false === $import && get_user_meta($user_meta->user_id, 'buddystream_googleplus_synctoac', 1) == "1") {

                            //Handle the OAuth requests
                            $buddystreamOAuth = new BuddyStreamOAuth();
                            $buddystreamOAuth->setCallbackUrl($bp->root_domain);
                            $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_googleplus_consumer_key"));
                            $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_googleplus_consumer_secret"));
                            $buddystreamOAuth->setAccessToken(get_user_meta($user_meta->user_id, 'buddystream_googleplus_token', 1));
                            $buddystreamOAuth->setAccessTokenSecret(get_user_meta($user_meta->user_id, 'buddystream_googleplus_tokensecret', 1));
                            $items = $buddystreamOAuth->oAuthRequest('https://www.googleapis.com/plus/v1/people/me/activities/public');
                            $items = json_decode($items);

                            if (isset($items->items)) {
                                //go through items
                                foreach ($items->items as $item) {

                                    //check for daylimit
                                    $limitReached = $buddyStreamFilters->limitReached('googleplus', $user_meta->user_id);
                                    if (!$limitReached) {

                                        //pre-defined
                                        $content = "";
                                        $image = "";

                                        //create te correct content for the activity stream
                                        //hey we have some images
                                        if (isset($item->object->attachments)) {
                                            foreach ($item->object->attachments as $media) {

                                                $media->displayName = "";
                                                $media->url = "";

                                                if ($media->objectType == "article") {
                                                    $content .= $media->displayName . "<br>";
                                                    $content .= $media->content . "<br>";
                                                    $content .= '<a href="' . $media->url . '">' . __('visit link', 'buddystream_googleplus') . '</a>';

                                                } elseif ($media->objectType == "video") {

                                                    //transform the video url.
                                                    $videoUrl = $media->url;
                                                    $videoUrl = str_replace("http://www.youtube.com/v/", "", $videoUrl);
                                                    $videoUrlAray = explode("&", $videoUrl);

                                                    $videoId = $videoUrlAray[0];
                                                    $videoUrl = 'http://www.youtube.com/?v=' . $videoId;

                                                    $content .= $media->displayName . "<br>";
                                                    $content .= $media->content . "<br>";

                                                    $image .= '<a href="' . $videoUrl . '" id="' . $videoId . '" title="' . str_replace('"', '', $media->displayName) . '" class="bs_lightbox"><img src="' . $media->image->url . '">' . $media->displayName . '</a>';

                                                } else {
                                                    $image .= '<a href="' . $media->fullImage->url . '" title="' . str_replace('"', '', $media->displayName) . '" class="bs_lightbox"><img src="' . $media->image->url . '">' . $media->displayName . '</a>';
                                                }
                                            }
                                        } else {
                                            $content = $item->object->content;
                                        }

                                        //combine the images and content
                                        $content = $image . $content;

                                        //check if good filter passes
                                        $goodFilters = get_site_option('buddystream_googleplus_filter');
                                        $goodFilter = $buddyStreamFilters->searchFilter($content, $goodFilters, false, true, true);

                                        //check if bad filter passes
                                        $badFilters = get_site_option('buddystream_googleplus_filterexplicit');
                                        $badFilter = $buddyStreamFilters->searchFilter($content, $badFilters, true);

                                        //no filters set so just import everything
                                        if (!get_site_option('buddystream_googleplus_filter')) {
                                            $goodFilter = true;
                                        }

                                        if (!get_site_option('buddystream_googleplus_filterexplicit')) {
                                            $badFilter = false;
                                        }

                                        if ($goodFilter && !$badFilter) {

                                            $returnCreate = buddystreamCreateActivity(array(
                                                    'user_id' => $user_meta->user_id,
                                                    'extension' => 'googleplus',
                                                    'type' => 'google+ ' . $item->object->objectType,
                                                    'content' => $content,
                                                    'item_id' => 'googleplus_' . $item->id,
                                                    'raw_date' => gmdate('Y-m-d H:i:s', strtotime($item->published)),
                                                    'actionlink' => $item->url
                                                )
                                            );

                                            if ($returnCreate) {
                                                $itemCounter++;
                                            }
                                        }

                                        unset($videoId, $content, $videoUrl, $image);
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }


        //add record to the log
        $buddyStreamLog->log("Google+ imported " . $itemCounter . " items.");

        //return number of items imported
        return $itemCounter;

    }
}