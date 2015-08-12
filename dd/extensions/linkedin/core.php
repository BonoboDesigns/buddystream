<?php
/**
 * Add sharing button
 */

function buddystreamLinkedinSharing()
{
    global $bp;
    if (get_site_option("buddystream_linkedin_consumer_key") && get_site_option('buddystream_linkedin_export')) {

        if (get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token', 1)) {

            if(get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_reauth', 1)){
                echo'<a href="' . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=linkedin"><span class="buddystream_share_button linkedin" id="' . __('You need to re-authenticate on LinkedIn.', 'buddystream_linkedin') . '"></span></a>';
            }else{
                echo'<span class="buddystream_share_button linkedin" onclick="linkedin_addTag()" id="' . __('Also post this to my Linkedin account.', 'buddystream_linkedin') . '"></span>';
            }


            echo'<div class="linkedin_share_counterbox" style="display:none;">
        <div class="linkedin_share_counter">677</div>
        </div><div class="linkedin_hoverbox"></div>';
        }
    }
}

/**
 * Post update to Linkedin
 */

function buddystreamLinkedinPostUpdate($content = "", $shortLink = "", $user_id = 0)
{

    global $bp;
    $buddyStreamFilters = new BuddyStreamFilters();

    //strip out location tag
    $content = str_replace("#location", "", $content);

    $content = $buddyStreamFilters->filterPostContent($content, $shortLink);
    $content = '<?xml version="1.0" encoding="UTF-8"?><share><comment>' . $content . '</comment><visibility><code>anyone</code></visibility></share>';

    //Handle the oAuth requests
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setPostData($content);

    $buddystreamOAuth->oAuthRequestPostXml('https://api.linkedin.com/v1/people/~/shares?oauth2_access_token='.get_user_meta($user_id, 'buddystream_linkedin_token', 1));
}

/**
 *
 * Page loader functions
 *
 */

function buddystream_linkedin()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('linkedin');
}



/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamLinkedinUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_linkedin_token';");
}


/**
 * Count imported items for  user
 * @param $user_id
 * @return int
 */
function buddystreamLinkedinCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='linkedin';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamLinkedinImportOn($user_id){
    return get_user_meta($user_id, 'buddystream_linkedin_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamLinkedinResetUser($user_id){
    
    delete_user_meta($user_id, "buddystream_linkedin_synctoac");
    delete_user_meta($user_id, "buddystream_linkedin_lastupdate");
    delete_user_meta($user_id, "buddystream_linkedin_checkboxon");
    delete_user_meta($user_id, "buddystream_linkedin_counterdate");
    delete_user_meta($user_id, "buddystream_linkedin_tokensecret");
    delete_user_meta($user_id, "buddystream_linkedin_filtermentions");
    delete_user_meta($user_id, "buddystream_linkedin_synctoac");
    delete_user_meta($user_id, "buddystream_linkedin_counterdate");
    delete_user_meta($user_id, "buddystream_linkedin_checkboxon");
    delete_user_meta($user_id, "buddystream_linkedin_daycounter");
    delete_user_meta($user_id, "buddystream_linkedin_filtergood");
    delete_user_meta($user_id, "buddystream_linkedin_filterbad");
    delete_user_meta($user_id, "buddystream_linkedin_filtertoactivity");
    delete_user_meta($user_id, "buddystream_linkedin_filtertolinkedin");
    delete_user_meta($user_id, "buddystream_linkedin_profilelink");
    delete_user_meta($user_id, "buddystream_linkedin_token");
}

