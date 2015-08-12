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
            update_site_option('buddystream_rss_filter', trim(strip_tags(strtolower($_POST['buddystream_rss_filter']))));
            update_site_option('buddystream_rss_filter_show', trim(strip_tags($_POST['buddystream_rss_filter_show'])));
            update_site_option('buddystream_rss_filterexplicit', trim(strip_tags(strtolower($_POST['buddystream_rss_filterexplicit']))));

            $message = __('Filters saved.', 'buddystream_rss');
        }
        ?>

        <blockquote>
            <p>
                <?php _e('rss filters description', 'buddystream_rss'); ?>
            </p>
        </blockquote>


        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('Rss filters (optional)', 'buddystream_rss');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td scope="row"><?php _e('Filters (comma seperated)', 'buddystream_rss');?></td>
                    <td>
                        <input type="text" name="buddystream_rss_filter"
                               value="<?php echo get_site_option('buddystream_rss_filter');?>" size="50"/>
                    </td>
                </tr>

                <tr class="odd">
                    <td scope="row"><?php _e('Explicit words filters (comma seperated)', 'buddystream_rss');?></td>
                    <td>
                        <input type="text" name="buddystream_rss_filterexplicit"
                               value="<?php echo get_site_option('buddystream_rss_filterexplicit');?>" size="50"/>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>