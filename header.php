<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$banner_base = get_template_directory_uri() . '/assets/images/banner/';
$banner_bg   = get_theme_mod( 'mld_banner_bg', $banner_base . 'banner_tam_tong_mieu.jpg' );
?>
<div class="hero-banner site-banner">
  <img class="hero-bg" src="<?php echo esc_url( $banner_bg ); ?>" alt="">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="hero-logo" src="<?php echo esc_url( $banner_base . 'logo.png' ); ?>" alt="Biểu tượng Tam Giáo"></a>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-text" aria-label="Minh Lý Đạo - Tam Tông Miếu">
    <span class="ht-line1">MINH LÝ ĐẠO</span>
    <span class="ht-line2">TAM-TÔNG MIẾU</span>
    <span class="ht-line3">廟&nbsp;&nbsp;宗&nbsp;&nbsp;㆔</span>
  </a>
</div>

<header class="site-header">
  <div class="wrap head-inner">
    <button class="menu-toggle" aria-label="Menu" onclick="document.getElementById('primary-nav').classList.toggle('open')">☰</button>
    <nav class="main-nav" id="primary-nav">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'fallback_cb'    => 'mld_fallback_menu',
        'depth'          => 0,
      ) );
      ?>
    </nav>
    <div class="lang">
      <a href="<?php echo esc_url( mld_lang_switch_url( 'vi' ) ); ?>"><img src="https://minhlydao.org.vn/templates/images/vi.png" alt="VN" width="18"> VI</a>
      <!-- TAM AN nut EN/FR den khi ban dich xong va chinh xac. Doan code goc (de bat lai sau):
      <a href="EN_URL"><img src="https://minhlydao.org.vn/templates/images/en.png" alt="EN" width="18"> EN</a>
      <a href="FR_URL"><img src="https://minhlydao.org.vn/templates/images/fr.png" alt="FR" width="18"> FR</a>
      Xem lich su git de lay lai code PHP that (co goi mld_lang_switch_url). -->
    </div>
  </div>
</header>
