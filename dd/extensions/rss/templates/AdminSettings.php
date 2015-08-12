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
        echo $buddyStreamExtensions->tabLoader('rss');
        ?>

        <?php

        if ($_POST) {
            update_site_option('buddystream_rss_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_rss_user_settings_maximport']))));
            update_site_option('buddystream_rss_setup', true);


            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_rss');
        }
        ?>

        <blockquote>
            <p>
                <?php  _e('rss settings description', 'buddystream_rss'); ?>
            </p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('User options', 'buddystream_rss');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td><?php _e('Maximum number of items to import per user, per day (empty - unlimited):', 'buddystream_rss'); ?></td>
                    <td><input type="text" name="buddystream_rss_user_settings_maximport"
                               value="<?php echo get_site_option('buddystream_rss_user_settings_maximport'); ?>"
                               size="5"/></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>

    </div>
</div>