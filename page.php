<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>
<article class="content-area">
	<?php if ( has_post_thumbnail() ) : ?>
		<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
	<?php endif; ?>
	<div class="entry-content"><?php the_content(); ?></div>
</article>
<?php endwhile; get_footer(); ?>
