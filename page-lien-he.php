<?php
/**
 * Template riêng cho trang "Liên hệ" (slug: lien-he) — có bản đồ (tùy chọn) và form gửi yêu cầu.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>
<div class="section single-section">
<article class="content-area">
	<div class="entry-content"><?php the_content(); ?></div>

	<div class="contact-grid">
		<div class="contact-info">
			<h3>Thông tin liên hệ</h3>
			<p><b>Địa chỉ:</b> <?php echo esc_html( get_theme_mod( 'mld_addr', 'Số 82, Đường Cao Thắng, Phường Bàn Cờ, TP.HCM.' ) ); ?></p>
			<p><b>Điện thoại:</b> <?php echo esc_html( get_theme_mod( 'mld_phone', '(84) (28) 3835 8181' ) ); ?></p>
			<p><b>Email:</b> <?php echo esc_html( get_theme_mod( 'mld_email', 'tamtongmieu1924@gmail.com' ) ); ?></p>
		</div>
		<div class="contact-form-col">
			<h3>Gửi yêu cầu</h3>
			<?php mld_contact_form(); ?>
		</div>
	</div>
</article>
</div>
<?php endwhile; get_footer(); ?>
