<?php
/**
 * Add sharing button
 */

function buddystreamTumblrSharing()
{
    global $bp;
    if (get_site_option("buddystream_tumblr_consumer_key") && get_site_option("buddystream_tumblr_export")) {
        if (get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', 1)) {
            echo'<span class="buddystream_share_button tumblr" onclick="tumblr_addTag()" id="' . __('Also post this to my Tumblr blog(s).', 'buddystream_tumblr') . '"></span>';
        }
    }
}

/**
 * Post update to Tumblr
 */

function buddystreamTumblrPostUpdate($content = "", $shortLink = "", $user_id = 0)
{
    //no location so remove tag
    $content = str_replace("#location", "", $content);

    global $bp;
    $buddyStreamFilters = new BuddyStreamFilters();

    //handle oauth calls
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_tumblr_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_tumblr_consumer_secret"));
    $buddystreamOAuth->setAccessToken(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', 1));
    $buddystreamOAuth->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret', 1));
    $buddystreamOAuth->setRequestType('POST');
    $buddystreamOAuth->setParameters(array('type' => 'text', 'body' => $buddyStreamFilters->filterPostContent($content, $shortLink)));

    $buddystreamTumblrBlogsOut = get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out', 1);
    $buddystreamTumblrBlogsOut = explode(",", $buddystreamTumblrBlogsOut);

    foreach ($buddystreamTumblrBlogsOut as $blog) {
        $blog = urlencode('' . $blog . '.tumblr.com');
        $result = $buddystreamOAuth->oAuthRequest('http://api.tumblr.com/v2/blog/' . $blog . '/post');
        $result = json_decode($result);

        buddyStreamAddToImportLog($bp->loggedin_user->id, $result->response->id, 'tumblr');
    }
}

/**
 *
 * Page loader functions
 *
 */
function buddystream_tumblr()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('tumblr');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamTumblrUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_tumblr_token';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamTumblrCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='tumblr';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamTumblrImportOn($user_id){
    return get_user_meta($user_id, 'buddystream_tumblr_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamTumblrResetUser($user_id){
    delete_user_meta($user_id, 'buddystream_tumblr_token');
    delete_user_meta($user_id, 'buddystream_tumblr_tokensecret');
    delete_user_meta($user_id, 'buddystream_tumblr_synctoac');
    delete_user_meta($user_id, 'buddystream_tumblr_blogs_import');
    delete_user_meta($user_id, 'buddystream_tumblr_blogs_out');
}