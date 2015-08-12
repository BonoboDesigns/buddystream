<?php

/**
 *
 * Page loader functions
 *
 */

function buddystream_googleplus()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('googleplus');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamGoogleplusUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_googleplus_token';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamGoogleplusCountItems($user_id){

    global $wpdb,$bp;
    return count("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='googleplus';");
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamGoogleplusImportOn($user_id){
    return get_user_meta($user_id, 'buddystream_googleplus_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamGoogleplusResetUser($user_id){

    delete_user_meta($user_id, "buddystream_googleplus_synctoac");
    delete_user_meta($user_id, "buddystream_googleplus_mention");
    delete_user_meta($user_id, "buddystream_googleplus_lastupdate");
    delete_user_meta($user_id, "buddystream_googleplus_deletetweet");
    delete_user_meta($user_id, "buddystream_googleplus_checkboxon");
    delete_user_meta($user_id, "buddystream_googleplus_counterdate");
    delete_user_meta($user_id, "buddystream_googleplus_tokensecret");
    delete_user_meta($user_id, "buddystream_googleplus_filtermentions");
    delete_user_meta($user_id, "buddystream_googleplus_synctoac");
    delete_user_meta($user_id, "buddystream_googleplus_counterdate");
    delete_user_meta($user_id, "buddystream_googleplus_checkboxon");
    delete_user_meta($user_id, "buddystream_googleplus_daycounter");
    delete_user_meta($user_id, "buddystream_googleplus_deletetweet");
    delete_user_meta($user_id, "buddystream_googleplus_filtergood");
    delete_user_meta($user_id, "buddystream_googleplus_filterbad");
    delete_user_meta($user_id, "buddystream_googleplus_filtertoactivity");
    delete_user_meta($user_id, "buddystream_googleplus_filtertotwitter");
    delete_user_meta($user_id, "buddystream_googleplus_profilelink");
    delete_user_meta($user_id, "buddystream_googleplus_screenname");
    delete_user_meta($user_id, "buddystream_googleplus_token");
}