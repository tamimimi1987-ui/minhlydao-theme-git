<?php
/**
 * Trang chuyên mục Thánh Ngôn (Sám hối / Khuyến tu) — hiển thị dạng thẻ trích dẫn
 * giống site gốc (2 cột, khung vàng, dấu ngoặc kép) thay vì lưới thẻ bài viết mặc định.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$term = get_queried_object();
$name = ( $term && ! is_wp_error( $term ) ) ? $term->name : '';
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php echo esc_html( $name ); ?></h1></div></div>

<div class="section quotes">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<div class="quote-grid-2">
			<?php while ( have_posts() ) : the_post();
				$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
				if ( ! $src ) { $src = get_the_title(); }
			?>
			<div class="quote">
				<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 60, '…' ) ); ?></p>
				<cite>— <?php echo esc_html( $src ); ?></cite>
				<a class="more" href="<?php the_permalink(); ?>">Xem thêm →</a>
			</div>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php echo paginate_links(); // phpcs:ignore ?></div>
		<?php else : ?>
			<?php mld_empty_hint( 'Chưa có Thánh Ngôn trong chuyên mục này.' ); ?>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
