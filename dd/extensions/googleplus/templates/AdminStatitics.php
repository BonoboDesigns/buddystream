<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet"
      xmlns="http://www.w3.org/1999/html">
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

        global $bp, $wpdb; $component = "googleplus";



        ?>

        <blockquote>
            <p><?php _e('googleplus statitics description', 'buddystream_googleplus');?></p>
        </blockquote>

        <table class="table table-striped" cellspacing="0">
            <thead>
            <th><?php echo __('Statistics', 'buddystream_googleplus'); ?></th>
            <th></th>
            </thead>

            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_itemstreamusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_googleplus_token';"));
            $perc_itemstreamusers = round(($count_itemstreamusers / $count_users) * 100);
            $count_items = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='googleplus';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_tweetupdates = round(($count_items / $count_activity * 100));
            $average_items_day = round($count_items / 24);
            $average_items_week = $average_items_day * 7;
            $average_items_month = $average_items_day * 30;
            $average_items_year = $average_items_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_googleplus') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Amount of user Google+ intergration:', 'buddystream_googleplus') . "</td>
            <td>" . $count_itemstreamusers . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users Google+ using intergration:', 'buddystream_googleplus') . "</td>
            <td>" . $perc_itemstreamusers . "%</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Amount of activity updates:', 'buddystream_googleplus') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of updates:', 'buddystream_googleplus') . "</td>
            <td>" . $count_items . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Percentage of items in activity updates:', 'buddystream_googleplus') . "</td>
            <td>" . $perc_tweetupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average items import per day:', 'buddystream_googleplus') . "</td>
            <td>" . $average_items_day . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average items import per week:', 'buddystream_googleplus') . "</td>
            <td>" . $average_items_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per month:', 'buddystream_googleplus') . "</td>
            <td>" . $average_items_month . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average items import per year:', 'buddystream_googleplus') . "</td>
            <td>" . $average_items_year . "</td>
        </tr>
        ";
            ?>

        </table>
    </div>
</div>

