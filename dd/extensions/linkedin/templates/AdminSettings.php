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
        echo $buddyStreamExtensions->tabLoader('linkedin');
        ?>

        <?php


        if ($_POST) {
            update_site_option('buddystream_linkedin_consumer_key', trim(strip_tags($_POST['buddystream_linkedin_consumer_key'])));
            update_site_option('buddystream_linkedin_consumer_secret', trim(strip_tags($_POST['buddystream_linkedin_consumer_secret'])));
            update_site_option('buddystream_linkedin_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_linkedin_user_settings_maximport']))));

            if ($_POST['buddystream_linkedin_consumer_key']) {
                update_site_option('buddystream_linkedin_setup', true);
            }

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_linkedin');
        }
        ?>

        <blockquote>
            <p>
                <?php global $bp; echo str_replace("#ROOTDOMAIN", $bp->root_domain, __('linkedin settings description', 'buddystream_linkedin')); ?>
            </p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('Linkedin API', 'buddystream_linkedin');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td><?php _e('API key:', 'buddystream_linkedin');?></td>
                    <td><input type="text" name="buddystream_linkedin_consumer_key"
                               value="<?php echo get_site_option('buddystream_linkedin_consumer_key'); ?>" size="50"/>
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Api secret:', 'buddystream_linkedin');?></td>
                    <td><input type="text" name="buddystream_linkedin_consumer_secret"
                               value="<?php echo get_site_option('buddystream_linkedin_consumer_secret'); ?>"
                               size="50"/></td>
                </tr>

                </tbody>
            </table>

            <?php if (get_site_option('buddystream_linkedin_consumer_key') && get_site_option('buddystream_linkedin_consumer_secret')) { ?>

                <table class="table table-striped" cellspacing="0">

                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_linkedin');?></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td width="600"><?php _e('Maximum LinkedIn updates to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_linkedin'); ?></td>
                        <td><input type="text" name="buddystream_linkedin_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_linkedin_user_settings_maximport'); ?>"
                                   size="5"/></td>
                    </tr>

                    </tbody>
                </table>

            <?php } ?>


            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>