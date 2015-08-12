<?php
/**
 * Import starter
 */

function BuddystreamFoursquareImportStart()
{
    $importer = new BuddyStreamFoursquareImport();
    return $importer->doImport();
}

/**
 * Foursquare Import Class
 */

class BuddyStreamFoursquareImport
{

    //do the import
    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        //item counter for in the logs
        $itemCounter = 0;

        if (get_site_option("buddystream_foursquare_consumer_key")) {
            if (get_site_option('buddystream_foursquare_user_settings_syncbp') == 0) {

                $user_metas = $wpdb->get_results("SELECT user_id FROM ".$wpdb->usermeta." WHERE meta_key='buddystream_foursquare_token'");

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //check for
                        $limitReached = $buddyStreamFilters->limitReached('foursquare', $user_meta->user_id);

                        if (!$limitReached && get_user_meta($user_meta->user_id, 'buddystream_foursquare_synctoac', 1)) {

                            //Handle the OAuth requests
                            $buddyStreamOAuth = new BuddyStreamOAuth();
                            $buddyStreamOAuth->setParameters(
                                array('client_id'   => get_site_option("buddystream_foursquare_consumer_key"),
                                    'client_secret' => get_site_option("buddystream_foursquare_consumer_secret"),
                                    'v'             => date('ymd'),
                                    'oauth_token'   => get_user_meta($user_meta->user_id, 'buddystream_foursquare_token', 1)));

                            $items = $buddyStreamOAuth->oAuthRequest('https://api.foursquare.com/v2/users/self/checkins');
                            $items = json_decode($items);

                            $items = $items->response->checkins->items;

                            if ($items) {

                                //go through tweets
                                foreach ($items as $item) {

                                    //check daylimit
                                    $limitReached = $buddyStreamFilters->limitReached('foursquare', $user_meta->user_id);

                                    //check if source filter passes
                                    if ( ! $limitReached) {

                                        $returnCreate = false;

                                        //icon
                                        $icon = "";
                                        $icon = $item->venue->categories[0]->icon;
                                        $icon = str_replace("https", "http", $icon);

                                        //map settings
                                        if(get_site_option("buddystream_foursquare_map_height")){
                                            $height = get_site_option("buddystream_foursquare_map_height");
                                        }else{
                                            $height = 150;
                                        }

                                        if(get_site_option("buddystream_foursquare_map_width")){
                                            $width = get_site_option("buddystream_foursquare_map_width");
                                        }else{
                                            $width = 520;
                                        }

                                        if(get_site_option("buddystream_foursquare_map_zoom")){
                                            $zoom = get_site_option("buddystream_foursquare_map_zoom");
                                        }else{
                                            $zoom = 13;
                                        }

                                        $content  = '';
                                        $content .= 'Checked in <a href="'.$item->venue->canonicalUrl.'" target="_blank">'.$item->venue->name.'</a><br>';
                                        $content .= '<a href="'.$item->venue->canonicalUrl.'" target="_blank"><img src="http://maps.googleapis.com/maps/api/staticmap?center='.$item->location->lat.','.$item->venue->location->lng.'&zoom='.$zoom.'&size='.$width.'x'.$height.'&sensor=false&markers=icon%3A'.$icon.'%7C'.$item->venue->location->lat.','.$item->venue->location->lng.'&format=png32">';

                                        $returnCreate = buddystreamCreateActivity(array(
                                                'user_id'    => $user_meta->user_id,
                                                'extension'  => 'foursquare',
                                                'type'       => 'checkin',
                                                'content'    =>  $content,
                                                'item_id'    =>  $item->id,
                                                'raw_date'   => gmdate('Y-m-d H:i:s', $item->createdAt),
                                                'actionlink' => $item->venue->canonicalUrl
                                            )
                                        );

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
        $buddyStreamLog->log("Foursquare imported " . $itemCounter . " checkins for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}