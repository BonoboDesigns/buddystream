<?php
/**
 *
 * Page loader functions
 *
 */

function buddystream_rss()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('rss');
}

/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamRssUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_rss_feeds';");
}


/**
 * Count imported items for  user
 * @param $user_id
 * @return int
 */
function buddystreamRssCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='rss';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamRssImportOn($user_id){
    return true;
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamRssResetUser($user_id){
    delete_user_meta($user_id, "buddystream_rss_feeds");

}