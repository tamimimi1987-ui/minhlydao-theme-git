<?php
/**
 * Trang chuyên mục Giáo lý (Giáo lý / Người Minh Lý môn sanh / Minh Lý Yếu Giải / Lễ / Bài giảng).
 * Chuyên mục chỉ có 1 bài (vd. "Giáo lý") hiển thị thẳng toàn văn như site gốc,
 * chuyên mục nhiều bài hiển thị dạng lưới thẻ như trước.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$term = get_queried_object();
$name = ( $term && ! is_wp_error( $term ) ) ? $term->name : '';

$q = new WP_Query( array(
	'post_type'      => 'giao_ly',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
	'tax_query'      => array( array( 'taxonomy' => 'giao_ly_cat', 'field' => 'slug', 'terms' => $term ? $term->slug : '' ) ),
) );
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php echo esc_html( $name ); ?></h1></div></div>

<div class="section news">
	<div class="wrap">
		<?php if ( 1 === (int) $q->found_posts ) :
			// Chuyên mục chỉ có 1 bài: hiển thị toàn văn ngay tại đây, không dùng thẻ, giống site gốc.
			$q->the_post();
			?>
			<article class="single-content" style="max-width:900px;margin:0 auto">
				<?php the_content(); ?>
			</article>
			<?php wp_reset_postdata();
		elseif ( $q->have_posts() ) : ?>
		<div class="news-grid">
			<?php $mld_ico_url = get_template_directory_uri() . '/assets/images/banner/logo.png'; ?>
			<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<article class="post-card">
				<a class="thumb ico-thumb" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( $mld_ico_url ); ?>" alt="<?php the_title_attribute(); ?>">
					<?php endif; ?>
				</a>
				<div class="pbody">
					<span class="tag"><?php echo esc_html( $name ); ?></span>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo esc_html( mld_excerpt( 26 ) ); ?></p>
					<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
				</div>
			</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : ?>
			<?php mld_empty_hint( 'Chưa có bài trong chuyên mục này.' ); ?>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
