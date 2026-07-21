<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
$mld_page_content = apply_filters( 'the_content', get_the_content() );
$mld_toc_html = '';
if ( preg_match( '#^\s*<p class="mld-toc-strip">.*?</p>#s', $mld_page_content, $mld_toc_m ) ) {
	$mld_toc_html = $mld_toc_m[0];
	$mld_page_content = substr( $mld_page_content, strlen( $mld_toc_m[0] ) );
}
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><?php echo $mld_toc_html; ?><h1><?php the_title(); ?></h1></div></div>
<?php if ( $mld_toc_html ) : ?>
<script>
(function(){
	var toc = document.querySelector(".page-head .mld-toc-strip");
	if(!toc) return;
	var spacer = document.createElement("div");
	spacer.className = "mld-toc-strip-spacer";
	toc.parentNode.insertBefore(spacer, toc);
	var natural = null, stuck = false;
	function headerH(){
		var v = parseFloat(getComputedStyle(document.documentElement).getPropertyValue("--mld-header-h"));
		return isNaN(v) ? 55 : v;
	}
	function measure(){
		var wasStuck = stuck;
		if(wasStuck){ toc.classList.remove("mld-toc-stuck"); toc.style.left=""; toc.style.width=""; spacer.style.display="none"; }
		var r = toc.getBoundingClientRect();
		natural = { top: r.top + window.scrollY, left: r.left, width: r.width, height: r.height };
		if(wasStuck) applyStuck();
	}
	function applyStuck(){
		toc.classList.add("mld-toc-stuck");
		toc.style.left = natural.left + "px";
		toc.style.width = natural.width + "px";
		spacer.style.display = "block";
		spacer.style.height = natural.height + "px";
	}
	function releaseStuck(){
		toc.classList.remove("mld-toc-stuck");
		toc.style.left = "";
		toc.style.width = "";
		spacer.style.display = "none";
	}
	function onScroll(){
		var thresholdY = natural.top - headerH();
		if(window.scrollY >= thresholdY && !stuck){ stuck = true; applyStuck(); }
		else if(window.scrollY < thresholdY && stuck){ stuck = false; releaseStuck(); }
	}
	measure();
	onScroll();
	window.addEventListener("scroll", onScroll, {passive:true});
	window.addEventListener("resize", function(){ measure(); onScroll(); });
})();
</script>
<?php endif; ?>
<article class="content-area">
	<?php if ( has_post_thumbnail() ) : ?>
		<div style="margin-bottom:30px"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:10px' ) ); ?></div>
	<?php endif; ?>
	<div class="entry-content"><?php echo $mld_page_content; ?></div>
</article>
<?php endwhile; get_footer(); ?>
