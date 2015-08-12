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

            global $bp, $wpdb; $component = "vimeo";


        ?>



        <blockquote>
            <p><?php _e('vimeo statitics description', 'buddystream_vimeo'); ?></p>
        </blockquote>

        <table class="table table-striped" cellpadding="0" cellspacing="0">

            <thead>
            <tr>
                <th><?php _e('Statistics', 'buddystream_vimeo'); ?></th>
                <th></th>
            </tr>
            </thead>

            <?php
                $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
                $count_vimeo_users = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_vimeo_username';"));
                $perc_vimeo_users = round(($count_vimeo_users / $count_users) * 100);
                $count_history = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='vimeo';"));
                $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
                $perc_vimeoupdates = round(($count_history / $count_activity * 100));
                $average_history_day = round($count_history / 24);
                $average_history_week = $average_history_day * 7;
                $average_history_month = $average_history_day * 30;
                $average_history_year = $average_history_day * 365;

            echo "
                <tr>
                    <td>" . __('Amount of users:', 'buddystream_vimeo') . "</td>
                    <td>" . $count_users . "</td>
                </tr>
                <tr>
                    <td>" . __('Amount of user using Vimeo:', 'buddystream_vimeo') . "</td>
                    <td>" . $count_vimeo_users . "</td>
                </tr>
                <tr>
                    <td>" . __('Percentage of users using Vimeo:', 'buddystream_vimeo') . "</td>
                    <td>" . $perc_vimeo_users . "%</td>
                </tr>
               <tr>
                    <td>" . __('Amount of activity updates:', 'buddystream_vimeo') . "</td>
                    <td>" . $count_activity . "</td>
                </tr>
                <tr>
                    <td>" . __('Amount of Vimeo videos:', 'buddystream_vimeo') . "</td>
                    <td>" . $count_history . "</td>
                </tr>
                <tr>
                    <td>" . __('Percentage of Vimeo videos:', 'buddystream_vimeo') . "</td>
                    <td>" . $perc_vimeoupdates . "%</td>
                </tr>
                <tr>
                    <td>" . __('Average number of Vimeo videos imported per day:', 'buddystream_vimeo') . "</td>
                    <td>" . $average_history_day . "</td>
                </tr>
                <tr>
                    <td>" . __('Average number of Vimeo videos imported per week:', 'buddystream_vimeo') . "</td>
                    <td>" . $average_history_week . "</td>
                </tr>
                <tr>
                    <td>" . __('Average number of Vimeo videos imported per month:', 'buddystream_vimeo') . "</td>
                    <td>" . $average_history_month . "</td>
                </tr>
                <tr>
                    <td>" . __('Average number of Vimeo videos imported per year:', 'buddystream_vimeo') . "</td>
                    <td>" . $average_history_year . "</td>
                </tr>
                ";
            ?>
            </tbody>

        </table>
    </div>
</div>