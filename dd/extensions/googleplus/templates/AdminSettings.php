<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet">
<script src="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/js/bootstrap.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/buddystream.css" rel="stylesheet">

<script src="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/jquery.slickswitch.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/slickswitch.css" rel="stylesheet">

<br><br>
<div id="buddystream" class="container">
    <div class="span9">

        <?php
            $buddyStreamExtensions = new BuddyStreamExtensions();
            echo $buddyStreamExtensions->tabLoader('googleplus');
        ?>
        <?php

        $arraySwitches = array(
            'buddystream_googleplus_album',
            'buddystream_googleplus_user_settings_syncbp'
        );

        if ($_POST) {
            update_site_option('buddystream_googleplus_consumer_key', trim($_POST['buddystream_googleplus_consumer_key']));
            update_site_option('buddystream_googleplus_consumer_secret', trim($_POST['buddystream_googleplus_consumer_secret']));
            update_site_option('buddystream_googleplus_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_googleplus_user_settings_maximport']))));

            if ($_POST['buddystream_googleplus_consumer_key']) {
                update_site_option('buddystream_googleplus_setup', true);
            }

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_googleplus');
        }
        ?>

        <blockquote>
            <p><?php global $bp; echo str_replace("#DOMAIN", "<strong>" . $bp->root_domain . "</strong>", __('googleplus settings description', 'buddystream_googleplus')); ?></p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('Google+ API', 'buddystream_googleplus');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td width="600"><?php _e('Consumer key:', 'buddystream_googleplus');?></td>
                    <td><input type="text" name="buddystream_googleplus_consumer_key"
                               value="<?php echo get_site_option('buddystream_googleplus_consumer_key'); ?>" size="50"/>
                    </td>
                </tr>

                <tr>
                    <td width="600"><?php _e('Consumer secret key:', 'buddystream_googleplus');?></td>
                    <td><input type="text" name="buddystream_googleplus_consumer_secret"
                               value="<?php echo get_site_option('buddystream_googleplus_consumer_secret'); ?>"
                               size="50"/></td>
                </tr>

                </tbody>

            </table>

            <?php if (get_site_option('buddystream_googleplus_consumer_key') && get_site_option('buddystream_googleplus_consumer_secret')) { ?>

                <table class="table table-striped" cellspacing="0">

                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_googleplus');?></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td width="600"><?php _e('Show Google+ album on user profile page?', 'buddystream_googleplus');?></td>
                        <td><input class="switch icons" type="checkbox" name="buddystream_googleplus_album"
                                   id="buddystream_googleplus_album"/></td>
                    </tr>

                    <tr>
                        <td width="600"><?php _e('Allow users to sync Google+ to you website?', 'buddystream_googleplus');?></td>
                        <td><input class="switch icons" type="checkbox"
                                   name="buddystream_googleplus_user_settings_syncbp"
                                   id="buddystream_googleplus_user_settings_syncbp"/></td>
                    </tr>

                    <tr>
                        <td><?php _e('Maximum items to be imported per user, per day (empty = unlimited items import):', 'buddystream_googleplus'); ?></td>
                        <td><input type="text" name="buddystream_googleplus_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_googleplus_user_settings_maximport'); ?>"
                                   size="5"/></td>
                    </tr>

                    </tbody>
                </table>



            <?php } ?>

            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>

<script type="text/javascript">
    jQuery(".switch").slickswitch();
</script>

<?php
foreach ($arraySwitches as $switch) {
    if (get_site_option($switch)) {
        echo'
        <script>
            jQuery("#' . $switch . '").slickswitch("toggleOn");
        </script>
        ';
    } else {
        echo'
        <script>
            jQuery("#' . $switch . '").slickswitch("toggleOff");
        </script>
        ';
    }
}
?>