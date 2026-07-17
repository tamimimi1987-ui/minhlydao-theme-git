<?php
/**
 * Minh Lý Đạo - Tam Tông Miếu — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MLD_VER', '2.94.0' );

/* ------------------------------------------------------------------
 * 1. Thiết lập theme
 * ------------------------------------------------------------------ */
function mld_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 120, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array(
		'primary' => __( 'Menu chính (đầu trang)', 'minhlydao' ),
		'footer'  => __( 'Menu chân trang (Liên kết)', 'minhlydao' ),
	) );
}
add_action( 'after_setup_theme', 'mld_setup' );

/* ------------------------------------------------------------------
 * 2. Nạp CSS / JS
 * ------------------------------------------------------------------ */
function mld_assets() {
	// Font chinh cua site la Arial (font he thong, khong can nap ngoai) -- chi con nap
	// rieng Dancing Script (co ho tro dau tieng Viet) cho cac tieu de kieu thu phap.
	wp_enqueue_style( 'mld-fonts', 'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap', array(), null );
	wp_enqueue_style( 'mld-style', get_stylesheet_uri(), array(), MLD_VER );
	wp_enqueue_script( 'mld-main', get_template_directory_uri() . '/assets/main.js', array(), MLD_VER, true );
	if ( is_page( 'lich-tam-tong-mieu' ) ) {
		wp_enqueue_script( 'mld-lich', get_template_directory_uri() . '/assets/lich.js', array(), MLD_VER, true );
	}
}
add_action( 'wp_enqueue_scripts', 'mld_assets' );

/* ------------------------------------------------------------------
 * 3. Các loại nội dung tùy chỉnh (Custom Post Types)
 * ------------------------------------------------------------------ */
function mld_register_cpts() {

	$cpts = array(
		'su_kien' => array(
			'name'     => 'Lịch sinh hoạt',
			'singular' => 'Sự kiện',
			'icon'     => 'dashicons-calendar-alt',
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'slug'     => 'su-kien',
		),
		'kinh' => array(
			'name'     => 'Kinh',
			'singular' => 'Kinh',
			'icon'     => 'dashicons-book-alt',
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'slug'     => 'kinh',
		),
		'sach' => array(
			'name'     => 'Sách',
			'singular' => 'Sách',
			'icon'     => 'dashicons-book',
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'slug'     => 'sach',
		),
		'thanh_ngon' => array(
			'name'     => 'Thánh Ngôn',
			'singular' => 'Thánh Ngôn',
			'icon'     => 'dashicons-format-quote',
			'supports' => array( 'title', 'editor' ),
			'slug'     => 'thanh-ngon',
		),
		'media_tt' => array(
			'name'     => 'Media (Audio/Video)',
			'singular' => 'Media',
			'icon'     => 'dashicons-format-video',
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'slug'     => 'media',
		),
		'giao_ly' => array(
			'name'     => 'Giáo lý',
			'singular' => 'Bài giáo lý',
			'icon'     => 'dashicons-welcome-learn-more',
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'slug'     => 'giao-ly',
		),
		'tu_tinh' => array(
			'name'     => 'Tu tịnh',
			'singular' => 'Bài Tu tịnh',
			'icon'     => 'dashicons-lightbulb',
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'slug'     => 'tu-tinh',
		),
		'tin_tuc' => array(
			'name'     => 'Tin tức',
			'singular' => 'Bài Tin tức',
			'icon'     => 'dashicons-megaphone',
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'slug'     => 'tin-tuc',
		),
	);

	foreach ( $cpts as $key => $c ) {
		register_post_type( $key, array(
			'labels' => array(
				'name'          => $c['name'],
				'singular_name' => $c['singular'],
				'add_new'       => 'Thêm mới',
				'add_new_item'  => 'Thêm ' . $c['singular'],
				'edit_item'     => 'Sửa ' . $c['singular'],
				'all_items'     => $c['name'],
				'menu_name'     => $c['name'],
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => $c['icon'],
			'supports'     => $c['supports'],
			'rewrite'      => array( 'slug' => $c['slug'] ),
			'show_in_rest' => true,
		) );
	}
}
add_action( 'init', 'mld_register_cpts' );

function mld_register_tin_tuc_taxonomy() {
	register_taxonomy( 'tin_tuc_cat', 'tin_tuc', array(
		'label'        => 'Chuyên mục Tin tức',
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'tin-tuc-chuyen-muc' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mld_register_tin_tuc_taxonomy' );

/** Chuyên mục Giáo lý: khớp với 5 mục con của trang Giáo lý trên site gốc
 *  (Giáo lý, Người Minh Lý môn sanh, Minh Lý Yếu Giải, Lễ, Bài giảng). */
function mld_register_giao_ly_taxonomy() {
	register_taxonomy( 'giao_ly_cat', 'giao_ly', array(
		'label'        => 'Chuyên mục Giáo lý',
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'giao-ly-chuyen-muc' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mld_register_giao_ly_taxonomy' );

/** Danh sách chuyên mục Giáo lý, đúng thứ tự như menu con trên site gốc. */
function mld_giao_ly_cat_order() {
	return array( 'giao-ly', 'nguoi-minh-ly-mon-sanh', 'minh-ly-yeu-giai', 'le', 'bai-giang' );
}

/** Tạo sẵn 5 term mặc định cho chuyên mục Giáo lý (chạy 1 lần khi kích hoạt/tải giao diện). */
function mld_seed_giao_ly_terms() {
	$terms = array(
		'giao-ly'                => 'Giáo lý',
		'nguoi-minh-ly-mon-sanh'  => 'Người Minh Lý môn sanh',
		'minh-ly-yeu-giai'        => 'Minh Lý Yếu Giải',
		'le'                      => 'Lễ',
		'bai-giang'               => 'Bài giảng',
	);
	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'giao_ly_cat' ) ) {
			wp_insert_term( $name, 'giao_ly_cat', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'mld_seed_giao_ly_terms', 20 );

/** Chuyên mục Thánh Ngôn: khớp với 2 mục con trên site gốc (Sám hối, Khuyến tu). */
function mld_register_thanh_ngon_taxonomy() {
	register_taxonomy( 'thanh_ngon_cat', 'thanh_ngon', array(
		'label'        => 'Chuyên mục Thánh Ngôn',
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'thanh-ngon-chuyen-muc' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mld_register_thanh_ngon_taxonomy' );

/** Danh sách chuyên mục Thánh Ngôn, đúng thứ tự + biểu tượng như site gốc. */
function mld_thanh_ngon_cat_order() {
	return array(
		'sam-hoi'   => 'SÁM HỐI',
		'khuyen-tu' => 'KHUYẾN TU',
	);
}

/** Tạo sẵn term mặc định cho chuyên mục Thánh Ngôn (chạy 1 lần khi kích hoạt/tải giao diện). */
function mld_seed_thanh_ngon_terms() {
	$terms = array(
		'sam-hoi'   => 'Sám hối',
		'khuyen-tu' => 'Khuyến tu',
	);
	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'thanh_ngon_cat' ) ) {
			wp_insert_term( $name, 'thanh_ngon_cat', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'mld_seed_thanh_ngon_terms', 20 );

/** Bài Thánh Ngôn chưa được gán chuyên mục sẽ tự động xếp vào "Sám hối"
 *  (đúng với hiện trạng site gốc: mục Khuyến tu hiện chưa có bài nào). */
function mld_autoassign_thanh_ngon_terms() {
	$posts = get_posts( array(
		'post_type'      => 'thanh_ngon',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );
	foreach ( $posts as $pid ) {
		$terms = get_the_terms( $pid, 'thanh_ngon_cat' );
		if ( ! $terms || is_wp_error( $terms ) ) {
			wp_set_object_terms( $pid, 'sam-hoi', 'thanh_ngon_cat' );
		}
	}
}
add_action( 'init', 'mld_autoassign_thanh_ngon_terms', 21 );

/* ------------------------------------------------------------------
 * 4. Ô nhập thông tin thêm (Meta boxes)
 * ------------------------------------------------------------------ */
function mld_add_meta_boxes() {
	add_meta_box( 'mld_event', 'Thông tin sự kiện', 'mld_event_box', 'su_kien', 'side', 'high' );
	add_meta_box( 'mld_quote', 'Nguồn / Tác giả', 'mld_quote_box', 'thanh_ngon', 'side', 'high' );
	add_meta_box( 'mld_video', 'Liên kết Video (YouTube)', 'mld_video_box', 'media_tt', 'side', 'high' );
	add_meta_box( 'mld_icon', 'Chữ biểu tượng (tùy chọn)', 'mld_icon_box', array( 'kinh', 'sach' ), 'side', 'default' );
	add_meta_box( 'mld_news', 'Thông tin bài Tin tức', 'mld_news_box', 'tin_tuc', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'mld_add_meta_boxes' );

function mld_field( $post, $key, $label, $type = 'text', $hint = '' ) {
	$val = esc_attr( get_post_meta( $post->ID, $key, true ) );
	echo '<p><label style="display:block;font-weight:600;margin-bottom:4px">' . esc_html( $label ) . '</label>';
	echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $key ) . '" value="' . $val . '" style="width:100%" />';
	if ( $hint ) { echo '<small style="color:#666">' . esc_html( $hint ) . '</small>'; }
	echo '</p>';
}

function mld_event_box( $post ) {
	wp_nonce_field( 'mld_meta', 'mld_meta_nonce' );
	mld_field( $post, '_mld_event_date', 'Ngày diễn ra', 'date' );
	mld_field( $post, '_mld_event_time', 'Giờ (vd: 01:00 - 08:00)', 'text' );
}
function mld_quote_box( $post ) {
	wp_nonce_field( 'mld_meta', 'mld_meta_nonce' );
	mld_field( $post, '_mld_quote_source', 'Nguồn', 'text', 'Vd: TN 8-12-1965 · Đông Phương Lão Tổ' );
}
function mld_video_box( $post ) {
	wp_nonce_field( 'mld_meta', 'mld_meta_nonce' );
	mld_field( $post, '_mld_video_url', 'Dán link YouTube', 'url', 'Vd: https://youtu.be/xxxxxxxx' );
}
function mld_icon_box( $post ) {
	wp_nonce_field( 'mld_meta', 'mld_meta_nonce' );
	mld_field( $post, '_mld_icon', 'Một chữ Hán/ký tự', 'text', 'Để trống sẽ tự lấy chữ đầu của tiêu đề' );
	mld_field( $post, '_mld_cover_url', 'Ảnh bìa (URL ngoài, tùy chọn)', 'url', 'Nếu để trống sẽ dùng Ảnh đại diện (Featured Image)' );
}
function mld_news_box( $post ) {
	wp_nonce_field( 'mld_meta', 'mld_meta_nonce' );
	mld_field( $post, '_mld_news_date', 'Ngày đăng (hiển thị)', 'text', 'Vd: 15-11-2025' );
	mld_field( $post, '_mld_news_source_url', 'Link bài gốc trên minhlydao.org.vn', 'url' );
}

function mld_save_meta( $post_id ) {
	if ( ! isset( $_POST['mld_meta_nonce'] ) || ! wp_verify_nonce( $_POST['mld_meta_nonce'], 'mld_meta' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	$keys = array( '_mld_event_date', '_mld_event_time', '_mld_quote_source', '_mld_video_url', '_mld_icon', '_mld_cover_url', '_mld_news_date', '_mld_news_source_url' );
	foreach ( $keys as $k ) {
		if ( isset( $_POST[ $k ] ) ) {
			update_post_meta( $post_id, $k, sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) );
		}
	}
}
add_action( 'save_post', 'mld_save_meta' );

/* ------------------------------------------------------------------
 * 5. Tùy biến (Customizer): Hero, Thư ngỏ, Liên hệ
 * ------------------------------------------------------------------ */
function mld_customize( $wp_customize ) {

	// ---- Banner (ảnh tĩnh đầu trang) ----
	$wp_customize->add_section( 'mld_hero', array( 'title' => 'Trang chủ: Banner (ảnh tĩnh)', 'priority' => 30 ) );

	$wp_customize->add_setting( 'mld_banner_bg', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mld_banner_bg', array(
		'label'       => 'Ảnh nền banner (mặc định: banner_tam_tong_mieu.jpg có sẵn trong theme)',
		'description' => 'Banner hiện là ảnh tĩnh, không còn slideshow. Logo và chữ Minh Lý Đạo - Tam Tông Miếu được ghép cố định lên trên ảnh nền này.',
		'section'     => 'mld_hero',
	) ) );

	// ---- Thư ngỏ ----
	$wp_customize->add_section( 'mld_intro', array( 'title' => 'Trang chủ: Thư ngỏ', 'priority' => 31 ) );

	$intro_defaults = array(
		'mld_intro_title' => 'MINH LÝ ĐẠO – TAM TÔNG MIẾU',
		'mld_intro_body'  => "Chào mừng quý đạo hữu, đạo tâm đến với trang Web của MINH LÝ ĐẠO – TAM TÔNG MIẾU (gọi tắt là Minh Lý Đạo).\n\nMinh Lý Đạo là một mối Đạo được Thượng Đế khai sáng bằng linh điển tại Việt Nam để tất cả mọi người từ thiện tín đến môn sanh có thể theo đó tự học, tự tu để quay về với Chánh Pháp mà Thiêng-Liêng đã ban trao.\n\nWebsite minhlydao.org.vn là trang Web chính thống và duy nhất của Hội Thánh Minh Lý Đạo nhằm qua đó quý đạo hữu/đạo tâm trong và ngoài nước Việt Nam sẽ từng bước biết rõ về Kinh sách, giáo luật, giáo lý, . . . . . và các hoạt động “tốt đời – đẹp Đạo” của Minh Lý Đạo. Website hoạt động dựa trên Hiến chương của Minh Lý Đạo và Chính sách sử dụng website (đính kèm bên dưới).\n\nRất mong trang Web minhlydao.org.vn sẽ là một nhịp cầu giúp cho tất cả mọi người, quý đạo hữu/đạo tâm trên bước đường tu học để tự độ và giác tha.",
		'mld_intro_note'  => 'CHÚ Ý: Ban biên tập Website minhlydao.org.vn không công nhận và không chịu trách nhiệm mọi thông tin không xuất phát từ trang Web nầy. — BBT. Website',
		'mld_intro_btn'   => '',
	);
	foreach ( $intro_defaults as $key => $def ) {
		$wp_customize->add_setting( $key, array( 'default' => $def, 'sanitize_callback' => 'wp_kses_post' ) );
		$type = ( 'mld_intro_body' === $key || 'mld_intro_note' === $key ) ? 'textarea' : 'text';
		$labels = array(
			'mld_intro_title' => 'Tiêu đề', 'mld_intro_body' => 'Nội dung (cách đoạn bằng dòng trống)',
			'mld_intro_note'  => 'Dòng lưu ý', 'mld_intro_btn' => 'Link nút "Xem thêm"',
		);
		$wp_customize->add_control( $key, array( 'label' => $labels[ $key ], 'section' => 'mld_intro', 'type' => $type ) );
	}
	$wp_customize->add_setting( 'mld_intro_img', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mld_intro_img', array( 'label' => 'Ảnh minh họa', 'section' => 'mld_intro' ) ) );

	// ---- Liên hệ ----
	$wp_customize->add_section( 'mld_contact', array( 'title' => 'Thông tin liên hệ (chân trang)', 'priority' => 32 ) );
	$contact_defaults = array(
		'mld_addr'  => 'Số 82, Đường Cao Thắng, Phường Bàn Cờ, TP.HCM.',
		'mld_phone' => '(84) (28) 3835 8181',
		'mld_email' => 'tamtongmieu1924@gmail.com',
		'mld_desc'  => 'Trang Web chính thống của Hội Thánh Minh Lý Đạo – Tam Tông Miếu.',
	);
	foreach ( $contact_defaults as $key => $def ) {
		$wp_customize->add_setting( $key, array( 'default' => $def, 'sanitize_callback' => 'sanitize_text_field' ) );
		$labels = array( 'mld_addr' => 'Địa chỉ', 'mld_phone' => 'Điện thoại', 'mld_email' => 'Email', 'mld_desc' => 'Mô tả ngắn' );
		$wp_customize->add_control( $key, array( 'label' => $labels[ $key ], 'section' => 'mld_contact', 'type' => ( 'mld_desc' === $key ? 'textarea' : 'text' ) ) );
	}
}
add_action( 'customize_register', 'mld_customize' );

/* ------------------------------------------------------------------
 * 6. Hàm tiện ích
 * ------------------------------------------------------------------ */

/** Lấy ID video YouTube từ một URL bất kỳ */
function mld_youtube_id( $url ) {
	if ( ! $url ) { return ''; }
	if ( preg_match( '%(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([A-Za-z0-9_-]{6,})%', $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/** Lấy mã nhúng YouTube từ một URL bất kỳ */
function mld_youtube_embed( $url ) {
	$id = mld_youtube_id( $url );
	return $id ? 'https://www.youtube.com/embed/' . $id : '';
}

/** In ra khối video: ưu tiên nhúng trực tiếp; nếu chủ kênh tắt tính năng nhúng
 *  (lỗi YouTube 153 "Video unavailable"), tự động hiện ảnh thumbnail bấm để
 *  mở video trên YouTube ở tab mới, tránh khung nhúng bị lỗi trống. */
function mld_youtube_box( $url, $title = '', $style = '' ) {
	$id = mld_youtube_id( $url );
	if ( ! $id ) { return; }
	$style_attr = $style ? ' style="' . esc_attr( $style ) . '"' : '';
	?>
	<div class="video-box"<?php echo $style_attr; ?>>
		<a class="ratio yt-thumb" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $id ); ?>" target="_blank" rel="noopener">
			<img src="<?php echo esc_url( 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg' ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
			<span class="play-btn" aria-hidden="true"></span>
		</a>
	</div>
	<?php
}

/** Logo: dùng custom-logo nếu có, nếu không dùng ảnh dự phòng */
function mld_logo( $class = 'logo' ) {
	$id = get_theme_mod( 'custom_logo' );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
	} else {
		$src = 'https://minhlydao.org.vn/img_data/images/banner/logo.png';
	}
	return '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $src ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
}

/** Hiện gợi ý cho quản trị viên khi một khu vực chưa có nội dung */
function mld_empty_hint( $msg ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<p class="admin-hint">' . esc_html( $msg ) . '</p>';
	}
}

/** Menu dự phòng khi chưa thiết lập Menu trong wp-admin
 *  (Sau khi nhập file minhlydao-noidung.wxr.xml, WordPress sẽ tự tạo Menu chính
 *  đầy đủ nhiều cấp — hàm này chỉ hiện ra khi menu đó chưa được gán ở
 *  Giao diện → Menu → vị trí "Menu chính (đầu trang)".) */
function mld_fallback_menu() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a></li>';

	$gioi_thieu = get_page_by_path( 'gioi-thieu' );
	if ( $gioi_thieu ) {
		echo '<li class="menu-item-has-children"><a href="' . esc_url( get_permalink( $gioi_thieu ) ) . '">Giới thiệu</a>';
		$children = get_pages( array( 'child_of' => $gioi_thieu->ID, 'sort_column' => 'menu_order' ) );
		if ( $children ) {
			echo '<ul class="sub-menu">';
			foreach ( $children as $child ) {
				echo '<li><a href="' . esc_url( get_permalink( $child ) ) . '">' . esc_html( get_the_title( $child ) ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}

	$cpts = array( 'giao_ly' => 'Giáo lý', 'kinh' => 'Kinh', 'sach' => 'Sách', 'thanh_ngon' => 'Thánh Ngôn', 'tu_tinh' => 'Tu tịnh', 'tin_tuc' => 'Tin tức' );
	foreach ( $cpts as $slug => $label ) {
		$link = get_post_type_archive_link( $slug );
		if ( $link ) { echo '<li><a href="' . esc_url( $link ) . '">' . esc_html( $label ) . '</a></li>'; }
	}

	$thu_vien = get_page_by_path( 'thu-vien-sach' );
	if ( $thu_vien ) { echo '<li><a href="' . esc_url( get_permalink( $thu_vien ) ) . '">Thư viện</a></li>'; }

	$media = get_page_by_path( 'audio' );
	if ( $media ) { echo '<li><a href="' . esc_url( get_permalink( $media ) ) . '">Media</a></li>'; }

	$lich = get_page_by_path( 'lich-tam-tong-mieu' );
	if ( $lich ) { echo '<li><a href="' . esc_url( get_permalink( $lich ) ) . '">Lịch tam tông miếu</a></li>'; }

	$lien_he = get_page_by_path( 'lien-he' );
	if ( $lien_he ) {
		echo '<li><a href="' . esc_url( get_permalink( $lien_he ) ) . '">Liên hệ</a></li>';
	}
	echo '</ul>';
}

/** Rút gọn nội dung an toàn */
function mld_excerpt( $len = 24 ) {
	return wp_trim_words( get_the_excerpt(), $len, '…' );
}

/** URL trang ngôn ngữ (English/Français) — tra theo slug trang, luôn ra URL đúng (có prefix cần thiết) */
function mld_lang_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/* ------------------------------------------------------------------
 * 6b. Hệ thống đa ngôn ngữ (VI mặc định / EN / FR) — site-wide
 * Không dùng plugin, không đụng tới rewrite rules gốc: chỉ "bóc" tiền tố
 * /en/ hoặc /fr/ khỏi request TRƯỚC khi WordPress tự phân giải URL như
 * bình thường (an toàn — nếu không có tiền tố, mọi thứ chạy y như cũ).
 * ------------------------------------------------------------------ */
$GLOBALS['mld_lang'] = 'vi';

function mld_strip_lang_prefix( $do_parse, $wp, $extra_query_vars ) {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
	$uri = preg_replace( '#\?.*$#', '', $uri );

	if ( preg_match( '#^(/index\.php)?/(en|fr)(/.*)?$#', $uri, $m ) ) {
		$GLOBALS['mld_lang'] = $m[2];
		$rest   = isset( $m[3] ) && '' !== trim( $m[3], '/' ) ? $m[3] : '/';
		$prefix = $m[1]; // '' hoặc '/index.php'

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$qs = strpos( $_SERVER['REQUEST_URI'], '?' );
			$_SERVER['REQUEST_URI'] = $prefix . $rest . ( false !== $qs ? substr( $_SERVER['REQUEST_URI'], $qs ) : '' );
		}
		if ( isset( $_SERVER['PATH_INFO'] ) ) {
			$_SERVER['PATH_INFO'] = ltrim( $prefix, '/index.php' ) === $prefix ? $rest : $rest;
			$_SERVER['PATH_INFO'] = $rest;
		}

		// Trang gốc /en/ hoặc /fr/ (không có gì phía sau) → trỏ tới trang tĩnh English/Français đã có sẵn.
		if ( '/' === $rest ) {
			$target_slug = 'en' === $m[2] ? 'en' : 'fr';
			$page        = get_page_by_path( $target_slug );
			if ( $page ) {
				$path = trim( wp_parse_url( get_permalink( $page ), PHP_URL_PATH ), '/' );
				$_SERVER['REQUEST_URI'] = '/' . $path . '/';
				if ( isset( $_SERVER['PATH_INFO'] ) ) {
					$_SERVER['PATH_INFO'] = '/' . preg_replace( '#^index\.php/#', '', $path ) . '/';
				}
			}
		}
	}

	return $do_parse;
}
add_filter( 'do_parse_request', 'mld_strip_lang_prefix', 5, 3 );

/** Ngôn ngữ hiện tại của request ('vi' | 'en' | 'fr') */
function mld_current_lang() {
	return isset( $GLOBALS['mld_lang'] ) ? $GLOBALS['mld_lang'] : 'vi';
}

/** Khi vao THANG url goc (khong tien to /en/ hay /fr/) cua mot bai da la ban dich
 * (vi mld_localize_permalink() co tinh giu URL rieng, sach cho cac bai da dich),
 * request nay khong co tien to nen mld_current_lang() mac dinh se tra ve 'vi',
 * lam menu/link sinh ra tren trang do bi roi ve tieng Viet (dung bug nguoi dung
 * bao: bam vao 1 bai tieng Anh xong menu lai tro ve tieng Viet).
 * Fix: neu bai dang xem co meta _mld_lang, dung luon gia tri do lam ngon ngu hien tai. */
function mld_detect_lang_from_singular() {
	if ( is_admin() ) { return; }
	if ( 'vi' !== mld_current_lang() ) { return; } // da co tien to ro rang tren URL -- khong ghi de.
	if ( ! is_singular() ) { return; }
	$post_id = get_queried_object_id();
	if ( ! $post_id ) { return; }
	$own_lang = get_post_meta( $post_id, '_mld_lang', true );
	if ( in_array( $own_lang, array( 'en', 'fr' ), true ) ) {
		$GLOBALS['mld_lang'] = $own_lang;
	}
}
add_action( 'wp', 'mld_detect_lang_from_singular' );

/** Chèn tiền tố ngôn ngữ vào một URL nội bộ của site (giữ nguyên phần còn lại).
 * LUU Y: site dung permalink dang "index.php/...", nen tien to ngon ngu phai nam
 * SAU "index.php/" (VD: /index.php/en/...), khong phai truoc no (VD sai: /en/index.php/...
 * -- dang nay Apache tra 404 thang, khong toi duoc WordPress). */
function mld_localize_url( $url, $lang = null ) {
	if ( null === $lang ) { $lang = mld_current_lang(); }
	if ( 'vi' === $lang ) { return $url; }

	$home = home_url( '/' );
	if ( 0 !== strpos( $url, $home ) ) { return $url; }

	$path = substr( $url, strlen( $home ) ); // phần sau domain, không có "/" đầu
	// Gỡ tiền tố ngôn ngữ cũ nếu có, dù nằm trước hay sau "index.php/".
	$path = preg_replace( '#^index\.php/(en|fr)/#', 'index.php/', $path );
	$path = preg_replace( '#^(en|fr)/#', '', $path );

	if ( 0 === strpos( $path, 'index.php/' ) ) {
		$rest = substr( $path, strlen( 'index.php/' ) );
		return $home . 'index.php/' . $lang . '/' . $rest;
	}
	return $home . $lang . '/' . $path;
}

/** Nhóm dịch: tra bài viết song ngữ tương ứng của $post_id theo ngôn ngữ $lang. Trả về WP_Post hoặc false. */
function mld_get_translation( $post_id, $lang ) {
	if ( 'vi' === $lang ) { return get_post( $post_id ); }
	$group = get_post_meta( $post_id, '_mld_lang_group', true );
	if ( ! $group ) { return false; }
	$q = new WP_Query( array(
		'post_type'      => get_post_type( $post_id ),
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array( 'key' => '_mld_lang_group', 'value' => $group ),
			array( 'key' => '_mld_lang', 'value' => $lang ),
		),
	) );
	return $q->have_posts() ? $q->posts[0] : false;
}

/** Trên trang single: nếu đang xem bằng EN/FR — hiển thị bản dịch nếu có; nếu KHÔNG có, để trống tiêu đề/nội dung
 * (đúng như hành vi thật của site cũ), KHÔNG hiện tạm nội dung tiếng Việt để tránh gây hiểu nhầm là đã dịch. */
function mld_swap_to_translation() {
	if ( ! is_singular() ) { return; }
	global $post, $wp_query;
	$GLOBALS['mld_original_post_id'] = $post->ID;

	$lang = mld_current_lang();
	if ( 'vi' === $lang ) { return; }

	// Trang WordPress Page (Giới thiệu, English/Français, Liên hệ...) đã có nội dung riêng theo từng ngôn ngữ sẵn — không áp dụng.
	if ( 'page' === get_post_type( $post ) ) { return; }

	// Dang xem THANG url rieng cua chinh bai dich (vi du vao tu archive, khong qua tien to /en/) --
	// bai nay da dung ngon ngu roi, khong can (va khong duoc) swap/de trong noi dung cua chinh no.
	$own_lang = get_post_meta( $post->ID, '_mld_lang', true );
	if ( $own_lang === $lang ) { return; }

	$translated = mld_get_translation( $post->ID, $lang );
	if ( $translated && $translated->ID !== $post->ID ) {
		$post = $translated;
		$wp_query->post  = $translated;
		$wp_query->posts = array( $translated );
		setup_postdata( $translated );
		return;
	}

	// Chưa có bản dịch: để trống, giống hệt site cũ.
	$post->post_title   = '';
	$post->post_content = '';
	$post->post_excerpt = '';
	$wp_query->post  = $post;
	$wp_query->posts = array( $post );
	setup_postdata( $post );
	$GLOBALS['mld_untranslated'] = true;
}
add_action( 'template_redirect', 'mld_swap_to_translation' );

/** Bản đồ slug trang WordPress Page (Giới thiệu, Hiến chương...) => URL trang tiếng Anh tương ứng đã dịch sẵn
 * (nội dung này được dịch từ giai đoạn đầu dự án, nằm dưới /en/... — bản đồ này chỉ để nút chuyển ngôn ngữ
 * trỏ đúng chỗ, không đụng tới nội dung của các trang đó). */
function mld_page_translation_map() {
	return array(
		'gioi-thieu'         => array( 'en' => '/en/introduction/' ),
		'thu-ngo'            => array( 'en' => '/en/welcome-letter/' ),
		'gioi-thieu-chung'   => array( 'en' => '/en/general-introduction/' ),
		'hien-chuong'        => array( 'en' => '/en/charter/' ),
		'hien-chuong-phan-1' => array( 'en' => '/en/charter-part-1/' ),
		'hien-chuong-phan-2' => array( 'en' => '/en/charter-part-2/' ),
		'hien-chuong-phan-3' => array( 'en' => '/en/charter-part-3/' ),
		'tho-phuong'         => array( 'en' => '/en/worship/' ),
		'lich-su-thanh-lap'  => array( 'en' => '/en/history/' ),
		'chinh-sach-website' => array( 'en' => '/en/website-usage-policy/' ),
		'lien-he'            => array( 'en' => '/en/contact/' ),
	);
}

/** URL để chuyển trang hiện tại sang ngôn ngữ $lang, cố gắng trỏ đúng bài/trang tương ứng thay vì luôn về trang chủ ngôn ngữ đó. */
function mld_lang_switch_url( $lang ) {
	if ( isset( $GLOBALS['mld_original_post_id'] ) ) {
		$orig = get_post( $GLOBALS['mld_original_post_id'] );
		if ( $orig && 'page' === $orig->post_type && 'vi' !== $lang ) {
			$map = mld_page_translation_map();
			if ( isset( $map[ $orig->post_name ][ $lang ] ) ) {
				return home_url( $map[ $orig->post_name ][ $lang ] );
			}
		}
		// Voi cac CPT (su_kien, kinh, sach, thanh_ngon, giao_ly, tu_tinh, tin_tuc...) -- neu da co
		// bai dich lien ket qua _mld_lang_group thi tro thang toi URL that cua bai dich do,
		// thay vi dung tien to /en/ chung chung (bai dich co slug rieng, khong giong bai goc).
		if ( $orig && 'page' !== $orig->post_type && 'vi' !== $lang ) {
			$translated = mld_get_translation( $orig->ID, $lang );
			if ( $translated && $translated->ID !== $orig->ID ) {
				return get_permalink( $translated );
			}
		}
		$base = get_permalink( $GLOBALS['mld_original_post_id'] );
		return 'vi' === $lang ? $base : mld_localize_url( $base, $lang );
	}
	if ( 'vi' === $lang ) { return home_url( '/' ); }
	return mld_lang_page_url( $lang );
}

/** Chen tien to ngon ngu vao MOI permalink cua bai viet/CPT/taxonomy duoc sinh ra qua
 * get_permalink()/the_permalink() (ke ca trong vong lap archive, bai lien quan...) -- de khi
 * dang duyet web o che do EN/FR, bam "Xem chi tiet" hay bat ky link bai viet nao cung GIU
 * NGUYEN ngon ngu dang xem, thay vi rot ve ban tieng Viet.
 * Ngoai le: neu bai viet DA LA ban dich (co meta _mld_lang) thi giu nguyen URL that cua no,
 * khong chen them tien to (URL do da dung va day du roi). */
function mld_localize_permalink( $url, $post = null ) {
	$lang = mld_current_lang();
	if ( 'vi' === $lang ) { return $url; }
	if ( $post instanceof WP_Post ) {
		$post_lang = get_post_meta( $post->ID, '_mld_lang', true );
		if ( $post_lang ) { return $url; }
	}
	return mld_localize_url( $url, $lang );
}
add_filter( 'post_link', 'mld_localize_permalink', 10, 2 );
add_filter( 'post_type_link', 'mld_localize_permalink', 10, 2 );
add_filter( 'term_link', 'mld_localize_permalink', 10, 1 );
add_filter( 'post_type_archive_link', 'mld_localize_permalink', 10, 1 );

/** Cac CPT co ho tro da ngon ngu (dung chung cho loc truy van ben duoi). */
function mld_translatable_post_types() {
	return array( 'su_kien', 'kinh', 'sach', 'thanh_ngon', 'giao_ly', 'tu_tinh', 'tin_tuc' );
}

/** Loc theo ngon ngu cho cac trang danh sach/chuyen muc/bai lien quan (khong phai truy van
 * chinh xac dinh bai dang xem):
 * - Che do EN/FR: chi hien thi bai DA dich (post co _mld_lang = ngon ngu hien tai), khong
 *   hien thi lan ban goc tieng Viet hoac bai chua dich -- nhat quan voi viec de trong noi
 *   dung chua dich tren trang bai don (mld_swap_to_translation).
 * - Che do tieng Viet: LOAI cac bai da dich (co meta _mld_lang, vi day la ban sao rieng
 *   duoc tao sau nen ngay tao moi hon, de lan vao muc "moi nhat" neu khong loc) -- chi con
 *   lai bai goc tieng Viet, dung nhu mong doi tren mot site tieng Viet.
 * KHONG dung vao truy van chinh cua trang bai don (main query + is_singular) vi co che swap
 * ban dich da xu ly rieng o do. */
function mld_filter_query_by_lang( $query ) {
	if ( is_admin() ) { return; }
	if ( $query->is_main_query() && $query->is_singular() ) { return; }

	$translatable = mld_translatable_post_types();
	$qtype        = $query->get( 'post_type' );
	$types        = is_array( $qtype ) ? $qtype : array( $qtype );
	$match        = false;
	foreach ( $types as $t ) {
		if ( in_array( $t, $translatable, true ) ) { $match = true; break; }
	}

	// Truy van chuyen muc theo taxonomy (vd /giao-ly-chuyen-muc/giao-ly/) thuong KHONG co
	// 'post_type' trong query var (WP tu suy ra qua taxonomy) -- can anh xa nguoc ve post_type
	// tuong ung de van loc dung, khong de lot bai chua dich qua cac trang chuyen muc nay.
	if ( ! $match ) {
		$tax_map = array(
			'giao_ly_cat'    => 'giao_ly',
			'thanh_ngon_cat' => 'thanh_ngon',
			'tin_tuc_cat'    => 'tin_tuc',
		);
		foreach ( $tax_map as $tax => $pt ) {
			if ( $query->get( $tax ) || $query->is_tax( $tax ) ) { $match = true; break; }
		}
	}
	if ( ! $match ) { return; }

	$lang       = mld_current_lang();
	$meta_query = $query->get( 'meta_query' );
	$meta_query = is_array( $meta_query ) ? $meta_query : array();

	if ( 'vi' === $lang ) {
		$meta_query[] = array( 'key' => '_mld_lang', 'compare' => 'NOT EXISTS' );
	} else {
		$meta_query[] = array( 'key' => '_mld_lang', 'value' => $lang );
	}
	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'mld_filter_query_by_lang' );

/** Bản dịch nhãn menu chính (VI => [EN, FR]). Nhãn không có trong bảng vẫn hiển thị nguyên văn tiếng Việt. */
function mld_menu_label_map() {
	return array(
		'Trang chủ'         => array( 'en' => 'Home',            'fr' => 'Accueil' ),
		'Giới thiệu'         => array( 'en' => 'About us',        'fr' => 'À propos' ),
		'Giáo lý'            => array( 'en' => 'Doctrine',        'fr' => 'Doctrine' ),
		'Tu tịnh'            => array( 'en' => 'Practice',        'fr' => 'Pratique' ),
		'Kinh – Sách'        => array( 'en' => 'Scriptures',      'fr' => 'Écritures' ),
		'Kinh - sách'        => array( 'en' => 'Scriptures',      'fr' => 'Écritures' ),
		'Kinh'               => array( 'en' => 'Bible',           'fr' => 'Bible' ),
		'Sách'               => array( 'en' => 'Book',            'fr' => 'Livre' ),
		'Thánh ngôn'         => array( 'en' => 'Holy Word',       'fr' => 'Parole Sainte' ),
		'Thánh Ngôn'         => array( 'en' => 'Holy Word',       'fr' => 'Parole Sainte' ),
		'Thư viện'           => array( 'en' => 'Book library',    'fr' => 'Bibliothèque' ),
		'Media'              => array( 'en' => 'Media',           'fr' => 'Média' ),
		'Lịch'               => array( 'en' => 'Calendar of the Three Temples', 'fr' => 'Calendrier' ),
		'Tin tức'            => array( 'en' => 'News',            'fr' => 'Actualités' ),
		'Liên hệ'            => array( 'en' => 'Contact us',      'fr' => 'Contactez-nous' ),
		'Thư ngỏ'            => array( 'en' => 'Cover letter',    'fr' => 'Lettre ouverte' ),
		'Giới thiệu chung'   => array( 'en' => 'General introduction', 'fr' => 'Introduction générale' ),
		'Hiến chương'        => array( 'en' => 'Charter',         'fr' => 'Charte' ),
		'Thờ phượng'         => array( 'en' => 'Worship',         'fr' => 'Culte' ),
		'Lịch sử thành lập'  => array( 'en' => 'History of establishment', 'fr' => 'Histoire de la fondation' ),
	);
}

function mld_translate_menu_items( $items ) {
	$lang = mld_current_lang();
	if ( 'vi' === $lang ) { return $items; }
	$map = mld_menu_label_map();
	foreach ( $items as $item ) {
		$title = trim( $item->title );
		if ( isset( $map[ $title ][ $lang ] ) ) {
			$item->title = $map[ $title ][ $lang ];
		}
		if ( ! empty( $item->url ) ) {
			$item->url = mld_localize_url( $item->url, $lang );
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'mld_translate_menu_items' );

/* ------------------------------------------------------------------
 * 7. Breadcrumb (Trang chủ > Mục cha > Trang hiện tại)
 * ------------------------------------------------------------------ */
function mld_breadcrumb() {
	if ( is_front_page() ) { return; }

	$trail = array( '<a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a>' );

	if ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor_id ) {
			$trail[] = '<a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">' . esc_html( get_the_title( $ancestor_id ) ) . '</a>';
		}
		$trail[] = '<span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_singular() ) {
		$pt_obj = get_post_type_object( get_post_type() );
		if ( in_array( get_post_type(), array( 'kinh', 'sach', 'thanh_ngon' ), true ) ) {
			$trail[] = '<a href="' . esc_url( get_post_type_archive_link( 'kinh' ) ) . '">Kinh – sách</a>';
		}
		if ( $pt_obj && $pt_obj->has_archive ) {
			$trail[] = '<a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '">' . esc_html( $pt_obj->labels->name ) . '</a>';
		}
		if ( 'thanh_ngon' === get_post_type() ) {
			$mld_tn_terms = get_the_terms( get_the_ID(), 'thanh_ngon_cat' );
			if ( $mld_tn_terms && ! is_wp_error( $mld_tn_terms ) ) {
				$trail[] = '<span>' . esc_html( $mld_tn_terms[0]->name ) . '</span>';
			} else {
				$trail[] = '<span>' . esc_html( get_the_title() ) . '</span>';
			}
		} else {
			$trail[] = '<span>' . esc_html( get_the_title() ) . '</span>';
		}
	} elseif ( is_post_type_archive() ) {
		if ( in_array( get_query_var( 'post_type' ), array( 'kinh', 'sach', 'thanh_ngon' ), true ) ) {
			$trail[] = '<a href="' . esc_url( get_post_type_archive_link( 'kinh' ) ) . '">Kinh – sách</a>';
		}
		$trail[] = '<span>' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
	} elseif ( is_home() ) {
		$trail[] = '<span>Tin tức</span>';
	} elseif ( is_category() || is_tag() || is_archive() ) {
		$trail[] = '<span>' . esc_html( single_cat_title( '', false ) ) . '</span>';
	}

	echo '<nav class="breadcrumb" aria-label="breadcrumb">' . implode( '<span class="sep">/</span>', $trail ) . '</nav>'; // phpcs:ignore
}

/* ------------------------------------------------------------------
 * 8. Form Liên hệ (trang page-lien-he.php)
 * ------------------------------------------------------------------ */
function mld_contact_form() {
	$sent  = isset( $_GET['mld_contact'] ) && 'ok' === $_GET['mld_contact'];
	$error = isset( $_GET['mld_contact'] ) && 'error' === $_GET['mld_contact'];
	?>
	<div class="contact-form-wrap">
		<?php if ( $sent ) : ?>
			<p class="form-notice form-notice-ok">Cảm ơn quý đạo hữu/đạo tâm đã gửi yêu cầu. Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
		<?php endif; ?>
		<?php if ( $error ) : ?>
			<p class="form-notice form-notice-err">Có lỗi xảy ra, vui lòng kiểm tra lại thông tin và gửi lại.</p>
		<?php endif; ?>
		<form class="mld-contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mld_contact">
			<?php wp_nonce_field( 'mld_contact_action', 'mld_contact_nonce' ); ?>
			<p style="position:absolute;left:-9999px" aria-hidden="true">
				<label>Để trống ô này<input type="text" name="mld_website" tabindex="-1" autocomplete="off"></label>
			</p>
			<div class="form-row">
				<label for="mld_name">Họ và tên *</label>
				<input type="text" id="mld_name" name="mld_name" required>
			</div>
			<div class="form-row">
				<label for="mld_email">Email *</label>
				<input type="email" id="mld_email" name="mld_email" required>
			</div>
			<div class="form-row">
				<label for="mld_phone">Điện thoại</label>
				<input type="text" id="mld_phone" name="mld_phone">
			</div>
			<div class="form-row">
				<label for="mld_message">Nội dung *</label>
				<textarea id="mld_message" name="mld_message" rows="5" required></textarea>
			</div>
			<button type="submit" class="btn">Gửi yêu cầu</button>
		</form>
	</div>
	<?php
}

function mld_handle_contact_form() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/lien-he/' );

	if ( ! isset( $_POST['mld_contact_nonce'] ) || ! wp_verify_nonce( $_POST['mld_contact_nonce'], 'mld_contact_action' ) ) {
		wp_safe_redirect( add_query_arg( 'mld_contact', 'error', $redirect ) );
		exit;
	}
	// Honeypot: nếu bot điền vào ô ẩn thì âm thầm bỏ qua.
	if ( ! empty( $_POST['mld_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'mld_contact', 'ok', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['mld_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mld_name'] ) ) : '';
	$email   = isset( $_POST['mld_email'] ) ? sanitize_email( wp_unslash( $_POST['mld_email'] ) ) : '';
	$phone   = isset( $_POST['mld_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mld_phone'] ) ) : '';
	$message = isset( $_POST['mld_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mld_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'mld_contact', 'error', $redirect ) );
		exit;
	}

	$to      = get_theme_mod( 'mld_email', get_option( 'admin_email' ) );
	$subject = 'Liên hệ từ website — ' . $name;
	$body    = "Họ và tên: {$name}\nEmail: {$email}\nĐiện thoại: {$phone}\n\nNội dung:\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'mld_contact', 'ok', $redirect ) );
	exit;
}
add_action( 'admin_post_nopriv_mld_contact', 'mld_handle_contact_form' );
add_action( 'admin_post_mld_contact', 'mld_handle_contact_form' );


