<?php
/**
 * Lưu trữ Kinh — chỉ hiển thị ảnh bìa (giống site gốc: lưới ảnh bìa
 * đơn giản, không mô tả, không khung thẻ).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1>Kinh</h1></div></div>

<div class="section lib">
	<div class="wrap">
		<?php
		$mld_kinh_q = new WP_Query( array(
			'post_type'      => 'kinh',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'ASC',
		) );
		if ( $mld_kinh_q->have_posts() ) :
		?>
		<div class="kinh-grid">
			<?php while ( $mld_kinh_q->have_posts() ) : $mld_kinh_q->the_post();
				$cover = get_post_meta( get_the_ID(), '_mld_cover_url', true );
			?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php if ( $cover ) : ?>
					<img src="<?php echo esc_url( $cover ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
				<?php elseif ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium' ); ?>
				<?php else : ?>
					<div class="ico"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></div>
				<?php endif; ?>
			</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Kinh. Thêm trong Bảng điều khiển → Kinh → Thêm mới.' ); endif; ?>
	</div>
</div>
<?php get_footer(); ?>
