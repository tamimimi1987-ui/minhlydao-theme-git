<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
	$video_url = get_post_meta( get_the_ID(), '_mld_video_url', true );
	$has_video = (bool) mld_youtube_id( $video_url );

	$mld_pt_obj  = get_post_type_object( get_post_type() );
	$mld_archive = ( $mld_pt_obj && $mld_pt_obj->has_archive ) ? get_post_type_archive_link( get_post_type() ) : false;
	if ( $mld_archive ) {
		$back_url   = $mld_archive;
		$back_label = '← Về trang trước';
	} else {
		$back_url   = home_url( '/' );
		$back_label = '← Về trang chủ';
	}
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>
<div class="section single-section">
<article class="content-area">
	<?php if ( has_post_thumbnail() && ! $has_video ) : ?>
		<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
	<?php endif; ?>

	<?php if ( $has_video ) : mld_youtube_box( $video_url, get_the_title(), 'margin-bottom:30px' ); endif; ?>

	<div class="entry-content"><?php the_content(); ?></div>

	<?php if ( $src ) : ?>
		<p style="margin-top:24px;font-weight:600;color:var(--maroon)">— <?php echo esc_html( $src ); ?></p>
	<?php endif; ?>

	<p style="margin-top:40px"><a class="btn" href="<?php echo esc_url( $back_url ); ?>" onclick="var r=document.referrer;if(r&&r.indexOf(window.location.host)!==-1&&r.indexOf(window.location.href)===-1){window.location.href=r;return false;}"><?php echo esc_html( $back_label ); ?></a></p>
</article>
</div>
<?php endwhile; get_footer(); ?>
