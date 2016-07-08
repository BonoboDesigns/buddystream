<div id="buddystream" class="container-fluid">

    <?php
        if (!isset($_GET['network'])) {
            echo "<h3>" . __('Social networks', 'buddystream_lang') . "</h3>";
            echo __('Social networks setup description', 'buddystream_lang') . "<br/><br/>";
        }
    ?>

            <div class="nav_unused">
                <?php
                    //get the active
                    $varsdfsdfsdf = $_GET["network"];
                    $buddyStreamExtension = new BuddyStreamExtensions();
                    foreach ($buddyStreamExtension->getExtensionsConfigs() as $extension) {
                        if (get_site_option('buddystream_' . $extension['name'] . '_power') == "on" && get_site_option('buddystream_' . $extension['name'] . '_setup') && !$extension['parent']) {
                            echo '<div><a class="btn waves-effect';
                            if ($extension['name'] == $varsdfsdfsdf) {echo ' green';}
                            echo '" href="?network=' . $extension['name'] . '" id="' . ucfirst($extension['displayname']) . '">' . ucfirst($extension['displayname']) . '</a></div>';
                            $activeExtensions[] = $extension['name'];
                        }
                    }
                ?>
            </div>

    <br/>

    <?php
        if (isset($_GET['network'])) {
            include(BP_BUDDYSTREAM_DIR . "/extensions/" . $_GET['network'] . "/templates/UserSettings.php");
        }
    ?>

</div>