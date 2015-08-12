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
            echo $buddyStreamExtensions->tabLoader('foursquare');
        ?>

        <?php
        global $bp;

        if ($_POST) {
            update_site_option('buddystream_foursquare_consumer_key', trim($_POST['buddystream_foursquare_consumer_key']));
            update_site_option('buddystream_foursquare_consumer_secret', trim($_POST['buddystream_foursquare_consumer_secret']));
            update_site_option('buddystream_foursquare_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_foursquare_user_settings_maximport']))));

            update_site_option('buddystream_foursquare_map_width', trim(strip_tags(strtolower($_POST['buddystream_foursquare_map_width']))));
            update_site_option('buddystream_foursquare_map_height', trim(strip_tags(strtolower($_POST['buddystream_foursquare_map_height']))));
            update_site_option('buddystream_foursquare_map_zoom', trim(strip_tags(strtolower($_POST['buddystream_foursquare_map_zoom']))));

            if ($_POST['buddystream_foursquare_consumer_key']) {
                update_site_option('buddystream_foursquare_setup', true);
            }

            $message = __('Settings saved.', 'buddystream_foursquare');
        }
        ?>

        <blockquote>
            <p>
                <?php echo str_replace("#ROOTDOMAIN", $bp->root_domain, __('foursquare settings description', 'buddystream_foursquare')); ?>
            </p>
        </blockquote>


        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php _e('Foursquare API', 'buddystream_foursquare');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="600"><?php _e('Client key:', 'buddystream_foursquare');?></td>
                    <td><input type="text" name="buddystream_foursquare_consumer_key"
                               value="<?php echo get_site_option('buddystream_foursquare_consumer_key'); ?>" size="50"/></td>
                </tr>

                <tr>
                    <td width="600"><?php _e('Client secret:', 'buddystream_foursquare');?></td>
                    <td><input type="text" name="buddystream_foursquare_consumer_secret"
                               value="<?php echo get_site_option('buddystream_foursquare_consumer_secret'); ?>" size="50"/></td>
                </tr>
                </tbody>
            </table>

            <?php if (get_site_option('buddystream_foursquare_consumer_key') && get_site_option('buddystream_foursquare_consumer_secret')) { ?>

                <table class="table table-striped" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_foursquare');?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr valign="top">
                        <td width="600"><?php _e('Maximum checkins to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_foursquare'); ?></td>
                        <td><input type="text" name="buddystream_foursquare_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_foursquare_user_settings_maximport'); ?>"
                                   size="5"/></td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-striped" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php _e('Map options (optional)', 'buddystream_foursquare');?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td width="600"><?php _e('Map zoom', 'buddystream_foursquare');?></td>
                        <td><input type="text" name="buddystream_foursquare_map_zoom"
                                   value="<?php echo get_site_option('buddystream_foursquare_map_zoom'); ?>" size="50"/></td>
                    </tr>

                    <tr>
                        <td width="600"><?php _e('Map width', 'buddystream_foursquare');?></td>
                        <td><input type="text" name="buddystream_foursquare_map_width"
                                   value="<?php echo get_site_option('buddystream_foursquare_map_width'); ?>" size="50"/></td>
                    </tr>

                    <tr valign="top">
                        <td width="600"><?php _e('Map height', 'buddystream_foursquare');?></td>
                        <td><input type="text" name="buddystream_foursquare_map_height"
                                   value="<?php echo get_site_option('buddystream_foursquare_map_height'); ?>" size="50"/></td>
                    </tr>


                    </tbody>
                </table>

            <?php } ?>


            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_foursquare') ?>"/></p>
        </form>

    </div>
</div>
