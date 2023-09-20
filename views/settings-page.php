<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <?php
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?post_type=admin_popup&page=admin_popup_settings&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'General Options' ); ?></a>
        <a href="?post_type=admin_popup&page=admin_popup_settings&tab=users_options" class="nav-tab <?php echo $active_tab === 'users_options' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Users Options' ); ?></a>
    </h2>

    <form action="options.php" method="POST">
        <?php
        if( $active_tab === 'general_options' ) {
            settings_fields( 'admin_popup_group' );
            do_settings_sections( 'admin_popup_general_page' );
        } elseif ( $active_tab === 'users_options' ) {
            settings_fields( 'admin_popup_group' );
            do_settings_sections( 'admin_popup_users_page' );
        }

        submit_button( esc_html__( 'Save Settings', 'admin-popup' ) );
        ?>
    </form>
</div>