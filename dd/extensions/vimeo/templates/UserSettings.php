<?php

if (isset($_GET['reset'])) {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_vimeo_username');
}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_vimeo_username', $_POST['buddystream_vimeo_username']);
   $message = __('Settings saved', 'buddystream_lang');
}

$buddystream_vimeo_username = get_user_meta($bp->loggedin_user->id, 'buddystream_vimeo_username', 1);
if ($buddystream_vimeo_username) {
    do_action('buddystream_vimeo_activated');
}
?>

<form id="settings_form"
      action="<?php echo  $bp->loggedin_user->domain . BP_SETTINGS_SLUG; ?>/buddystream-networks/?network=vimeo"
      method="post">

    <h3><?php echo __('Vimeo Settings', 'buddystream_lang')?></h3>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="table table-striped" cellspacing="0">
        <thead>
        <tr>
            <th><?php echo __('Vimeo username', 'buddystream_lang');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" name="buddystream_vimeo_username" value="<?php echo $buddystream_vimeo_username; ?>" size="50"/>
            </td>
        </tr>
        </tbody>
    </table>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_vimeo_username', 1)): ?>
        <a href="?network=vimeo&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Vimeo synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>

</form>