<?php
/**
 * Template riêng cho trang "Lịch tam tông miếu" (slug: lich-tam-tong-mieu).
 * Lịch âm dương tương tác: chọn tháng/năm (1950-2100), xem ngày tốt xấu
 * (Trực, Ngày Hoàng đạo/Hắc đạo, giờ hoàng đạo) theo phương pháp lịch vạn niên truyền thống.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once get_template_directory() . '/inc/lunar-calendar.php';
get_header();

$today = getdate();
$month = isset( $_GET['m'] ) ? max( 1, min( 12, (int) $_GET['m'] ) ) : (int) $today['mon'];
$year  = isset( $_GET['y'] ) ? max( 1950, min( 2100, (int) $_GET['y'] ) ) : (int) $today['year'];

$first_ts     = mktime( 0, 0, 0, $month, 1, $year );
$days_in_month = (int) date( 't', $first_ts );
$start_dow    = (int) date( 'N', $first_ts ); // 1=T2 ... 7=CN
$leading      = $start_dow - 1;

// Tổng số ô hiển thị (bội số của 7), thêm ngày đầu/cuối tháng liền kề để lấp đầy tuần.
$total_cells = (int) ceil( ( $leading + $days_in_month ) / 7 ) * 7;

$cells = array();
for ( $i = 0; $i < $total_cells; $i++ ) {
	$day_offset = $i - $leading + 1; // 1..days_in_month cho ngày trong tháng, <=0 hoặc >days_in_month cho tháng liền kề
	$ts   = mktime( 0, 0, 0, $month, $day_offset, $year );
	$dd   = (int) date( 'j', $ts );
	$mm   = (int) date( 'n', $ts );
	$yy   = (int) date( 'Y', $ts );
	$in_month = ( $mm === $month );
	$is_today = ( $dd === (int) $today['mday'] && $mm === (int) $today['mon'] && $yy === (int) $today['year'] );
	$info = mld_get_day_info( $dd, $mm, $yy );
	$cells[] = array(
		'dd' => $dd, 'mm' => $mm, 'yy' => $yy,
		'in_month' => $in_month, 'is_today' => $is_today,
		'info' => $info,
	);
}

$month_names = array( 1=>'Tháng 1', 2=>'Tháng 2', 3=>'Tháng 3', 4=>'Tháng 4', 5=>'Tháng 5', 6=>'Tháng 6', 7=>'Tháng 7', 8=>'Tháng 8', 9=>'Tháng 9', 10=>'Tháng 10', 11=>'Tháng 11', 12=>'Tháng 12' );
$dow_labels  = array( 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN' );

$prev_m = $month - 1; $prev_y = $year;
if ( $prev_m < 1 ) { $prev_m = 12; $prev_y--; }
$next_m = $month + 1; $next_y = $year;
if ( $next_m > 12 ) { $next_m = 1; $next_y++; }
$page_url = get_permalink();

while ( have_posts() ) : the_post();
?>
<div class="page-head"><div class="wrap"><?php mld_breadcrumb(); ?><h1><?php the_title(); ?></h1></div></div>
<article class="content-area">
	<div class="entry-content"><?php the_content(); ?></div>

	<div class="mld-lich">
		<form class="mld-lich-picker" method="get" action="<?php echo esc_url( $page_url ); ?>">
			<select name="m">
				<?php foreach ( $month_names as $mnum => $mlabel ) : ?>
					<option value="<?php echo (int) $mnum; ?>" <?php selected( $mnum, $month ); ?>><?php echo esc_html( $mlabel ); ?></option>
				<?php endforeach; ?>
			</select>
			<select name="y">
				<?php for ( $y = 1950; $y <= 2100; $y++ ) : ?>
					<option value="<?php echo (int) $y; ?>" <?php selected( $y, $year ); ?>><?php echo (int) $y; ?></option>
				<?php endfor; ?>
			</select>
			<button type="submit" class="btn">Xem</button>
			<a class="mld-lich-nav" href="<?php echo esc_url( add_query_arg( array( 'm' => $prev_m, 'y' => $prev_y ), $page_url ) ); ?>">‹ Tháng trước</a>
			<a class="mld-lich-nav" href="<?php echo esc_url( add_query_arg( array( 'm' => $next_m, 'y' => $next_y ), $page_url ) ); ?>">Tháng sau ›</a>
		</form>

		<h2 class="mld-lich-title"><?php echo esc_html( $month_names[ $month ] . ' năm ' . $year ); ?></h2>

		<div class="mld-lich-grid">
			<?php foreach ( $dow_labels as $lbl ) : ?>
				<div class="mld-lich-dow"><?php echo esc_html( $lbl ); ?></div>
			<?php endforeach; ?>

			<?php foreach ( $cells as $c ) :
				$info = $c['info'];
				$hd   = $info['hoang_dao'];
				$tk   = $info['tiet_khi'];
				$cls  = 'mld-lich-cell';
				if ( ! $c['in_month'] ) { $cls .= ' is-outside'; }
				if ( $c['is_today'] ) { $cls .= ' is-today'; }
				if ( $tk['is_start'] ) { $cls .= ' has-tietkhi'; }
				$cls .= $hd['hoang_dao'] ? ' is-hoangdao' : ' is-hacdao';
				$data = wp_json_encode( array(
					'duong'   => sprintf( '%02d/%02d/%04d', $c['dd'], $c['mm'], $c['yy'] ),
					'am'      => sprintf( '%d/%d%s', $info['am']['d'], $info['am']['m'], $info['am']['nhuan'] ? ' (nhuận)' : '' ),
					'ccngay'  => $info['can_chi_ngay'],
					'ccthang' => $info['can_chi_thang'],
					'ccnam'   => $info['can_chi_nam'],
					'truc'    => $info['truc'],
					'sao'     => $hd['name'],
					'hoangdao'=> $hd['hoang_dao'],
					'gio'     => $info['gio_hoang_dao'],
					'tietkhi' => $tk['vi'],
					'tietkhien' => $tk['en'],
					'tietkhifr' => $tk['fr'],
					'tietkhistart' => $tk['is_start'],
				) );
			?>
				<button type="button" class="<?php echo esc_attr( $cls ); ?>" data-info='<?php echo esc_attr( $data ); ?>'>
					<span class="d-num"><?php echo (int) $c['dd']; ?></span>
					<span class="d-am"><?php echo (int) $info['am']['d']; ?>/<?php echo (int) $info['am']['m']; ?></span>
					<span class="d-truc"><?php echo esc_html( $info['truc'] ); ?></span>
					<?php if ( $tk['is_start'] ) : ?>
						<span class="d-tietkhi"><?php echo esc_html( $tk['vi'] ); ?></span>
					<?php endif; ?>
					<span class="d-dot" aria-hidden="true"></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="mld-lich-legend">
			<span><i class="dot hoangdao"></i> Ngày Hoàng đạo (tốt)</span>
			<span><i class="dot hacdao"></i> Ngày Hắc đạo (xấu)</span>
			<span><i class="dot tietkhi"></i> Ngày bắt đầu Tiết khí</span>
		</div>
	</div>

	<div id="mld-lich-modal" class="mld-lich-modal" hidden>
		<div class="mld-lich-modal-box">
			<button type="button" class="mld-lich-modal-close" aria-label="Đóng">&times;</button>
			<div class="mld-lich-modal-body"></div>
		</div>
	</div>
</article>
<?php endwhile; get_footer(); ?>
