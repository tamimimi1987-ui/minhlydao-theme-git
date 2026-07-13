<?php
/**
 * Trang chủ
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<!-- THƯ NGỎ -->
<section class="section intro">
	<div class="wrap">
		<span class="eyebrow">Thư ngỏ</span>
		<div class="divider"></div>
		<div style="height:38px"></div>
		<div class="intro-grid">
			<div>
				<h2><?php echo esc_html( get_theme_mod( 'mld_intro_title', 'MINH LÝ ĐẠO – TAM TÔNG MIẾU' ) ); ?></h2>
				<?php
				$default_body = "Chào mừng quý đạo hữu, đạo tâm đến với trang Web của MINH LÝ ĐẠO – TAM TÔNG MIẾU (gọi tắt là Minh Lý Đạo).\n\nMinh Lý Đạo là một mối Đạo được Thượng Đế khai sáng bằng linh điển tại Việt Nam để tất cả mọi người từ thiện tín đến môn sanh có thể theo đó tự học, tự tu để quay về với Chánh Pháp mà Thiêng-Liêng đã ban trao.\n\nWebsite minhlydao.org.vn là trang Web chính thống và duy nhất của Hội Thánh Minh Lý Đạo nhằm qua đó quý đạo hữu/đạo tâm trong và ngoài nước Việt Nam sẽ từng bước biết rõ về Kinh sách, giáo luật, giáo lý, . . . . . và các hoạt động “tốt đời – đẹp Đạo” của Minh Lý Đạo. Website hoạt động dựa trên Hiến chương của Minh Lý Đạo và Chính sách sử dụng website (đính kèm bên dưới).\n\nRất mong trang Web minhlydao.org.vn sẽ là một nhịp cầu giúp cho tất cả mọi người, quý đạo hữu/đạo tâm trên bước đường tu học để tự độ và giác tha.";
				$default_note = 'CHÚ Ý: Ban biên tập Website minhlydao.org.vn không công nhận và không chịu trách nhiệm mọi thông tin không xuất phát từ trang Web nầy. — BBT. Website';
				$body = get_theme_mod( 'mld_intro_body', $default_body );
				if ( $body ) {
					foreach ( preg_split( "/\n\s*\n/", $body ) as $para ) {
						echo '<p>' . wp_kses_post( trim( $para ) ) . '</p>';
					}
				}
				$note = get_theme_mod( 'mld_intro_note', $default_note );
				if ( $note ) { echo '<p class="note">' . wp_kses_post( $note ) . '</p>'; }
				$btn = get_theme_mod( 'mld_intro_btn' );
				if ( ! $btn ) {
					$mld_gioi_thieu = get_page_by_path( 'gioi-thieu' );
					$btn = $mld_gioi_thieu ? get_permalink( $mld_gioi_thieu ) : '';
				}
				if ( $btn ) { echo '<div style="margin-top:22px"><a class="btn" href="' . esc_url( $btn ) . '">Xem thêm</a></div>'; }
				?>
			</div>
			<figure>
				<img src="<?php echo esc_url( get_theme_mod( 'mld_intro_img', 'https://minhlydao.org.vn/img_data/images/about.jpg' ) ); ?>" alt="Thư ngỏ">
			</figure>
		</div>
	</div>
</section>

<!-- LỊCH SINH HOẠT -->
<section class="section section-activity">
	<div class="wrap">
		<span class="eyebrow">Hoạt động</span>
		<h2 class="sec-title">Lịch Sinh Hoạt</h2>
		<div class="divider"></div>
		<p class="sec-sub">Lịch các hoạt động và nghi lễ quan trọng trong năm.</p>
		<?php
		$ev = new WP_Query( array( 'post_type' => 'su_kien', 'posts_per_page' => 3, 'meta_key' => '_mld_event_date', 'orderby' => 'meta_value', 'order' => 'DESC' ) );
		if ( $ev->have_posts() ) : ?>
		<div class="cal-list">
			<?php while ( $ev->have_posts() ) : $ev->the_post();
				$date = get_post_meta( get_the_ID(), '_mld_event_date', true );
				$time = get_post_meta( get_the_ID(), '_mld_event_time', true );
				$ts   = $date ? strtotime( $date ) : get_the_time( 'U' );
			?>
			<div class="cal-item">
				<div class="cal-date">
					<div class="d"><?php echo esc_html( date_i18n( 'd', $ts ) ); ?></div>
					<div class="m"><?php echo esc_html( date_i18n( 'M Y', $ts ) ); ?></div>
					<?php if ( $time ) : ?><div class="t"><?php echo esc_html( $time ); ?></div><?php endif; ?>
				</div>
				<div class="cal-body">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo esc_html( mld_excerpt( 30 ) ); ?></p>
				</div>
			</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có sự kiện. Thêm trong Bảng điều khiển → Lịch sinh hoạt → Thêm mới.' ); endif; ?>
	</div>
</section>

<!-- KINH -->
<section class="section lib">
	<div class="wrap">
		<span class="eyebrow">Thư viện</span>
		<h2 class="sec-title">Kinh</h2>
		<div class="divider"></div>
		<p class="sec-sub">Tập hợp các lời dạy, kinh điển và giáo pháp của Hội Thánh, giúp tín đồ tu dưỡng tâm linh và hành đạo.</p>
		<?php
		$kinh = new WP_Query( array( 'post_type' => 'kinh', 'posts_per_page' => 4 ) );
		if ( $kinh->have_posts() ) : ?>
		<div class="card-grid g2">
			<?php while ( $kinh->have_posts() ) : $kinh->the_post();
				$icon = get_post_meta( get_the_ID(), '_mld_icon', true );
			?>
			<a class="book" href="<?php the_permalink(); ?>">
				<div class="ico"><?php
					if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); }
					else { echo esc_html( $icon ? $icon : mb_substr( get_the_title(), 0, 1 ) ); }
				?></div>
				<h4><?php the_title(); ?></h4>
			</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Kinh. Thêm trong Bảng điều khiển → Kinh → Thêm mới.' ); endif; ?>
	</div>
</section>

<!-- THÁNH NGÔN -->
<section class="section quotes">
	<div class="wrap">
		<span class="eyebrow">Thư viện</span>
		<h2 class="sec-title">Thánh Ngôn</h2>
		<div class="divider"></div>
		<p class="sec-sub">Lời dạy thiêng liêng, làm kim chỉ nam cho tín đồ trên đường tu học và hành đạo.</p>
		<?php
		$tn = new WP_Query( array( 'post_type' => 'thanh_ngon', 'posts_per_page' => 3 ) );
		if ( $tn->have_posts() ) : ?>
		<div class="quote-grid">
			<?php while ( $tn->have_posts() ) : $tn->the_post();
				$src = get_post_meta( get_the_ID(), '_mld_quote_source', true );
			?>
			<div class="quote">
				<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 45, '…' ) ); ?></p>
				<?php if ( $src ) : ?><cite>— <?php echo esc_html( $src ); ?></cite><?php endif; ?>
				<a class="more" href="<?php the_permalink(); ?>">Xem thêm →</a>
			</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Thánh Ngôn. Thêm trong Bảng điều khiển → Thánh Ngôn → Thêm mới (nội dung là lời dạy, "Nguồn" nhập ở ô bên phải).' ); endif; ?>
	</div>
</section>

<!-- SÁCH -->
<section class="section lib">
	<div class="wrap">
		<span class="eyebrow">Thư viện</span>
		<h2 class="sec-title">Sách</h2>
		<div class="divider"></div>
		<p class="sec-sub">Những tác phẩm giáo lý và ghi chép đạo pháp, giúp tín đồ học hỏi, suy ngẫm và ứng dụng vào đời sống tu hành.</p>
		<?php
		$sach = new WP_Query( array( 'post_type' => 'sach', 'posts_per_page' => 8 ) );
		if ( $sach->have_posts() ) : ?>
		<div class="card-grid g4">
			<?php while ( $sach->have_posts() ) : $sach->the_post();
				$icon = get_post_meta( get_the_ID(), '_mld_icon', true );
			?>
			<a class="book" href="<?php the_permalink(); ?>">
				<div class="ico"><?php
					if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); }
					else { echo esc_html( $icon ? $icon : mb_substr( get_the_title(), 0, 1 ) ); }
				?></div>
				<h4><?php the_title(); ?></h4>
			</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Sách. Thêm trong Bảng điều khiển → Sách → Thêm mới.' ); endif; ?>
	</div>
</section>

<!-- MEDIA -->
<section class="section media">
	<div class="wrap">
		<span class="eyebrow">Thư viện</span>
		<h2 class="sec-title">Audio &amp; Video</h2>
		<div class="divider"></div>
		<p class="sec-sub">Kho tư liệu âm thanh và hình ảnh, truyền tải giáo lý và sinh hoạt của Hội Thánh.</p>
		<?php
		$media = new WP_Query( array( 'post_type' => 'media_tt', 'posts_per_page' => 1 ) );
		if ( $media->have_posts() ) : $media->the_post();
			mld_youtube_box( get_post_meta( get_the_ID(), '_mld_video_url', true ), get_the_title() );
			wp_reset_postdata();
		else : mld_empty_hint( 'Chưa có Media. Thêm trong Bảng điều khiển → Media → Thêm mới, rồi dán link YouTube ở ô bên phải.' ); endif; ?>
	</div>
</section>

<!-- TIN NỔI BẬT -->
<section class="section news">
	<div class="wrap">
		<span class="eyebrow">Tin tức đạo</span>
		<h2 class="sec-title">Tin Nổi Bật</h2>
		<div class="divider"></div>
		<p class="sec-sub">Các hoạt động đáng chú ý của Hội Thánh.</p>
		<?php
		$news = new WP_Query( array( 'post_type' => 'tin_tuc', 'posts_per_page' => 3 ) );
		if ( $news->have_posts() ) : ?>
		<div class="news-grid">
			<?php while ( $news->have_posts() ) : $news->the_post();
				$cats = get_the_terms( get_the_ID(), 'tin_tuc_cat' );
				$cats = ( $cats && ! is_wp_error( $cats ) ) ? array_values( $cats ) : array();
			?>
			<article class="post-card">
				<a class="thumb" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>
				</a>
				<div class="pbody">
					<?php if ( ! empty( $cats ) ) : ?><span class="tag"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo esc_html( mld_excerpt( 26 ) ); ?></p>
					<a class="more" href="<?php the_permalink(); ?>">Xem chi tiết →</a>
				</div>
			</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : mld_empty_hint( 'Chưa có Tin tức. Thêm trong Bảng điều khiển → Tin tức → Thêm mới.' ); endif; ?>
	</div>
</section>

<?php get_footer(); ?>
