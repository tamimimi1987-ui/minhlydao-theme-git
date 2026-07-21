<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
$mld_page_content = apply_filters( 'the_content', get_the_content() );
$mld_toc_html = '';
if ( preg_match( '#^\s*<p class="mld-toc-strip">.*?</p>#s', $mld_page_content, $mld_toc_m ) ) {
	$mld_toc_html = $mld_toc_m[0];
	$mld_page_content = substr( $mld_page_content, strlen( $mld_toc_m[0] ) );
}
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><?php echo $mld_toc_html; ?><h1><?php the_title(); ?></h1></div></div>
<article class="content-area">
	<?php if ( has_post_thumbnail() ) : ?>
		<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
	<?php endif; ?>
	<div class="entry-content"><?php echo $mld_page_content; ?></div>
</article>
<?php endwhile; get_footer(); ?>
