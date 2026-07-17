<?php
/**
 * Trang chủ "Kinh - sách" (slug: kinh-sach) — trang tổng hợp hiển thị
 * ảnh bìa Kinh và Sách, giống site gốc (2 khối: Kinh / Sách), thay vì
 * đưa thẳng vào lưu trữ CPT Kinh như trước. Chỉ hiện các bài có ảnh bìa
 * thật (bỏ ô chữ cái thay thế khi thiếu ảnh). Bổ sung khối Thánh Ngôn
 * ở cuối trang giống site gốc.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

/**
 * Trả về true nếu bài có ảnh bìa thật (meta _mld_cover_url hoặc Featured Image).
 */
function mld_ks_has_cover( $post_id ) {
	$cover = get_post_meta( $post_id, '_mld_cover_url', true );
	return $cover || has_post_thumbnail( $post_id );
}
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1>Kinh - sách</h1></div></div>

<div class="section lib">
	<div class="wrap">

		<h2 class="sec-title"><a href="<?php echo esc_url( get_post_type_archive_link( 'kinh' ) ); ?>">Kinh</a></h2>
		<?php
		$mld_ks_kinh = new WP_Query( array(
			'post_type'      => 'kinh',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'ASC',
		) );
		$mld_ks_kinh_ids = $mld_ks_kinh->have_posts() ? wp_list_pluck( $mld_ks_kinh->posts, 'ID' ) : array();
		$mld_ks_kinh_ids = array_filter( $mld_ks_kinh_ids, 'mld_ks_has_cover' );
		if ( $mld_ks_kinh_ids ) :
		?>
		<div class="kinh-grid">
			<?php foreach ( $mld_ks_kinh_ids as $mld_id ) : $cover = get_post_meta( $mld_id, '_mld_cover_url', true ); ?>
			<a href="<?php echo esc_url( get_permalink( $mld_id ) ); ?>" title="<?php echo esc_attr( get_the_title( $mld_id ) ); ?>">
				<?php if ( $cover ) : ?>
					<img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( get_the_title( $mld_id ) ); ?>" loading="lazy">
				<?php else : ?>
					<?php echo get_the_post_thumbnail( $mld_id, 'medium' ); ?>
				<?php endif; ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Kinh.' ); endif;
		wp_reset_postdata();
		?>

		<h2 class="sec-title" style="margin-top:40px"><a href="<?php echo esc_url( get_post_type_archive_link( 'sach' ) ); ?>">Sách</a></h2>
		<?php
		$mld_ks_sach = new WP_Query( array(
			'post_type'      => 'sach',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'ASC',
		) );
		$mld_ks_sach_ids = $mld_ks_sach->have_posts() ? wp_list_pluck( $mld_ks_sach->posts, 'ID' ) : array();
		$mld_ks_sach_ids = array_filter( $mld_ks_sach_ids, 'mld_ks_has_cover' );
		if ( $mld_ks_sach_ids ) :
		?>
		<div class="kinh-grid">
			<?php foreach ( $mld_ks_sach_ids as $mld_id ) : $cover = get_post_meta( $mld_id, '_mld_cover_url', true ); ?>
			<a href="<?php echo esc_url( get_permalink( $mld_id ) ); ?>" title="<?php echo esc_attr( get_the_title( $mld_id ) ); ?>">
				<?php if ( $cover ) : ?>
					<img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( get_the_title( $mld_id ) ); ?>" loading="lazy">
				<?php else : ?>
					<?php echo get_the_post_thumbnail( $mld_id, 'medium' ); ?>
				<?php endif; ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Sách.' ); endif;
		wp_reset_postdata();
		?>

	</div>
</div>

<div class="section quotes">
	<div class="wrap">
		<span class="eyebrow">Thư viện</span>
		<h2 class="sec-title">Thánh Ngôn</h2>
		<div class="divider"></div>
		<p class="sec-sub">Lời dạy thiêng liêng, làm kim chỉ nam cho tín đồ trên đường tu học và hành đạo.</p>
		<?php
		$mld_ks_tn = new WP_Query( array( 'post_type' => 'thanh_ngon', 'posts_per_page' => 4, 'orderby' => 'date', 'order' => 'DESC' ) );
		if ( $mld_ks_tn->have_posts() ) : ?>
		<div class="quote-grid-2">
			<?php while ( $mld_ks_tn->have_posts() ) : $mld_ks_tn->the_post();
				$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
				if ( ! $src ) { $src = get_the_title(); }
			?>
			<div class="quote">
				<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 55, '…' ) ); ?></p>
				<cite>— <?php echo esc_html( $src ); ?></cite>
				<a class="more" href="<?php the_permalink(); ?>">Xem thêm →</a>
			</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<a class="btn" style="margin-top:20px;display:inline-block" href="<?php echo esc_url( get_post_type_archive_link( 'thanh_ngon' ) ); ?>">Xem tất cả Thánh Ngôn →</a>
		<?php else : mld_empty_hint( 'Chưa có Thánh Ngôn.' ); endif; ?>
	</div>
</div>
<?php get_footer(); ?>
