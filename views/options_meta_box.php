<?php

global $post;
$admin_popup_options = get_post_meta( $post->ID, "admin_popup_options", true );

var_dump( $admin_popup_options );

?>
<table class="form-table">
    <input type="hidden" name="admin_popup_options_nonce" value="<?php echo wp_create_nonce( 'admin_popup_options_nonce'); ?>">

    <td>
        <select name="admin_popup_options[style]" id="admin_popup_style">
            <option value="style-1" <?php isset( $admin_popup_options['style'] ) ? selected( $admin_popup_options['style'], 'style-1' ) : ''; ?>><?php echo esc_html__('Style 1'); ?></option>
            <option value="style-2" <?php isset( $admin_popup_options['style'] ) ? selected( $admin_popup_options['style'], 'style-2' ) : ''; ?>><?php echo esc_html__('Style 2'); ?></option>
        </select>
    </td>
    <td>
        <input
            type="text"
            name="admin_popup_options[button_text]"
            id="admin_popup_button_text"
            class="regular-text"
            placeholder="Text to show in the button confirmation..."
            value="<?php echo isset( $admin_popup_options['button_text'] ) ? esc_html( $admin_popup_options['button_text'] ) : 'Ok'; ?>"
            >
    </td>
</table>