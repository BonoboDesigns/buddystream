<?php
if ($_POST) {

    update_user_meta($bp->loggedin_user->id, 'buddystream_rss_feeds', $_POST['buddystream_rss_feeds']);
    $message = __('Settings saved', 'buddystream_lang');
}

$buddystream_rss_feeds = get_user_meta($bp->loggedin_user->id, 'buddystream_rss_feeds', 1);

if ($buddystream_rss_feeds) {
    do_action('buddystream_rss_activated');
}
?>

<form id="settings_form"
      action="<?php echo  $bp->loggedin_user->domain . BP_SETTINGS_SLUG; ?>/buddystream-networks/?network=rss"
      method="post">
    <h3><?php echo __('Rss Settings', 'buddystream_lang')?></h3>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="table table-striped" cellspacing="0">
        <thead>
        <tr>
            <th><?php _e('Feeds (new line per feed)', 'buddystream_rss');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <textarea name="buddystream_rss_feeds" rows="5" style="width:98%;"><?php echo $buddystream_rss_feeds; ?></textarea>
            </td>
        </tr>
        </tbody>
    </table>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">
</form>