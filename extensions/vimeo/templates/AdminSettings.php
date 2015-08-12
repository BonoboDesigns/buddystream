<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<script src="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/js/bootstrap.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/buddystream.css" rel="stylesheet">

<script src="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/jquery.slickswitch.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/slickswitch.css" rel="stylesheet">

<br><br>
<div id="buddystream" class="container">
    <div class="span9">

        <?php
            $buddyStreamExtensions = new BuddyStreamExtensions();
            echo $buddyStreamExtensions->tabLoader('vimeo');
        ?>

        <?php
            $arraySwitches = array(
                'buddystream_vimeo_album'
            );

            if ($_POST) {
                update_site_option('buddystream_vimeo_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_vimeo_user_settings_maximport']))));
                update_site_option('buddystream_vimeo_setup', true);

                foreach ($arraySwitches as $switch) {
                    update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
                }

                echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_vimeo') . '</div>';
            }
        ?>

        <blockquote>
            <p><?php
                _e('vimeo settings description', 'buddystream_vimeo'); ?></p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellpadding="0" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('User options', 'buddystream_vimeo');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td width="600"><?php _e('Show Vimeo album on user profile page?', 'buddystream_vimeo');?></td>
                    <td><input class="switch icons" type="checkbox" name="buddystream_vimeo_album"
                               id="buddystream_vimeo_album"/></td>
                </tr>

                <tr>
                    <td width="600"><?php _e('Maximum number of videos to import per user, per day (empty - unlimited):', 'buddystream_vimeo'); ?></td>
                    <td><input type="text" name="buddystream_vimeo_user_settings_maximport"
                               value="<?php echo get_site_option('buddystream_vimeo_user_settings_maximport'); ?>" size="5"/>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse" value="<?php _e('Save Changes', 'buddystream_vimeo') ?>"/></p>
        </form>

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