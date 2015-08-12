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
        echo $buddyStreamExtensions->tabLoader('tumblr');
        ?>

        <?php
            if ($_POST) {
                update_site_option('buddystream_tumblr_filter', trim(strip_tags(strtolower($_POST ['buddystream_tumblr_filter']))));
                update_site_option('buddystream_tumblr_filter_show', trim(strip_tags($_POST ['buddystream_tumblr_filter_show'])));
                update_site_option('buddystream_tumblr_filterexplicit', trim(strip_tags(strtolower($_POST ['buddystream_tumblr_filterexplicit']))));
                $message = __('Filters saved.', 'buddystream_tumblr');
            }
        ?>

        <blockquote>
            <p><?php _e('tumblr filters description', 'buddystream_tumblr');?></p>
        </blockquote>

<form method="post" action="">

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>


    <table class="table table-striped" cellspacing="0">

        <thead>
            <tr>
                <th><?php echo __('Tumblr filters (optional)', 'buddystream_tumblr');?></th>
                <th></th>
            </tr>
        </thead>

        <tr>
            <td><?php echo __('Filters (comma seperated)', 'buddystream_tumblr');?></td>
            <td><input type="text" name="buddystream_tumblr_filter"
                       value="<?php echo get_site_option('buddystream_tumblr_filter');?>" size="50"/></td>
        </tr>

        <tr>
            <td><?php echo __('Explicit words (comma seperated)', 'buddystream_tumblr');?></td>
            <td><input type="text" name="buddystream_tumblr_filterexplicit"
                       value="<?php echo get_site_option('buddystream_tumblr_filterexplicit');?>" size="50"/></td>
        </tr>
    </table>
    <p class="submit"><input type="submit" class="btn btn-inverse"
                             value="<?php _e('Save Changes', 'buddystream_tumblr') ?>"/></p>
</form>
        </div>
    </div>