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

        <blockquote>
            <p>
                <?php _e('rss statitics description', 'buddystream_rss'); ?>
            </p>
        </blockquote>

        <?php

        global $bp, $wpdb; $component = "rss";


        ?>

        <table class="table table-striped" cellspacing="0">

            <thead>

            <thead>
            <tr>
                <th><?php _e('Statistics', 'buddystream_rss'); ?></th>
                <th></th>
            </tr>

            </thead>
            <tbody>

            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_rss_users = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_rss_feeds';"));
            $perc_rss_users = round(($count_rss_users / $count_users) * 100);
            $count_history = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='rss';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_rssupdates = round(($count_history / $count_activity * 100));
            $average_history_day = round($count_history / 24);
            $average_history_week = $average_history_day * 7;
            $average_history_month = $average_history_day * 30;
            $average_history_year = $average_history_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $count_users . "</td>
        </tr>
        <tr >
            <td>" . __('Amount of user using rss:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $count_rss_users . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using rss:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $perc_rss_users . "%</td>
        </tr>
       <tr >
            <td>" . __('Amount of activity updates:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of rss items:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $count_history . "</td>
        </tr>
        <tr >
            <td>" . __('Percentage of rss items:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $perc_rssupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average number of rss items imported per day:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $average_history_day . "</td>
        </tr>
        <tr >
            <td>" . __('Average number of rss items imported per week:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $average_history_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average number of rss items imported per month:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $average_history_month . "</td>
        </tr>
        <tr >
            <td>" . __('Average number of rss items imported per year:', 'buddystream_rss') . "</td>
            <td scope='row' class='column'>" . $average_history_year . "</td>
        </tr>
        ";
            ?>
            </tbody>

        </table>
    </div>
</div>

