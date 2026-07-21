<?php
/**
 * Trang danh sách Tu tịnh — lưới 3 cột phẳng, icon Tam Tài, tag "Tu tịnh",
 * giống cấu trúc trang "Tu tịnh" trên site gốc (minhlydao.org.vn/vi/tu-tinh.html).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$q = new WP_Query( array(
	'post_type'      => 'tu_tinh',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
) );

$mld_ico_url = get_template_directory_uri() . '/assets/images/banner/logo.png';
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1>Tu tịnh</h1></div></div>

<div class="section news tu-tinh-archive">
	<div class="wrap">
		<?php if ( $q->have_posts() ) : ?>
		<div class="news-grid" style="margin:10px 0 20px">
			<?php while ( $q->have_posts() ) : $q->the_post();
				$excerpt = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( get_the_content() ), 18, '…' );
			?>
			<article class="post-card">
				<a class="thumb ico-thumb" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( $mld_ico_url ); ?>" alt="<?php the_title_attribute(); ?>">
					<?php endif; ?>
				</a>
				<div class="pbody">
					<span class="tag">Tu tịnh</span>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( $excerpt ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
					<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
				</div>
			</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có bài Tu tịnh.' ); endif; ?>
	</div>
</div>
<?php get_footer(); ?>
