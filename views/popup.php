<?php
$popup_background = wp_get_attachment_image_src( get_post_meta( $popup_id, 'admin_popup_background', true ), 'full' );
?>
<div class="admin_popup" style="">
    <div class="admin_popup_overlay"></div>
    <div class="admin_popup_wrap">
        <div class="admin_popup_background"></div>
        <section class="admin_popup_info">
            <h1 class="admin_popup_title">
                <?php
                echo esc_html( get_the_title( $popup_id ) );
                ?>
            </h1>

            <div class="admin_popup_content">
                <?php
                echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $popup_id ) ) );
                ?>
            </div>

            <?php //thumbnail_by_id(); ?>

            <button class="admin_popup_button">
                <?php
                echo esc_html( $options['button_text'] );
                ?>
            </button>

        </section>

        <section class="admin_popup_image">
            <img src="<?php echo get_the_post_thumbnail_url( $popup_id, 'full' ); ?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" loading="lazy">
        </section>
    </div>
</div>

<style>
.admin_popup {
    z-index: 9991;
    background: hsla( 0, 0%, 0%, .7 );
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    display:flex;
    justify-content:center;
    align-items:center;
}

.admin_popup_overlay {
    z-index: 9992;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
}

.admin_popup_wrap {
    z-index: 9993;
    position: relative;
    background: #f0f0f1;
    display: grid;
    gap: 2rem;
    padding: 4rem;
    max-width: 1080px;
    min-height: 650px;
}

.admin_popup_background {
    position: absolute;
    z-index: -1;
    background: #1d2327;
    background-image: url( <?php echo $popup_background[0]; ?> );
    background-repeat: no-repeat;
    background-size: cover;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: auto;
    opacity: .6;
}

.admin_popup_info {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    background: hsla( 0, 255%, 255%, .6 );
    padding: 2rem;

}

.admin_popup_title {
    font-size: 3rem;
    padding: 1rem;
    width: 100%;
}

.admin_popup_content p {
    font-size: 1.10rem;
}

.admin_popup_button {
    font-size: 1.5rem;
    padding: .7rem;
    border-radius: 50px;
    background: #1d2327;
    color: #fff;
    max-width: 400px;
    cursor: pointer;
}

.admin_popup_button:hover {
    background: #2271b1;
}

.admin_popup_image {
    display: flex;
    align-items: center;
    grid-column: 2;
}

.admin_popup_image img {
    max-width: 100%;
    height: auto;
    border-radius: 50%;
}
</style>

<script>
function close_popup( element ) {
    const popup = document.querySelector('.admin_popup');

    element.addEventListener('click', e => {
        //popup.style = 'display:none';
        popup.remove();
    });
}

const popup = document.querySelector('.admin_popup');
const popup_overlay = document.querySelector('.admin_popup_overlay');
const close_button = popup.querySelector('.admin_popup .admin_popup_button');
close_popup( close_button );
close_popup( popup_overlay );
</script>