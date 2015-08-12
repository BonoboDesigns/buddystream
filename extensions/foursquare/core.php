<?php
/**
 * Add sharing button
 */

function buddystreamFoursquareSharing()
{
    global $bp;
    if (get_site_option("buddystream_foursquare_consumer_key")  && get_site_option("buddystream_foursquare_export")) {
        if (get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', 1)) {
            echo'<span class="buddystream_share_button foursquare" id="' . __('Also checkin on Foursquare.', 'buddystream_foursquare') . '"></span>';
        }
    }
}


/**
 * Post update to Foursquare
 */

function buddystreamFoursquarePostUpdate($content = "", $shortLink = "", $user_id = 0)
{

    global $bp;

    $lat  = ""; $long = ""; $locName = "";

    if(isset($_COOKIE["buddystream_location"])){
        $arrLocation = explode("#",$_COOKIE["buddystream_location"]);
        $lat         = $arrLocation[0];
        $long        = $arrLocation[1];
        $locName     = $arrLocation[2];
    }

    //get the venue id
    $buddyStreamOAuth = new BuddyStreamOAuth();
    $buddyStreamOAuth->setParameters(
        array('client_id'      => get_site_option("buddystream_foursquare_consumer_key"),
              'client_secret'  => get_site_option("buddystream_foursquare_consumer_secret"),
              'v'              => date('ymd'),
              'll'             => $lat.','.$long,
              'limit'          => 1,
              'oauth_token'    => get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', 1)));

    $response = $buddyStreamOAuth->oAuthRequest('https://api.foursquare.com/v2/venues/search');
    $response = json_decode($response);

    $venueId  = $response->response->groups[0]->items[0]->id;

    //checkin
    $buddyStreamOAuth = new BuddyStreamOAuth();
    $buddyStreamOAuth->setParameters(
        array('client_id'     => get_site_option("buddystream_foursquare_consumer_key"),
              'client_secret' => get_site_option("buddystream_foursquare_consumer_secret"),
              'v'             => date('ymd'),
              'll'            => $lat . ',' . $long,
              'venueId'       => $venueId,
              'oauth_token'   => get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', 1)));

    $buddyStreamOAuth->setRequestType("POST");
    $response = $buddyStreamOAuth->oAuthRequest('https://api.foursquare.com/v2/checkins/add');
    $response = json_decode($response);

    buddyStreamAddToImportLog($bp->loggedin_user->id, $response->response->checkin->id, 'foursquare');
}

/**
 *
 * Page loader functions
 *
 */

function buddystream_foursquare()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('foursquare');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamFoursquareUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_foursquare_token';");
}


/**
 * Count imported items for  user
 * @param $user_id
 * @return int
 */
function buddystreamFoursquareCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='foursquare';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamFoursquareImportOn($user_id){
    return get_user_meta($user_id, 'buddystream_foursquare_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamFoursquareResetUser($user_id){
    delete_user_meta($user_id, 'buddystream_foursquare_token');
    delete_user_meta($user_id, 'buddystream_foursquare_tokensecret');
    delete_user_meta($user_id, 'buddystream_foursquare_tokensecret_temp');
    delete_user_meta($user_id, 'buddystream_foursquare_token_temp');
    delete_user_meta($user_id, 'buddystream_foursquare_mention');
    delete_user_meta($user_id, 'buddystream_foursquare_synctoac');
}