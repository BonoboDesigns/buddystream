<?php

global $bp;

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_tokensecret_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_mention');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_synctoac');
}


if (isset($_GET['code'])) {
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setAccessTokenUrl('https://foursquare.com/oauth2/access_token');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-foursquare');
    $buddystreamOAuth->setParameters(
        array(
            'redirect_uri' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=foursquare',
            'client_id' => get_site_option("buddystream_foursquare_consumer_key"),
            'client_secret' =>get_site_option("buddystream_foursquare_consumer_secret"),
            'grant_type' => 'authorization_code',
            'code' => $_GET['code']));

    //get accesstoken and save it
    $accessToken = json_decode($buddystreamOAuth->accessToken(true));

    update_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', $accessToken->access_token);
    update_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_synctoac', 1);

    //for other plugins
    do_action('buddystream_foursquare_activated');

}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_synctoac', $_POST['buddystream_foursquare_synctoac']);
 
    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_achievements', $_POST['buddystream_foursquare_achievements']);

    $message = __('Settings saved', 'buddystream_foursquare');
}

//put some options into variables
$buddystream_foursquare_synctoac = get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_synctoac', 1);
$buddystream_foursquare_filtermentions = get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_filtermentions', 1);

//achievements plugin
$buddystream_foursquare_achievements = get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=foursquare" method="post">
        <h3>' . __('Foursquare Settings', 'buddystream_foursquare') . '</h3>';
    ?>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!get_site_option('buddystream_foursquare_import')) {
        _e('There are no settings available.</br></br>', 'buddystream_foursquare');
    } else {
        ?>

        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Synchronize checkins to my activity stream?', 'buddystream_foursquare'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="radio" name="buddystream_foursquare_synctoac"
                           value="1" <?php if ($buddystream_foursquare_synctoac == 1) {
                        echo 'checked';
                    } ?> />
                   <?php echo __('Yes', 'buddystream_foursquare'); ?>

                    <input type="radio" name="buddystream_foursquare_synctoac"
                           value="0" <?php if ($buddystream_foursquare_synctoac == 0) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('No', 'buddystream_foursquare'); ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>
            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo __('Send achievements unlock to my foursquare', 'buddystream_foursquare');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="radio" name="buddystream_foursquare_achievements" id="buddystream_foursquare_achievements"
                               value="1" <?php if ($buddystream_foursquare_achievements == 1) {
                            echo'checked';
                        }?>> <?php echo __('Yes', 'buddsytream_lang'); ?>
                        <input type="radio" name="buddystream_foursquare_achievements" id="buddystream_foursquare_achievements"
                               value="0" <?php if ($buddystream_foursquare_achievements == 0) {
                            echo'checked';
                        }?>> <?php echo __('No', 'buddsytream_lang'); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php } ?>

    <?php } ?>

    <br/><br/>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_foursquare_token', 1)): ?>
        <a href="?network=foursquare&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Foursquare synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>
    </form>

<?php
} else {

    echo '<h3>' . __('Foursquare setup</h3>
                 You may setup you foursquare intergration over here.<br/>
                 Before you can begin using Foursquare with this site you must authorize on Foursquare by clicking the link below.', 'buddystream_foursquare') . '<br/><br/>';

    //get the redirect url for the user
    $redirectUrl = "https://foursquare.com/oauth2/authenticate?client_id=".get_site_option("buddystream_foursquare_consumer_key")."&response_type=code&redirect_uri=".urlencode($bp->loggedin_user->domain . BP_SETTINGS_SLUG . "/buddystream-networks/?network=foursquare");

    if ($redirectUrl) {
        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_foursquare') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_foursquare');
    }
}