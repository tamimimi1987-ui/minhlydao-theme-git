<?php
/**
 * Bài Lịch sinh hoạt (su_kien) — giống single.php nhưng có thêm cột "Lịch sinh hoạt mới nhất"
 * bên phải và khối "Lịch sinh hoạt liên quan" bên dưới nội dung, đúng như trang Tin tức.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$event_date = get_post_meta( get_the_ID(), '_mld_event_date', true );
	$event_time = get_post_meta( get_the_ID(), '_mld_event_time', true );
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>

<div class="content-with-sidebar wrap">
	<article class="content-area">
		<?php if ( has_post_thumbnail() ) : ?>
			<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
		<?php endif; ?>

		<?php if ( $event_date ) : ?>
			<p style="font-weight:600;color:var(--maroon);margin-bottom:20px">
				<?php echo esc_html( mysql2date( 'd/m/Y', $event_date ) ); ?><?php if ( $event_time ) { echo ' · ' . esc_html( $event_time ); } ?>
			</p>
		<?php endif; ?>

		<div class="entry-content"><?php the_content(); ?></div>

		<?php
		$mld_related = new WP_Query( array(
			'post_type'      => 'su_kien',
			'posts_per_page' => 5,
			'post__not_in'   => array( get_the_ID() ),
		) );
		if ( $mld_related->have_posts() ) :
		?>
		<div class="related-news">
			<h4>Lịch sinh hoạt liên quan</h4>
			<ul>
				<?php while ( $mld_related->have_posts() ) : $mld_related->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>
		<?php endif; ?>
	</article>

	<aside class="side-news">
		<h3>Lịch sinh hoạt mới nhất</h3>
		<?php
		$mld_news = new WP_Query( array(
			'post_type'      => 'su_kien',
			'posts_per_page' => 6,
			'post__not_in'   => array( get_the_ID() ),
		) );
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
		<p style="padding:14px 18px;font-size:13.5px;color:var(--muted)">Chưa có lịch sinh hoạt.</p>
		<?php endif; ?>
	</aside>
</div>
<?php endwhile; get_footer(); ?>
