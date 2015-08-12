<?php

/**
 * Import starter
 */

function BuddystreamTumblrImportStart()
{
    $importer = new BuddyStreamTumblrImport();
    return $importer->doImport();
}

/**
 * Tumblr Import Class
 */

class BuddyStreamTumblrImport
{

    //do the import
    public function doImport()
    {

        global $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        if (get_site_option("buddystream_tumblr_consumer_key")) {
            if (get_site_option('buddystream_tumblr_user_settings_syncbp') == 0) {

                $user_metas = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_tumblr_token'");

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //check for daylimit
                        $limitReached = $buddyStreamFilters->limitReached('tumblr', $user_meta->user_id);

                        if (!$limitReached && get_user_meta($user_meta->user_id, 'buddystream_tumblr_synctoac', 1)) {

                            //Handle the OAuth requests
                            $buddystreamOAuth = new BuddyStreamOAuth();
                            $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_tumblr_consumer_key"));
                            $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_tumblr_consumer_secret"));
                            $buddystreamOAuth->setAccessToken(get_user_meta($user_meta->user_id, 'buddystream_tumblr_token', 1));
                            $buddystreamOAuth->setAccessTokenSecret(get_user_meta($user_meta->user_id, 'buddystream_tumblr_tokensecret', 1));

                            $buddystreamTumblrBlogsImport = get_user_meta($user_meta->user_id, 'buddystream_tumblr_blogs_import', 1);
                            $buddystreamTumblrBlogsImport = explode(",", $buddystreamTumblrBlogsImport);

                            foreach ($buddystreamTumblrBlogsImport as $blog) {

                                $blog = urlencode('' . $blog . '.tumblr.com');
                                $buddystreamOAuth->setParameters(array('base-hostname' => $blog, 'api_key' => get_site_option("buddystream_tumblr_consumer_key")));

                                $items = $buddystreamOAuth->oAuthRequest('http://api.tumblr.com/v2/blog/' . $blog . '/posts');
                                $items = json_decode($items);
                                $items = $items->response->posts;

                                if ($items) {

                                    //go through items
                                    foreach ($items as $item) {

                                        //check for daylimit
                                        $limitReached = $buddyStreamFilters->limitReached('tumblr', $user_meta->user_id);

                                        //get the title
                                        $title = "";
                                        if ($item->title) {
                                            $title = $item->title;
                                            $title = strip_tags($title);
                                            $title = trim($title);
                                            $title = "<strong>" . $title . "</strong> <br/>";
                                        }

                                        //get the image
                                        $image = "";
                                        if ($item->photos) {
                                            $caption = $item->caption;
                                            $caption = strip_tags($caption);
                                            $caption = trim($caption);

                                            $image = '<a href="' . $item->photos[0]->original_size->url . '" class="bs_lightbox"><img src="' . $item->photos[0]->alt_sizes[3]->url . '" alt="' . $caption . '"></a> ';
                                        }

                                        //get the body
                                        $body = $item->body . "" . $item->text . "" . $item->caption . "";
                                        $body = strip_tags($body);
                                        $body = trim($body);

                                        //combine all to content
                                        $content = "" . $title . "<br>" . $image . "" . $body . "";

                                        //check if good filter passes
                                        $goodFilters = get_site_option('buddystream_tumblr_filter');
                                        $goodFilter = $buddyStreamFilters->searchFilter($content, $goodFilters, false, true, true);

                                        //check if bad filter passes
                                        $badFilters = get_site_option('buddystream_tumblr_filterexplicit');
                                        $badFilter = $buddyStreamFilters->searchFilter($content, $badFilters, true);

                                        //no filters set so just import everything
                                        if (!get_site_option('buddystream_tumblr_filter')) {
                                            $goodFilter = true;
                                        }

                                        if (!get_site_option('buddystream_tumblr_filterexplicit')) {
                                            $badFilter = false;
                                        }

                                        if (!$limitReached && $goodFilter && !$badFilter) {

                                            //convert timestamp
                                            $timeStamp = strtotime($item->date);

                                            $returnCreate = buddystreamCreateActivity(array(
                                                'user_id' => $user_meta->user_id,
                                                'extension' => 'tumblr',
                                                'type' => 'tumblr blogpost',
                                                'content' => $content,
                                                'item_id' => $item->id,
                                                'raw_date' => gmdate('Y-m-d H:i:s', $timeStamp),
                                                'actionlink' => trim($item->post_url . "")
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
        }

        //add record to the log
        $buddyStreamLog->log("Tumblr imported " . $itemCounter . " items for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}