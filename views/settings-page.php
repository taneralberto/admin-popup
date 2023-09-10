<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <?php
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=admin_popup_settings&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'General Options' ); ?></a>
    </h2>

    <form action="options.php" method="POST">
        <?php
        if( $active_tab == 'general_options' ) {
            settings_fields( 'admin_popup_group' );
            do_settings_sections( 'admin_popup_general_page' );
        } else {
            settings_fields( 'admin_popup_group' );
            do_settings_sections( 'admin_popup_priority' );
        }

        submit_button( esc_html__( 'Save Settings', 'admin-popup' ) );
        ?>
    </form>
</div>