
<?php
    function tidsrapportering_save() {
        ob_start();
        ?>
        <div class="wrap">
        <h1><?php echo PLUGIN_NAME; ?></h1>
        <?php
        include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/tidsrapportering-admin-form.php';
        ?>
        </div>
        <?php
        echo ob_get_clean();
    }

?>