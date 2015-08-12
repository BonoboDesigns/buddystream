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

        global $bp, $wpdb; $component = "linkedin";


        ?>


        <blockquote>
            <p>
                <?php _e('linkedin statitics description', 'buddystream_linkedin');?>
            </p>
        </blockquote>

        <form method="post" action="">


            <table class="table table-striped" cellspacing="0">

                <thead>
                <thead>
                <th><?php echo __('Statistics', 'buddystream_linkedin'); ?></th>
                <th></th>
                </thead>
                <tbody>

                <?php
                $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
                $count_buddystream_linkedinusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_linkedin_token';"));
                $perc_buddystream_linkedinusers = round(($count_buddystream_linkedinusers / $count_users) * 100);
                $count_items = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='linkedin';"));
                $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
                $perc_tweetupdates = round(($count_items / $count_activity * 100));
                $average_items_day = round($count_items / 24);
                $average_items_week = $average_items_day * 7;
                $average_items_month = $average_items_day * 30;
                $average_items_year = $average_items_day * 365;

                echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_linkedin') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of user LinkedIn intergration:', 'buddystream_linkedin') . "</td>
            <td>" . $count_buddystream_linkedinusers . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users LinkedIn using intergration:', 'buddystream_linkedin') . "</td>
            <td>" . $perc_buddystream_linkedinusers . "%</td>
        </tr>
        <tr>
            <td>" . __('Amount of activity updates:', 'buddystream_linkedin') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of items updates:', 'buddystream_linkedin') . "</td>
            <td>" . $count_items . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of items in activity updates:', 'buddystream_linkedin') . "</td>
            <td>" . $perc_tweetupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average items import per day:', 'buddystream_linkedin') . "</td>
            <td>" . $average_items_day . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per week:', 'buddystream_linkedin') . "</td>
            <td>" . $average_items_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per month:', 'buddystream_linkedin') . "</td>
            <td>" . $average_items_month . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per year:', 'buddystream_linkedin') . "</td>
            <td>" . $average_items_year . "</td>
        </tr>
        ";
                ?>
                </tbody>

            </table>
    </div>
</div>

