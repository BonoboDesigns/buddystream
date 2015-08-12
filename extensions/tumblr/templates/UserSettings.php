<?php

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_import');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out');
}

if (isset($_GET['oauth_token'])) {

    //Handle the oAuth requests
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('http://www.tumblr.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('http://www.tumblr.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('http://www.tumblr.com/oauth/authorize');

    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=tumblr');
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_tumblr_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_tumblr_consumer_secret"));
    $buddystreamOAuth->setParameters(array('oauth_verifier' => $_GET['oauth_verifier']));

    $buddystreamOAuth->setRequestToken(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token_temp', 1));
    $buddystreamOAuth->setRequestTokenSecret(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret_temp', 1));
    $accessToken = $buddystreamOAuth->accessToken();

    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', '' . $accessToken['oauth_token'] . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret', '' . $accessToken['oauth_token_secret'] . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_synctoac', 1);

    //for other plugins
    do_action('buddystream_tumblr_activated');

}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_synctoac', $_POST['buddystream_tumblr_synctoac']);

    if ($_POST['buddystream_tumblr_blogs_import']) {
        delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_import');
        update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_import', implode(',', $_POST['buddystream_tumblr_blogs_import']));
    }

    if ($_POST['buddystream_tumblr_blogs_out']) {
        delete_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out');
        update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out', implode(',', $_POST['buddystream_tumblr_blogs_out']));
    }

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_achievements', $_POST['buddystream_tumblr_achievements']);

    $message = __('Settings saved', 'buddystream_tumblr');
}

//put some options into variables
$buddystream_tumblr_synctoac = get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_synctoac', 1);


//achievements plugin
$buddystream_tumblr_achievements = get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=tumblr" method="post">
        <h3>' . __('Tumblr Settings', 'buddystream_tumblr') . '</h3>';
    ?>


    <?php if (!get_site_option('buddystream_tumblr_import')) {
        _e('There are no settings available.</br></br>', 'buddystream_tumblr');

    } else {

        //get the blogs for the settings
        //Handle the OAuth requests
        $buddystreamOAuth = new BuddyStreamOAuth();
        $buddystreamOAuth->setRequestTokenUrl('http://www.tumblr.com/oauth/request_token');
        $buddystreamOAuth->setAccessTokenUrl('http://www.tumblr.com/oauth/access_token');
        $buddystreamOAuth->setAuthorizeUrl('http://www.tumblr.com/oauth/authorize');
        $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_tumblr_consumer_key"));
        $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_tumblr_consumer_secret"));

        $buddystreamOAuth->setAccessToken(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', 1));
        $buddystreamOAuth->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret', 1));

        $blogs = $buddystreamOAuth->oAuthRequest('http://api.tumblr.com/v2/user/info');
        $blogs = json_decode($blogs);
        ?>

        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Synchronize Tumblr updates to my activity stream?', 'buddystream_tumblr'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="radio" name="buddystream_tumblr_synctoac"
                           value="1" <?php if ($buddystream_tumblr_synctoac == 1) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('Yes', 'buddystream_tumblr'); ?>

                    <input type="radio" name="buddystream_tumblr_synctoac"
                           value="0" <?php if ($buddystream_tumblr_synctoac == 0) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('No', 'buddystream_tumblr'); ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php echo __('When i share to tumblr, share to these blogs', 'buddystream_tumblr'); ?><br/><br/>
         <table class="table table-striped" cellspacing="0">
            <tbody>
                <?php
                    //get saved blogs
                    $savedTumblrBlogsBlogsOut = get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out', 1);
                    $savedTumblrBlogsBlogsOut = explode(',', $savedTumblrBlogsBlogsOut);

                    if ($blogs->response->user->blogs) {
                        foreach ($blogs->response->user->blogs as $blog) {
                            $checked = "";
                            if (get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_out', 1) && in_array($blog->name, $savedTumblrBlogsBlogsOut)) {
                                $checked = "checked";
                            }

                            echo'<tr><td><input type="checkbox" name="buddystream_tumblr_blogs_out[]" value="' . $blog->name . '" ' . $checked . '/> ' . $blog->url . '</td></tr>';
                        }
                    }
                ?>
            </tbody>
        </table>

        <?php echo __('Import blog items from these blogs', 'buddystream_tumblr'); ?><br/><br/>
        <table class="table table-striped" cellspacing="0">
            <tbody>
                <?php
                    //get saved blogs
                    $savedTumblrBlogsBlogsImport = get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_import', 1);
                    $savedTumblrBlogsBlogsImport = explode(',', $savedTumblrBlogsBlogsImport);

                    if ($blogs->response->user->blogs) {
                        foreach ($blogs->response->user->blogs as $blog) {
                            $checked = "";
                            if (get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_blogs_import', 1) && in_array($blog->name, $savedTumblrBlogsBlogsImport)) {
                                $checked = "checked";
                            }

                            echo'<tr><td><input type="checkbox" name="buddystream_tumblr_blogs_import[]" value="' . $blog->name . '" ' . $checked . '/> ' . $blog->url . '</td></tr>';
                        }
                    }
                ?>
            </tbody>
        </table>


        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo __('Send achievements unlock to my LinkedIn', 'buddystream_tumblr');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="radio" name="buddystream_tumblr_achievements" id="buddystream_tumblr_achievements"
                               value="1" <?php if ($buddystream_tumblr_achievements == 1) {
                            echo'checked';
                        }?>> <?php echo __('Yes', 'buddsytream_lang'); ?>
                        <input type="radio" name="buddystream_tumblr_achievements" id="buddystream_tumblr_achievements"
                               value="0" <?php if ($buddystream_tumblr_achievements == 0) {
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

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token', 1)): ?>
        <a href="?network=tumblr&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Tumblr synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>

    </form>

<?php
} else {

    echo '<h3>' . __('Tumblr setup', 'buddystream_tumblr') . '</h3>';

    echo __('You may setup you Tumblr intergration over here.<br/>
                 Before you can begin using Tumblr with this site you must authorize on Tumblr by clicking the link below.', 'buddystream_tumblr') . '<br/><br/>';

    //oauth
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('http://www.tumblr.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('http://www.tumblr.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('http://www.tumblr.com/oauth/authorize');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=tumblr');
    $buddystreamOAuth->setConsumerKey(get_site_option("buddystream_tumblr_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("buddystream_tumblr_consumer_secret"));

    //get requesttoken and save it for later use.
    $requestToken = $buddystreamOAuth->requestToken();
    $buddystreamOAuth->setRequestToken($requestToken['oauth_token']);
    $buddystreamOAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);

    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_token_temp', '' . $requestToken['oauth_token'] . '');
    update_user_meta($bp->loggedin_user->id, 'buddystream_tumblr_tokensecret_temp', '' . $requestToken['oauth_token_secret'] . '');

    //get the redirect url for the user
    $redirectUrl = $buddystreamOAuth->getRedirectUrl();

    if ($redirectUrl) {

        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_tumblr') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_tumblr');
    }
}