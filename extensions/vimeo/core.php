<?php
/**
 *
 * Page loader functions
 *
 */

function buddystream_vimeo()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('vimeo');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamVimeoUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_vimeo_username';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamVimeoCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='vimeo';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamVimeoImportOn($user_id){
    return true;
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamVimeoResetUser($user_id){

    delete_user_meta($user_id, "buddystream_vimeo_username");
}
