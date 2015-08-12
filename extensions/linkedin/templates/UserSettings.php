<?php
if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac');
}

if (isset($_GET['code'])) {

    //Handle the oAuth requests
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setAccessTokenUrl('https://www.linkedin.com/uas/oauth2/accessToken');

    $buddystreamOAuth->setParameters(array(
            'grant_type' => 'authorization_code',
            'code' => $_GET['code'],
            'redirect_uri' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=linkedin',
            'client_id' => get_site_option("buddystream_linkedin_consumer_key"),
            'client_secret' => get_site_option("buddystream_linkedin_consumer_secret")
        ));

    $accessToken = $buddystreamOAuth->accessToken(true);

    $accessToken = json_decode($accessToken);

    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token', '' . $accessToken->access_token);
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac', 1);
    delete_user_meta($bp->loggedin_user->id, "buddystream_linkedin_reauth", false);

    //for other plugins
    do_action('buddystream_linkedin_activated');
}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac', $_POST['buddystream_linkedin_synctoac']);

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_achievements', $_POST['buddystream_linkedin_achievements']);

    $message = __('Settings saved', 'buddystream_linkedin');
}

//put some options into variables
$buddystream_linkedin_synctoac = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac', 1);

//achievements plugin
$buddystream_linkedin_achievements = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token', 1) && ! get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_reauth', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=linkedin" method="post">
        <h3>' . __('LinkedIn Settings', 'buddystream_linkedin') . '</h3>';
    ?>


    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!get_site_option('buddystream_linkedin_import')) {
        _e('There are no settings available.</br></br>', 'buddystream_linkedin');

    } else {
        ?>

        <table class="table table-striped" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo __('Synchronize LinkedIn updates to my activity stream?', 'buddystream_linkedin'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="radio" name="buddystream_linkedin_synctoac" id="buddystream_linkedin_synctoac"
                               value="1" <?php if ($buddystream_linkedin_synctoac == 1) {
                            echo 'checked';
                        } ?> />
                        <?php echo __('Yes', 'buddystream_linkedin'); ?>

                        <input type="radio" name="buddystream_linkedin_synctoac" id="buddystream_linkedin_synctoac"
                               value="0" <?php if ($buddystream_linkedin_synctoac == 0) {
                            echo 'checked';
                        } ?> />
                        <?php echo __('No', 'buddystream_linkedin'); ?>
                    </td>
                </tr>
            </tbody>
        </table>


        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo __('Send achievements unlock to my LinkedIn', 'buddystream_linkedin');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="radio" name="buddystream_linkedin_achievements" id="buddystream_linkedin_achievements"
                               value="1" <?php if ($buddystream_linkedin_achievements == 1) {
                            echo'checked';
                        }?>> <?php echo __('Yes', 'buddsytream_lang'); ?>
                        <input type="radio" name="buddystream_linkedin_achievements" id="buddystream_linkedin_achievements"
                               value="0" <?php if ($buddystream_linkedin_achievements == 0) {
                            echo'checked';
                        }?>> <?php echo __('No', 'buddsytream_lang'); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php } ?>

    <?php } ?>

    <br/>
    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token', 1)): ?>
        <a href="?network=linkedin&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove LinkedIn synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>

    </form>

<?php
} else {

    echo '<h3>' . __('LinkedIn setup</h3>
                 You may setup you linkedIn intergration over here.<br/>
                 Before you can begin using LinkedIn with this site you must authorize on LinkedIn by clicking the link below.', 'buddystream_linkedin') . '<br/><br/>';

    //oauth
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('https://api.linkedin.com/uas/oauth/requestToken');
    $buddystreamOAuth->setAccessTokenUrl('https://api.linkedin.com/uas/oauth/accessToken');
    $buddystreamOAuth->setAuthorizeUrl('https://api.linkedin.com/uas/oauth/authorize');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=linkedin');
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));

    //get requesttoken and save it for later use.
    $requestToken = $buddystreamOAuth->requestToken();
    $buddystreamOAuth->setRequestToken($requestToken['oauth_token']);
    $buddystreamOAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);

    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token_temp', '' . $requestToken['oauth_token'] . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_tokensecret_temp', '' . $requestToken['oauth_token_secret'] . '');

    //get the redirect url for the user
    //$redirectUrl = $buddystreamOAuth->getRedirectUrl();
    // $redirectUrl =  "https://www.linkedin.com/uas/oauth2/authorization?scope=rw_nus&response_type=code&client_id=".get_site_option("buddystream_linkedin_consumer_key")."&state=".uniqid()."&redirect_uri=" . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . "/buddystream-networks/?network=linkedin";
    $redirectUrl =  "https://www.linkedin.com/uas/oauth2/authorization?scope=w_share&response_type=code&client_id=".get_site_option("buddystream_linkedin_consumer_key")."&state=".uniqid()."&redirect_uri=" . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . "/buddystream-networks/?network=linkedin";

    if ($redirectUrl) {
        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_linkedin') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_linkedin');
    }
}