<?php

global $post;
$priority_meta = get_post_meta( $post->ID, 'admin_popup_priority', true );

?>
<table class="form-table">
    <input type="hidden" name="admin_popup_priority_nonce" value="<?php echo wp_create_nonce( 'admin_popup_priority_nonce' ); ?>">
    <tr>
        <td>
            <select
                name="admin_popup_priority"
                id="admin_popup_priority"
                class="regular-text"
            >
                <option value="info" <?php isset( $priority_meta ) ? selected( $priority_meta, 'info' ) : ''; ?>><?php echo esc_html__( 'Info' ); ?></option>
                <option value="warning" <?php isset( $priority_meta ) ? selected( $priority_meta, 'warning' ) : ''; ?>><?php echo esc_html__( 'Warning' ); ?></option>
                <option value="danger" <?php isset( $priority_meta ) ? selected( $priority_meta, 'danger' ) : ''; ?>><?php echo esc_html__( 'Danger' ); ?></option>
                <option value="success" <?php isset( $priority_meta ) ? selected( $priority_meta, 'success' ) : ''; ?>><?php echo esc_html__( 'Success' ); ?></option>
            </select>
        </td>
    </tr>
</table>