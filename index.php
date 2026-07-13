<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="page-head"><div class="wrap"><h1><?php
	if ( is_home() ) { echo 'Tin tức'; }
	else { echo wp_kses_post( get_the_archive_title() ); }
?></h1></div></div>

<div class="section news">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<div class="news-grid">
			<?php while ( have_posts() ) : the_post();
				if ( 'post' === get_post_type() ) {
					$cats = get_the_category();
				} elseif ( 'tin_tuc' === get_post_type() ) {
					$cats = get_the_terms( get_the_ID(), 'tin_tuc_cat' );
					$cats = ( $cats && ! is_wp_error( $cats ) ) ? array_values( $cats ) : array();
				} else {
					$cats = array();
				}
			?>
			<article class="post-card">
				<a class="thumb" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?></a>
				<div class="pbody">
					<?php if ( ! empty( $cats ) ) : ?><span class="tag"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo esc_html( mld_excerpt( 26 ) ); ?></p>
					<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
				</div>
			</article>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php echo paginate_links(); // phpcs:ignore ?></div>
		<?php else : ?>
		<p style="text-align:center;color:#777">Chưa có nội dung.</p>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
