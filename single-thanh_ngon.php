<?php
/**
 * Bài Thánh Ngôn — giống single.php nhưng có thêm cột "Tin mới nhất"
 * bên phải (đúng như site gốc hiển thị trên mọi trang nội dung).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>

<div class="content-with-sidebar wrap">
	<article class="content-area">
		<?php if ( has_post_thumbnail() ) : ?>
			<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
		<?php endif; ?>

		<div class="entry-content"><?php the_content(); ?></div>

		<?php if ( $src ) : ?>
			<p style="margin-top:24px;font-weight:600;color:var(--maroon)">— <?php echo esc_html( $src ); ?></p>
		<?php endif; ?>

		<p style="margin-top:40px"><a class="btn" href="<?php echo esc_url( get_post_type_archive_link( 'thanh_ngon' ) ); ?>" onclick="var r=document.referrer;if(r&&r.indexOf(window.location.host)!==-1&&r.indexOf(window.location.href)===-1){window.location.href=r;return false;}">← Về Thánh Ngôn</a></p>
	</article>

	<aside class="side-news">
		<h3>Tin mới nhất</h3>
		<?php
		$mld_news = new WP_Query( array( 'post_type' => 'tin_tuc', 'posts_per_page' => 4 ) );
		if ( $mld_news->have_posts() ) :
		?>
		<ul>
			<?php while ( $mld_news->have_posts() ) : $mld_news->the_post(); ?>
			<li>
				<a class="thumb" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); } ?></a>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>
		<?php else : ?>
		<p style="padding:14px 18px;font-size:13.5px;color:var(--muted)">Chưa có tin tức.</p>
		<?php endif; ?>
	</aside>
</div>
<?php endwhile; get_footer(); ?>
