<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<footer class="site-footer">
  <div class="wrap foot-grid">
    <div class="foot-logo">
      <?php echo mld_logo( '' ); // phpcs:ignore ?>
      <p><?php echo esc_html( get_theme_mod( 'mld_desc', 'Trang Web chính thống của Hội Thánh Minh Lý Đạo – Tam Tông Miếu.' ) ); ?></p>
    </div>
    <div>
      <h5>Liên kết</h5>
      <?php if ( has_nav_menu( 'footer' ) ) : ?>
        <div class="foot-links"><?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'items_wrap' => '%3$s', 'depth' => 1 ) ); ?></div>
      <?php else : ?>
        <div class="foot-links">
          <?php
          $mld_gt = get_page_by_path( 'gioi-thieu' );
          $mld_lh = get_page_by_path( 'lien-he' );
          $mld_tv = get_page_by_path( 'thu-vien-sach' );
          $mld_media = get_page_by_path( 'audio' );
          $mld_lich = get_page_by_path( 'lich-tam-tong-mieu' );
          ?>
          <a href="<?php echo $mld_gt ? esc_url( get_permalink( $mld_gt ) ) : esc_url( home_url( '/' ) ); ?>">Giới thiệu</a>
          <?php if ( $mld_tv ) : ?><a href="<?php echo esc_url( get_permalink( $mld_tv ) ); ?>">Thư viện</a><?php endif; ?>
          <?php if ( $mld_lich ) : ?><a href="<?php echo esc_url( get_permalink( $mld_lich ) ); ?>">Lịch</a><?php endif; ?>
          <?php if ( $mld_media ) : ?><a href="<?php echo esc_url( get_permalink( $mld_media ) ); ?>">Audio/video</a><?php endif; ?>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'kinh' ) ); ?>">Kinh sách</a>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'tin_tuc' ) ); ?>">Tin tức</a>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'tu_tinh' ) ); ?>">Tu tịnh</a>
          <?php if ( $mld_lh ) : ?><a href="<?php echo esc_url( get_permalink( $mld_lh ) ); ?>">Liên hệ</a><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="foot-contact">
      <h5>Liên hệ</h5>
      <p><b>Địa chỉ:</b> <?php echo esc_html( get_theme_mod( 'mld_addr', 'Số 82, Đường Cao Thắng, Phường Bàn Cờ, TP.HCM.' ) ); ?></p>
      <p><b>Điện thoại:</b> <?php echo esc_html( get_theme_mod( 'mld_phone', '(84) (28) 3835 8181' ) ); ?></p>
      <p><b>Email:</b> <?php echo esc_html( get_theme_mod( 'mld_email', 'tamtongmieu1924@gmail.com' ) ); ?></p>
      <?php
      // Trang này là trang con (Trang cha: Giới thiệu) nên get_page_by_path() theo slug đơn
      // sẽ không tìm ra — tra theo 'name' (post_name) để không phụ thuộc cấp phân trang.
      $mld_cs_posts = get_posts( array(
        'post_type'      => 'page',
        'name'           => 'chinh-sach-website',
        'posts_per_page' => 1,
      ) );
      $mld_cs = $mld_cs_posts ? $mld_cs_posts[0] : null;
      ?>
      <?php if ( $mld_cs ) : ?><p><a href="<?php echo esc_url( get_permalink( $mld_cs ) ); ?>">Chính sách sử dụng website</a></p><?php endif; ?>
    </div>
  </div>
  <div class="copyright">
    COPYRIGHT © <?php echo esc_html( date( 'Y' ) ); ?> Bản quyền thuộc về <?php bloginfo( 'name' ); ?>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
