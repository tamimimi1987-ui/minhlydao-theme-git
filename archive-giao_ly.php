<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$order = mld_giao_ly_cat_order();
$terms = get_terms( array( 'taxonomy' => 'giao_ly_cat', 'hide_empty' => false ) );
$by_slug = array();
if ( $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $t ) { $by_slug[ $t->slug ] = $t; }
}
// Giữ đúng thứ tự chuyên mục như menu con trên site gốc; chuyên mục lạ (nếu có) xếp cuối.
$ordered_slugs = $order;
foreach ( array_keys( $by_slug ) as $slug ) {
	if ( ! in_array( $slug, $ordered_slugs, true ) ) { $ordered_slugs[] = $slug; }
}

$assigned_ids = array();
?>
<div class="page-head"><div class="wrap"><h1>Giáo lý</h1></div></div>

<div class="section news">
	<div class="wrap">
		<?php
		$printed_any = false;
		foreach ( $ordered_slugs as $slug ) :
			if ( ! isset( $by_slug[ $slug ] ) ) { continue; }
			$term = $by_slug[ $slug ];
			$q = new WP_Query( array(
				'post_type'      => 'giao_ly',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'tax_query'      => array( array( 'taxonomy' => 'giao_ly_cat', 'field' => 'slug', 'terms' => $slug ) ),
			) );
			if ( ! $q->have_posts() ) { continue; }
			$printed_any = true;
			?>
			<h2 class="sec-title" style="margin-top:<?php echo $printed_any ? '10px' : '0'; ?>"><?php echo esc_html( $term->name ); ?></h2>
			<div class="divider"></div>
			<div class="news-grid" style="margin:30px 0 50px">
				<?php while ( $q->have_posts() ) : $q->the_post(); $assigned_ids[] = get_the_ID(); ?>
				<article class="post-card">
					<a class="thumb" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?></a>
					<div class="pbody">
						<span class="tag"><?php echo esc_html( $term->name ); ?></span>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( mld_excerpt( 26 ) ); ?></p>
						<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
					</div>
				</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php
		endforeach;

		// Bài chưa gắn chuyên mục (dự phòng, để không "mất" bài nào nếu quên gán).
		$leftover = new WP_Query( array(
			'post_type'      => 'giao_ly',
			'posts_per_page' => -1,
			'post__not_in'   => $assigned_ids ? $assigned_ids : array( 0 ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		if ( $leftover->have_posts() ) :
			$printed_any = true;
			?>
			<h2 class="sec-title">Khác</h2>
			<div class="divider"></div>
			<div class="news-grid" style="margin:30px 0 50px">
				<?php while ( $leftover->have_posts() ) : $leftover->the_post(); ?>
				<article class="post-card">
					<a class="thumb" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?></a>
					<div class="pbody">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( mld_excerpt( 26 ) ); ?></p>
						<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
					</div>
				</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php
		endif;

		if ( ! $printed_any ) : mld_empty_hint( 'Chưa có bài Giáo lý.' ); endif;
		?>
	</div>
</div>
<?php get_footer(); ?>
