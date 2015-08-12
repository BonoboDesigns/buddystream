<?php
/**
 * Import starter
 */

function BuddystreamVimeoImportStart()
{
    $importer = new BuddyStreamVimeoImport();
    return $importer->doImport();
}

/**
 * Vimeo Import Class
 */

class BuddyStreamVimeoImport
{

    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        $user_metas = $wpdb->get_results(
                "SELECT user_id
                        FROM $wpdb->usermeta WHERE
                        meta_key='buddystream_vimeo_username'
                        ORDER BY meta_value;"
        );

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {

                //check for daylimit
                $limitReached = $buddyStreamFilters->limitReached('vimeo', $user_meta->user_id);

                if (!$limitReached && get_user_meta($user_meta->user_id, 'buddystream_vimeo_username', 1)) {

                    $url = 'http://vimeo.com/api/v2/' . get_user_meta($user_meta->user_id, 'buddystream_vimeo_username', 1) . '/videos.xml';
                    $buddystreamCurl = new BuddyStreamCurl();
                    $curlContent = $buddystreamCurl->getContentFromUrl($url);
                    $sxml = simplexml_load_string($curlContent);

                    if ($sxml) {
                        foreach ($sxml as $item) {

                            $max = $buddyStreamFilters->limitReached('vimeo', $user_meta->user_id);

                            if (!$max) {

                                $description = "";
                                $description = strip_tags($item->description);
                                if (strlen($description) > 400) {
                                    $description = substr($description, 0, 400) . "... <a href='" . $item->url . "'>read more</a>";
                                }

                                $content = '<a href="http://player.vimeo.com/video/' . $item->id . '" class="bs_lightbox" title="' . $item->title . '"><img src="' . $item->thumbnail_small . '"></a><b>' . $item->title . '</b> ' . $description;

                                //pre convert date
                                $ts = strtotime($item->upload_date);

                                $returnCreate = buddystreamCreateActivity(array(
                                        'user_id' => $user_meta->user_id,
                                        'extension' => 'vimeo',
                                        'type' => 'Vimeo video',
                                        'content' => $content,
                                        'item_id' => 'vimeo_' . $item->id,
                                        'raw_date' => date("Y-m-d H:i:s", $ts),
                                        'actionlink' => 'http://www.vimeo.com/' . get_user_meta($user_meta->user_id, 'buddystream_vimeo_username', 1)
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
        //add record to the log
        $buddyStreamLog->log("Vimeo imported " . $itemCounter . " video's for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;
    }

}