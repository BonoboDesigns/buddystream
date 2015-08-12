<?php
/**
 * Import starter
 */

function BuddystreamRssImportStart()
{
    $importer = new BuddyStreamRssImport();
    return $importer->doImport();
}

/**
 * Rss Import Class
 */
class BuddyStreamRssImport
{

    public function doImport()
    {


        include_once('wp-includes/feed.php');

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        $user_metas = $wpdb->get_results(
                "SELECT user_id
                        FROM $wpdb->usermeta WHERE
                        meta_key='buddystream_rss_feeds'
                        ORDER BY meta_value;"
        );

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {

                //always start with import = true
                $import = true;

                //check for daylimit
                $limitReached = $buddyStreamFilters->limitReached('rss', $user_meta->user_id);

                if (!$limitReached && $import && get_user_meta($user_meta->user_id, 'buddystream_rss_feeds', 1)) {

                    $feeds = get_user_meta($user_meta->user_id, 'buddystream_rss_feeds', 1);
                    $feeds = explode("\n", $feeds);

                    if (is_array($feeds)) {

                        foreach ($feeds as $feed) {

                            if (!empty($feed)) {

                                $feed = str_replace("http://", "", $feed);
                                $feed = "http://" . $feed;
                                $feed = trim($feed);

                                $feedReader = fetch_feed($feed);

                                if (!is_wp_error($feedReader)) {
                                    $items = $feedReader->get_items();

                                    if ($items && !is_wp_error($items)) {
                                        foreach ($items as $item) {

                                            //check for daylimit
                                            $max = $buddyStreamFilters->limitReached('rss', $user_meta->user_id);

                                            if (!$max) {

                                                //check if good filter passes
                                                $goodFilters = get_site_option('buddystream_rss_filter');
                                                $goodFilter = $buddyStreamFilters->searchFilter($item->get_description(), $goodFilters, false, true, true);

                                                //check if bad filter passes
                                                $badFilters = get_site_option('buddystream_rss_filterexplicit');
                                                $badFilter = $buddyStreamFilters->searchFilter($item->get_description(), $badFilters, true);

                                                //no filters set so just import everything
                                                if (!get_site_option('buddystream_rss_filter')) {
                                                    $goodFilter = true;
                                                }

                                                if (!get_site_option('buddystream_rss_filterexplicit')) {
                                                    $badFilter = false;
                                                }

                                                if ($goodFilter && !$badFilter) {

                                                    $description = strip_tags($item->get_description());
                                                    if (strlen($description) > 200) {
                                                        $description = "<b>" . $item->get_title() . "</b> - " . substr($description, 0, 200) . "... <br><br> <a href='" . $item->get_permalink() . "' target='_blank' rel='external'>read more</a>";
                                                    }

                                                    $returnCreate = buddystreamCreateActivity(array(
                                                            'user_id' => $user_meta->user_id,
                                                            'extension' => 'rss',
                                                            'type' => 'rss item',
                                                            'content' => $description,
                                                            'item_id' => $user_meta->user_id . "_" . md5($item->get_permalink()),
                                                            'raw_date' => gmdate('Y-m-d H:i:s', strtotime($item->get_date())),
                                                            'actionlink' => $item->get_permalink()
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
                }
            }
        }

        //add record to the log
        $buddyStreamLog->log("Rss imported " . $itemCounter . " items for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;
    }
}