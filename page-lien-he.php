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

	<div class="contact-grid contact-grid-single">
		<div class="contact-form-col">
			<h3>Gửi yêu cầu</h3>
			<?php mld_contact_form(); ?>
		</div>
	</div>
</article>
</div>
<?php endwhile; get_footer(); ?>
