<?php
/**
 * Trang danh sách Giáo lý — lưới 3 cột phẳng (không chia theo chuyên mục,
 * không có tiêu đề nhóm), icon Tam Tài, tiêu đề/mô tả in hoa, giống hệt
 * cấu trúc trang "Giáo lý" trên site gốc (minhlydao.org.vn/vi/giao-ly.html).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$order   = mld_giao_ly_cat_order();
$terms   = get_terms( array( 'taxonomy' => 'giao_ly_cat', 'hide_empty' => false ) );
$by_slug = array();
if ( $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $t ) { $by_slug[ $t->slug ] = $t; }
}
$ordered_slugs = $order;
foreach ( array_keys( $by_slug ) as $slug ) {
	if ( ! in_array( $slug, $ordered_slugs, true ) ) { $ordered_slugs[] = $slug; }
}

// Gom tất cả bài Giáo lý thành một danh sách phẳng duy nhất, giữ thứ tự
// theo chuyên mục (giống thứ tự menu con trên site gốc), mỗi bài chỉ xuất hiện 1 lần.
$all_posts   = array();
$seen_ids    = array();
foreach ( $ordered_slugs as $slug ) {
	if ( ! isset( $by_slug[ $slug ] ) ) { continue; }
	$term = $by_slug[ $slug ];
	$q = new WP_Query( array(
		'post_type'      => 'giao_ly',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'tax_query'      => array( array( 'taxonomy' => 'giao_ly_cat', 'field' => 'slug', 'terms' => $slug ) ),
	) );
	foreach ( $q->posts as $p ) {
		if ( isset( $seen_ids[ $p->ID ] ) ) { continue; }
		$seen_ids[ $p->ID ] = true;
		$all_posts[] = array( 'post' => $p, 'term' => $term );
	}
}
// Bài chưa gắn chuyên mục (dự phòng, để không "mất" bài nào).
$leftover = new WP_Query( array(
	'post_type'      => 'giao_ly',
	'posts_per_page' => -1,
	'post__not_in'   => $seen_ids ? array_keys( $seen_ids ) : array( 0 ),
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
foreach ( $leftover->posts as $p ) {
	$all_posts[] = array( 'post' => $p, 'term' => null );
}

$mld_ico_url = get_template_directory_uri() . '/assets/images/banner/logo.png';
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1>Giáo lý</h1></div></div>

<div class="section news giao-ly-archive">
	<div class="wrap">
		<?php if ( $all_posts ) : ?>
		<div class="news-grid" style="margin:10px 0 20px">
			<?php foreach ( $all_posts as $row ) :
				$p    = $row['post'];
				$term = $row['term'];
				$excerpt = has_excerpt( $p->ID ) ? wp_strip_all_tags( get_the_excerpt( $p ) ) : wp_trim_words( wp_strip_all_tags( $p->post_content ), 18, '…' );
			?>
			<article class="post-card">
				<a class="thumb ico-thumb" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
					<?php if ( has_post_thumbnail( $p->ID ) ) : ?>
						<?php echo get_the_post_thumbnail( $p->ID, 'medium' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( $mld_ico_url ); ?>" alt="<?php echo esc_attr( get_the_title( $p ) ); ?>">
					<?php endif; ?>
				</a>
				<div class="pbody">
					<?php if ( $term ) : ?><span class="tag"><?php echo esc_html( $term->name ); ?></span><?php endif; ?>
					<h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
					<?php if ( $excerpt ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
					<a class="more" href="<?php echo esc_url( get_permalink( $p ) ); ?>">Xem chi tiết →</a>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có bài Giáo lý.' ); endif; ?>
	</div>
</div>
<?php get_footer(); ?>
