<?php
/**
 * Thư viện tính lịch âm dương Việt Nam + Can Chi + Trực + Ngày Hoàng đạo/Hắc đạo + Giờ hoàng đạo.
 *
 * Thuật toán chuyển đổi dương lịch <-> âm lịch dựa theo phương pháp thiên văn chuẩn
 * (thuật toán quen thuộc dùng trong hầu hết lịch vạn niên Việt Nam, múi giờ UTC+7).
 * Chính xác cho khoảng năm 1900-2100 trở lên là an toàn nhất; dùng được rộng hơn.
 *
 * Không phụ thuộc dữ liệu ngoài — toàn bộ tính bằng công thức thiên văn nên
 * dùng được cho mọi năm mà không cần bảng tra.
 *
 * @package MinhLyDao
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------
 * A. Julian Day <-> Dương lịch
 * ------------------------------------------------------------------ */
function mld_jd_from_date( $dd, $mm, $yy ) {
	$a = (int) floor( ( 14 - $mm ) / 12 );
	$y = $yy + 4800 - $a;
	$m = $mm + 12 * $a - 3;
	$jd = $dd + (int) floor( ( 153 * $m + 2 ) / 5 ) + 365 * $y + (int) floor( $y / 4 ) - (int) floor( $y / 100 ) + (int) floor( $y / 400 ) - 32045;
	if ( $jd < 2299161 ) {
		$jd = $dd + (int) floor( ( 153 * $m + 2 ) / 5 ) + 365 * $y + (int) floor( $y / 4 ) - 32083;
	}
	return $jd;
}

function mld_jd_to_date( $jd ) {
	if ( $jd > 2299160 ) {
		$a = $jd + 32044;
		$b = (int) floor( ( 4 * $a + 3 ) / 146097 );
		$c = $a - (int) floor( ( $b * 146097 ) / 4 );
	} else {
		$b = 0;
		$c = $jd + 32082;
	}
	$d = (int) floor( ( 4 * $c + 3 ) / 1461 );
	$e = $c - (int) floor( ( 1461 * $d ) / 4 );
	$m = (int) floor( ( 5 * $e + 2 ) / 153 );
	$day   = $e - (int) floor( ( 153 * $m + 2 ) / 5 ) + 1;
	$month = $m + 3 - 12 * (int) floor( $m / 10 );
	$year  = $b * 100 + $d - 4800 + (int) floor( $m / 10 );
	return array( $day, $month, $year );
}

/* ------------------------------------------------------------------
 * B. Điểm Sóc (New Moon) & Kinh độ Mặt Trời
 * ------------------------------------------------------------------ */
function mld_new_moon( $k ) {
	$T  = $k / 1236.85;
	$T2 = $T * $T;
	$T3 = $T2 * $T;
	$dr = M_PI / 180;
	$Jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $T2 - 0.000000155 * $T3;
	$Jd1 = $Jd1 + 0.00033 * sin( ( 166.56 + 132.87 * $T - 0.009173 * $T2 ) * $dr );
	$M   = 359.2242 + 29.10535608 * $k - 0.0000333 * $T2 - 0.00000347 * $T3;
	$Mpr = 306.0253 + 385.81691806 * $k + 0.0107306 * $T2 + 0.00001236 * $T3;
	$F   = 21.2964 + 390.67050646 * $k - 0.0016528 * $T2 - 0.00000239 * $T3;
	$C1  = ( 0.1734 - 0.000393 * $T ) * sin( $M * $dr ) + 0.0021 * sin( 2 * $dr * $M );
	$C1 -= 0.4068 * sin( $Mpr * $dr ) - 0.0161 * sin( $dr * 2 * $Mpr );
	$C1 -= 0.0004 * sin( $dr * 3 * $Mpr );
	$C1 += 0.0104 * sin( $dr * 2 * $F ) - 0.0051 * sin( $dr * ( $M + $Mpr ) );
	$C1 -= 0.0074 * sin( $dr * ( $M - $Mpr ) ) - 0.0004 * sin( $dr * ( 2 * $F + $M ) );
	$C1 -= 0.0004 * sin( $dr * ( 2 * $F - $M ) ) + 0.0006 * sin( $dr * ( 2 * $F + $Mpr ) );
	$C1 += 0.0010 * sin( $dr * ( 2 * $F - $Mpr ) ) + 0.0005 * sin( $dr * ( 2 * $Mpr + $M ) );
	if ( $T < -11 ) {
		$deltat = 0.001 + 0.000839 * $T + 0.0002261 * $T2 - 0.00000845 * $T3 - 0.000000081 * $T * $T3;
	} else {
		$deltat = -0.000278 + 0.000265 * $T + 0.000262 * $T2;
	}
	return $Jd1 + $C1 - $deltat;
}

function mld_sun_longitude( $jdn ) {
	$T  = ( $jdn - 2451545.0 ) / 36525;
	$T2 = $T * $T;
	$dr = M_PI / 180;
	$M  = 357.52910 + 35999.05030 * $T - 0.0001559 * $T2 - 0.00000048 * $T * $T2;
	$L0 = 280.46645 + 36000.76983 * $T + 0.0003032 * $T2;
	$DL = ( 1.914600 - 0.004817 * $T - 0.000014 * $T2 ) * sin( $dr * $M );
	$DL = $DL + ( 0.019993 - 0.000101 * $T ) * sin( $dr * 2 * $M ) + 0.000290 * sin( $dr * 3 * $M );
	$L  = $L0 + $DL;
	$L  = $L * $dr;
	$L  = $L - M_PI * 2 * ( floor( $L / ( M_PI * 2 ) ) );
	return $L;
}

function mld_get_sun_longitude( $day_number, $tz ) {
	return (int) floor( mld_sun_longitude( $day_number - 0.5 - $tz / 24 ) / M_PI * 6 );
}

function mld_get_new_moon_day( $k, $tz ) {
	return (int) floor( mld_new_moon( $k ) + 0.5 + $tz / 24 );
}

function mld_get_lunar_month11( $yy, $tz ) {
	$off = mld_jd_from_date( 31, 12, $yy ) - 2415021;
	$k   = (int) floor( $off / 29.530588853 );
	$nm  = mld_get_new_moon_day( $k, $tz );
	$sun_long = mld_get_sun_longitude( $nm, $tz );
	if ( $sun_long >= 9 ) {
		$nm = mld_get_new_moon_day( $k - 1, $tz );
	}
	return $nm;
}

function mld_get_leap_month_offset( $a11, $tz ) {
	$k    = (int) floor( ( $a11 - 2415021.076998695 ) / 29.530588853 + 0.5 );
	$last = 0;
	$i    = 1;
	$arc  = mld_get_sun_longitude( mld_get_new_moon_day( $k + $i, $tz ), $tz );
	do {
		$last = $arc;
		$i++;
		$arc = mld_get_sun_longitude( mld_get_new_moon_day( $k + $i, $tz ), $tz );
	} while ( $arc != $last && $i < 14 );
	return $i - 1;
}

/**
 * Chuyển 1 ngày dương lịch sang âm lịch.
 * @return array [ngày âm, tháng âm, năm âm, có phải tháng nhuận (0/1)]
 */
function mld_solar_to_lunar( $dd, $mm, $yy, $tz = 7 ) {
	$day_number  = mld_jd_from_date( $dd, $mm, $yy );
	$k           = (int) floor( ( $day_number - 2415021.076998695 ) / 29.530588853 );
	$month_start = mld_get_new_moon_day( $k + 1, $tz );
	if ( $month_start > $day_number ) {
		$month_start = mld_get_new_moon_day( $k, $tz );
	}
	$a11 = mld_get_lunar_month11( $yy, $tz );
	$b11 = $a11;
	if ( $a11 >= $month_start ) {
		$lunar_year = $yy;
		$a11        = mld_get_lunar_month11( $yy - 1, $tz );
	} else {
		$lunar_year = $yy + 1;
		$b11        = mld_get_lunar_month11( $yy + 1, $tz );
	}
	$lunar_day  = $day_number - $month_start + 1;
	$diff       = (int) floor( ( $month_start - $a11 ) / 29 );
	$lunar_leap = 0;
	$lunar_month = $diff + 11;
	if ( $b11 - $a11 > 365 ) {
		$leap_month_diff = mld_get_leap_month_offset( $a11, $tz );
		if ( $diff >= $leap_month_diff ) {
			$lunar_month = $diff + 10;
			if ( $diff === $leap_month_diff ) {
				$lunar_leap = 1;
			}
		}
	}
	if ( $lunar_month > 12 ) {
		$lunar_month -= 12;
	}
	if ( $lunar_month >= 11 && $diff < 4 ) {
		$lunar_year -= 1;
	}
	return array( $lunar_day, $lunar_month, $lunar_year, $lunar_leap );
}

/* ------------------------------------------------------------------
 * C. Can Chi, Trực, Hoàng đạo / Hắc đạo, Giờ hoàng đạo
 * ------------------------------------------------------------------ */
function mld_can_list() { return array( 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý' ); }
function mld_chi_list() { return array( 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi' ); }
function mld_truc_list() { return array( 'Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế' ); }
function mld_star_list() {
	return array(
		array( 'Thanh Long', true ), array( 'Minh Đường', true ), array( 'Thiên Hình', false ), array( 'Chu Tước', false ),
		array( 'Kim Quỹ', true ), array( 'Kim Đường', true ), array( 'Bạch Hổ', false ), array( 'Ngọc Đường', true ),
		array( 'Thiên Lao', false ), array( 'Huyền Vũ', false ), array( 'Tư Mệnh', true ), array( 'Câu Trần', false ),
	);
}
function mld_gio_chi_list() {
	return array(
		'Tý (23h-1h)', 'Sửu (1h-3h)', 'Dần (3h-5h)', 'Mão (5h-7h)', 'Thìn (7h-9h)', 'Tỵ (9h-11h)',
		'Ngọ (11h-13h)', 'Mùi (13h-15h)', 'Thân (15h-17h)', 'Dậu (17h-19h)', 'Tuất (19h-21h)', 'Hợi (21h-23h)',
	);
}

function mld_can_chi_day( $jd ) {
	$can_list = mld_can_list();
	$chi_list = mld_chi_list();
	$can = $can_list[ ( $jd + 9 ) % 10 ];
	$chi = $chi_list[ ( $jd + 1 ) % 12 ];
	return $can . ' ' . $chi;
}

function mld_can_chi_day_index( $jd ) {
	return array( ( $jd + 9 ) % 10, ( $jd + 1 ) % 12 );
}

function mld_can_chi_year( $lunar_year ) {
	$can_list = mld_can_list();
	$chi_list = mld_chi_list();
	$can = $can_list[ ( $lunar_year + 6 ) % 10 ];
	$chi = $chi_list[ ( $lunar_year + 8 ) % 12 ];
	return $can . ' ' . $chi;
}

function mld_can_chi_month( $lunar_month, $lunar_year ) {
	$can_list = mld_can_list();
	$chi_list = mld_chi_list();
	$can = $can_list[ ( $lunar_year * 12 + $lunar_month + 3 ) % 10 ];
	$chi = $chi_list[ ( $lunar_month + 1 ) % 12 ];
	return $can . ' ' . $chi;
}

/** Trực của ngày (12 Kiến Trừ). */
function mld_get_truc( $jd, $lunar_month ) {
	list( , $day_chi_idx ) = mld_can_chi_day_index( $jd );
	$kien_chi_idx = ( $lunar_month + 1 ) % 12;
	$truc_idx = ( ( $day_chi_idx - $kien_chi_idx ) % 12 + 12 ) % 12;
	return mld_truc_list()[ $truc_idx ];
}

/** Ngày Hoàng đạo / Hắc đạo (theo tháng âm lịch). */
function mld_get_hoang_dao_ngay( $jd, $lunar_month ) {
	list( , $day_chi_idx ) = mld_can_chi_day_index( $jd );
	$start_chi_idx = ( 2 * ( ( $lunar_month - 1 ) % 6 ) ) % 12;
	$star_idx = ( ( $day_chi_idx - $start_chi_idx ) % 12 + 12 ) % 12;
	$star = mld_star_list()[ $star_idx ];
	return array(
		'name'      => $star[0],
		'hoang_dao' => $star[1],
	);
}

/** Danh sách 12 giờ trong ngày kèm Hoàng đạo/Hắc đạo (theo ngày Chi). */
function mld_get_gio_hoang_dao( $jd ) {
	list( , $day_chi_idx ) = mld_can_chi_day_index( $jd );
	$start_chi_idx = ( 2 * ( $day_chi_idx % 6 ) ) % 12;
	$stars    = mld_star_list();
	$gio_list = mld_gio_chi_list();
	$out = array();
	for ( $hour_chi_idx = 0; $hour_chi_idx < 12; $hour_chi_idx++ ) {
		$star_idx = ( ( $hour_chi_idx - $start_chi_idx ) % 12 + 12 ) % 12;
		$star = $stars[ $star_idx ];
		$out[] = array(
			'gio'       => $gio_list[ $hour_chi_idx ],
			'sao'       => $star[0],
			'hoang_dao' => $star[1],
		);
	}
	return $out;
}

/* ------------------------------------------------------------------
 * D. Tiết khí (24 tiết khí theo kinh độ Mặt Trời, đối chiếu bảng quản trị site cũ:
 *    mục "Lịch tam tông miếu > Tiết khí" — mã Code + tên Vi/En/Fr).
 * ------------------------------------------------------------------ */
function mld_sun_longitude_deg( $day_number, $tz = 7 ) {
	$rad = mld_sun_longitude( $day_number - 0.5 - $tz / 24 );
	return $rad * 180 / M_PI;
}

/** 24 tiết khí, đúng thứ tự bắt đầu từ Lập Xuân (kinh độ Mặt Trời 315°), mỗi tiết cách nhau 15°. */
function mld_tiet_khi_list() {
	return array(
		array( 'code' => 'LapXuan',    'vi' => 'Lập Xuân',    'en' => 'Beginning of Spring',    'fr' => 'Début du Printemps' ),
		array( 'code' => 'VuThuy',     'vi' => 'Vũ Thủy',     'en' => 'Rain Water',              'fr' => 'Eau de Pluie' ),
		array( 'code' => 'KinhTrap',   'vi' => 'Kinh Trập',   'en' => 'Awakening of Insects',    'fr' => 'Réveil des Insectes' ),
		array( 'code' => 'XuanPhan',   'vi' => 'Xuân Phân',   'en' => 'Spring Equinox',          'fr' => 'Équinoxe de Printemps' ),
		array( 'code' => 'ThanhMinh',  'vi' => 'Thanh Minh',  'en' => 'Pure Brightness',         'fr' => 'Clarté Pure' ),
		array( 'code' => 'CocVu',      'vi' => 'Cốc Vũ',      'en' => 'Grain Rain',              'fr' => 'Pluie des Grains' ),
		array( 'code' => 'LapHa',      'vi' => 'Lập Hạ',      'en' => 'Beginning of Summer',     'fr' => "Début de l'Été" ),
		array( 'code' => 'TieuMan',    'vi' => 'Tiểu Mãn',    'en' => 'Lesser Fullness of Grain','fr' => 'Moindre Plénitude des Grains' ),
		array( 'code' => 'MangChung',  'vi' => 'Mang Chủng',  'en' => 'Grain in Ear',            'fr' => 'Épi de Grain' ),
		array( 'code' => 'HaChi',      'vi' => 'Hạ Chí',      'en' => 'Summer Solstice',         'fr' => "Solstice d'Été" ),
		array( 'code' => 'TieuThu',    'vi' => 'Tiểu Thử',    'en' => 'Lesser Heat',             'fr' => 'Petite Chaleur' ),
		array( 'code' => 'DaiThu',     'vi' => 'Đại Thử',     'en' => 'Greater Heat',            'fr' => 'Grande Chaleur' ),
		array( 'code' => 'LapThu',     'vi' => 'Lập Thu',     'en' => 'Beginning of Autumn',     'fr' => "Début de l'Automne" ),
		array( 'code' => 'XuThu',      'vi' => 'Xử Thử',      'en' => 'Limit of Heat',           'fr' => 'Fin de la Chaleur' ),
		array( 'code' => 'BachLo',     'vi' => 'Bạch Lộ',     'en' => 'White Dew',               'fr' => 'Rosée Blanche' ),
		array( 'code' => 'ThuPhan',    'vi' => 'Thu Phân',    'en' => 'Autumn Equinox',          'fr' => "Équinoxe d'Automne" ),
		array( 'code' => 'HanLo',      'vi' => 'Hàn Lộ',      'en' => 'Cold Dew',                'fr' => 'Rosée Froide' ),
		array( 'code' => 'SuongGiang', 'vi' => 'Sương Giáng', 'en' => "Frost's Descent",         'fr' => 'Descente des Gelées' ),
		array( 'code' => 'LapDong',    'vi' => 'Lập Đông',    'en' => 'Beginning of Winter',     'fr' => "Début de l'Hiver" ),
		array( 'code' => 'TieuTuyet',  'vi' => 'Tiểu Tuyết',  'en' => 'Lesser Snow',             'fr' => 'Petite Neige' ),
		array( 'code' => 'DaiTuyet',   'vi' => 'Đại Tuyết',   'en' => 'Greater Snow',            'fr' => 'Grande Neige' ),
		array( 'code' => 'DongChi',    'vi' => 'Đông Chí',    'en' => 'Winter Solstice',         'fr' => "Solstice d'Hiver" ),
		array( 'code' => 'TieuHan',    'vi' => 'Tiểu Hàn',    'en' => 'Lesser Cold',             'fr' => 'Petit Froid' ),
		array( 'code' => 'DaiHan',     'vi' => 'Đại Hàn',     'en' => 'Greater Cold',            'fr' => 'Grand Froid' ),
	);
}

function mld_tiet_khi_index( $dd, $mm, $yy, $tz = 7 ) {
	$jd  = mld_jd_from_date( $dd, $mm, $yy );
	$deg = mld_sun_longitude_deg( $jd, $tz );
	$off = fmod( ( $deg - 315 + 360 ), 360 );
	return (int) floor( $off / 15 );
}

/** Tiết khí bao trùm 1 ngày dương lịch, kèm cờ is_start (true nếu đúng ngày tiết khí bắt đầu). */
function mld_get_tiet_khi( $dd, $mm, $yy, $tz = 7 ) {
	$idx  = mld_tiet_khi_index( $dd, $mm, $yy, $tz );
	$list = mld_tiet_khi_list();
	$jd_prev = mld_jd_from_date( $dd, $mm, $yy ) - 1;
	list( $pd, $pm, $py ) = mld_jd_to_date( $jd_prev );
	$prev_idx = mld_tiet_khi_index( $pd, $pm, $py, $tz );
	$item = $list[ $idx ];
	$item['is_start'] = ( $prev_idx !== $idx );
	return $item;
}

/**
 * Trả về toàn bộ thông tin âm lịch + Can Chi + Trực + Hoàng đạo + Tiết khí cho 1 ngày dương lịch.
 */
function mld_get_day_info( $dd, $mm, $yy ) {
	$jd = mld_jd_from_date( $dd, $mm, $yy );
	list( $lday, $lmonth, $lyear, $lleap ) = mld_solar_to_lunar( $dd, $mm, $yy );
	$truc      = mld_get_truc( $jd, $lmonth );
	$hoang_dao = mld_get_hoang_dao_ngay( $jd, $lmonth );
	$info = array(
		'duong'        => array( 'd' => $dd, 'm' => $mm, 'y' => $yy ),
		'am'           => array( 'd' => $lday, 'm' => $lmonth, 'y' => $lyear, 'nhuan' => (bool) $lleap ),
		'can_chi_ngay' => mld_can_chi_day( $jd ),
		'can_chi_thang' => mld_can_chi_month( $lmonth, $lyear ),
		'can_chi_nam'  => mld_can_chi_year( $lyear ),
		'truc'         => $truc,
		'hoang_dao'    => $hoang_dao,
		'gio_hoang_dao'=> mld_get_gio_hoang_dao( $jd ),
		'tiet_khi'     => mld_get_tiet_khi( $dd, $mm, $yy ),
	);

	// Neu Admin da nhap ghi de tay cho ngay nay (qua trang wp-admin "Ghi de Lich"),
	// uu tien dung gia tri do thay vi cong thuc tu dong tinh o tren. Xem functions.php
	// muc 10 (mld_apply_lich_override) - chi ghi de o nao Admin co nhap, cac o con
	// lai van giu nguyen ket qua cong thuc.
	if ( function_exists( 'mld_apply_lich_override' ) ) {
		$info = mld_apply_lich_override( $info, $dd, $mm, $yy );
	}

	return $info;
}
