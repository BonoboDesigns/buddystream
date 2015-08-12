<?php

global $bp;

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_tokensecret_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_mention');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_filtermentions');
}

if (isset($_GET['oauth_token'])) {

    //Handle the oAuth requests
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('https://www.google.com/accounts/OAuthGetRequestToken');
    $buddystreamOAuth->setAccessTokenUrl('https://www.google.com/accounts/OAuthGetAccessToken');
    $buddystreamOAuth->setAuthorizeUrl('https://www.google.com/accounts/OAuthAuthorizeToken');
    $buddystreamOAuth->setCallbackUrl($bp->root_domain);
    $buddystreamOAuth->setParameters(array('oauth_verifier' => $_GET['oauth_verifier']));
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_googleplus_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_googleplus_consumer_secret"));
    $buddystreamOAuth->setRequestToken(get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token_temp', 1));
    $buddystreamOAuth->setRequestTokenSecret(get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_tokensecret_temp', 1));
    $accessToken = $buddystreamOAuth->accessToken();

    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token', '' . urldecode($accessToken['oauth_token']) . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_tokensecret', '' . $accessToken['oauth_token_secret'] . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_synctoac', 1);

    //for other plugins
    do_action('buddystream_googleplus_activated');

}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_synctoac', $_POST['buddystream_googleplus_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_filtermentions', $_POST['buddystream_googleplus_filtermentions']);

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_achievements', $_POST['buddystream_googleplus_achievements']);

   $message = __('Settings saved', 'buddystream_googleplus');
}

//put some options into variables
$buddystream_googleplus_synctoac = get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_synctoac', 1);
$buddystream_googleplus_filtermentions = get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_filtermentions', 1);

//achievements plugin
$buddystream_googleplus_achievements = get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=googleplus" method="post">
        <h3>' . __('Google+ Settings', 'buddystream_googleplus') . '</h3>';
    ?>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (get_site_option('buddystream_googleplus_import') == 0) { ?>


        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Synchronize items to my activity stream?', 'buddystream_googleplus'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="radio" name="buddystream_googleplus_synctoac"
                           value="1" <?php if ($buddystream_googleplus_synctoac == 1) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('Yes', 'buddystream_googleplus'); ?>

                    <input type="radio" name="buddystream_googleplus_synctoac"
                           value="0" <?php if ($buddystream_googleplus_synctoac == 0) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('No', 'buddystream_googleplus'); ?>
                </td>
            </tr>
            </tbody>
        </table>


    <?php } ?>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token', 1)): ?>
        <a href="?network=googleplus&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Google+ synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>

    </form>

<?php
} else {

    echo '<h3>' . __('Google+ setup</h3>
                 You may setup you Google+ intergration over here.<br/>
                 Before you can begin using Google+ with this site you must authorize on Google+ by clicking the link below.', 'buddystream_googleplus') . '<br/><br/>';

    //oauth
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('https://www.google.com/accounts/OAuthGetRequestToken');
    $buddystreamOAuth->setAccessTokenUrl('https://www.google.com/accounts/OAuthGetAccessToken');
    $buddystreamOAuth->setAuthorizeUrl('https://www.google.com/accounts/OAuthAuthorizeToken');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=googleplus');
    $buddystreamOAuth->setParameters(array('scope' => 'https://www.googleapis.com/auth/plus.me'));
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_googleplus_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_googleplus_consumer_secret"));

    //get requesttoken and save it for later use.
    $requestToken = $buddystreamOAuth->requestToken();
    $buddystreamOAuth->setRequestToken($requestToken['oauth_token']);
    $buddystreamOAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);

    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_token_temp', '' . urldecode($requestToken['oauth_token'] . ''));
    update_user_meta($bp->loggedin_user->id, 'buddystream_googleplus_tokensecret_temp', '' . $requestToken['oauth_token_secret'] . '');

    //get the redirect url for the user
    $redirectUrl = $buddystreamOAuth->getRedirectUrl();
    if ($redirectUrl) {
        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_googleplus') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_googleplus');
    }
}