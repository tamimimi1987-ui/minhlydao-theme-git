<?php
/**
 * Lưu trữ Thánh Ngôn — nhóm theo chuyên mục (Sám hối / Khuyến tu),
 * hiển thị dạng thẻ trích dẫn giống site gốc thay vì lưới ảnh mặc định.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$order   = mld_thanh_ngon_cat_order();
$terms   = get_terms( array( 'taxonomy' => 'thanh_ngon_cat', 'hide_empty' => false ) );
$by_slug = array();
if ( $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $t ) { $by_slug[ $t->slug ] = $t; }
}
$ordered_slugs = array_keys( $order );
foreach ( array_keys( $by_slug ) as $slug ) {
	if ( ! in_array( $slug, $ordered_slugs, true ) ) { $ordered_slugs[] = $slug; }
}

$assigned_ids = array();
$printed_any  = false;
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1>Thánh Ngôn</h1></div></div>

<div class="section quotes">
	<div class="wrap">
		<?php
		foreach ( $ordered_slugs as $slug ) :
			if ( ! isset( $by_slug[ $slug ] ) ) { continue; }
			$term  = $by_slug[ $slug ];
			$label = isset( $order[ $slug ] ) ? $order[ $slug ] : $term->name;
			$q     = new WP_Query( array(
				'post_type'      => 'thanh_ngon',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tax_query'      => array( array( 'taxonomy' => 'thanh_ngon_cat', 'field' => 'slug', 'terms' => $slug ) ),
			) );
			if ( ! $q->have_posts() ) { continue; }
			$printed_any = true;
			?>
			<div class="quote-cat-block">
				<h2 class="quote-cat-title"><?php echo esc_html( $label ); ?></h2>
				<div class="quote-grid-2">
					<?php while ( $q->have_posts() ) : $q->the_post(); $assigned_ids[] = get_the_ID();
						$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
						if ( ! $src ) { $src = get_the_title(); }
					?>
					<div class="quote">
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 60, '…' ) ); ?></p>
						<cite>— <?php echo esc_html( $src ); ?></cite>
						<a class="more" href="<?php the_permalink(); ?>">Xem thêm →</a>
					</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
			<?php
		endforeach;

		// Bài chưa gắn chuyên mục (dự phòng, để không "mất" bài nào nếu quên gán).
		$leftover = new WP_Query( array(
			'post_type'      => 'thanh_ngon',
			'posts_per_page' => -1,
			'post__not_in'   => $assigned_ids ? $assigned_ids : array( 0 ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		if ( $leftover->have_posts() ) :
			$printed_any = true;
			?>
			<div class="quote-cat-block">
				<h2 class="quote-cat-title">Khác</h2>
				<div class="quote-grid-2">
					<?php while ( $leftover->have_posts() ) : $leftover->the_post();
						$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
						if ( ! $src ) { $src = get_the_title(); }
					?>
					<div class="quote">
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 60, '…' ) ); ?></p>
						<cite>— <?php echo esc_html( $src ); ?></cite>
						<a class="more" href="<?php the_permalink(); ?>">Xem thêm →</a>
					</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
			<?php
		endif;

		if ( ! $printed_any ) : mld_empty_hint( 'Chưa có Thánh Ngôn. Thêm trong Bảng điều khiển → Thánh Ngôn → Thêm mới.' ); endif;
		?>
	</div>
</div>
<?php get_footer(); ?>
