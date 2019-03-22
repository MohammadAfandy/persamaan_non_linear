<?php
require_once("Expression.php");

use Expression as MathExpression;

/* =============================================================================================== */
/* ====================================== START FUNCTION ========================================= */
/* =============================================================================================== */

// fungsi menghitung menggunakan metode biseksi bagi dua
function hitungPnl($metode, $fungsi, $selang_a, $selang_b, $esilon, $count = 1, $data = [])
{
	if (!empty($metode) && ($fungsi != '') && ($selang_a != '') && ($selang_b != '') && ($esilon != '')) {
		// step 2 (hitung fungsi f(a) dan f(b))
		$fungsi_a = hitungFungsi($fungsi, $selang_a);
		$fungsi_b = hitungFungsi($fungsi, $selang_b);

		// step 3 hitung c berdasarkan metode yang digunakan
		if ($metode == 'biseksi') {
			$c = ($selang_a + $selang_b) / 2;	
		} else if ($metode == 'regula') {
			$c = $selang_b - (($fungsi_b / ($fungsi_b - $fungsi_a)) * ($selang_b - $selang_a));
		}

		// step 4 hitung fungsi f(c)
		$fungsi_c = hitungFungsi($fungsi, $c);

		// isi variable data untuk ditampilkan di aplikasi
		$data[$count] = [
			'selang_a' => (string) $selang_a,
			'fungsi_a' => (string) $fungsi_a,
			'selang_b' => (string) $selang_b,
			'fungsi_b' => (string) $fungsi_b,
			'c' => (string) $c,
			'fungsi_c' => (string) $fungsi_c,
			'tanda' => cekFungsiCSamaFungsiA($fungsi_c, $fungsi_a) ? "Bertanda Sama" : "Bertanda Beda",
			'memenuhi' => cekMutlakFungsiCSamaEsilon($fungsi_c, $esilon) ? "Sudah Memenuhi" : "Belum Memenuhi",
		];

		// step 6 cek apakah mutlak f(c) <= esilon
		if (cekMutlakFungsiCSamaEsilon($fungsi_c, $esilon)) {
			return $data; // stop pengulangan
		} else {
			// step 5 cek apakah f(c) bertanda sama atau beda dengan f(a)
			if (cekFungsiCSamaFungsiA($fungsi_c, $fungsi_a)) {
				$selang_a = $c; // bertanda sama
			} else {
				$selang_b = $c; // bertanda beda
			}

			$count++; // hitungan iterasi menambah

			// jika sudah 30 iterasi tidak ditemukan hasil, maka stop iterasinya
			if ($count > 30) {
				$data[] = "Maximal 30 iterasi sudah dijalankan namun belum menemui hasil yang diinginkan";
				return $data;
			}
			
			// (recursive) ulang iterasinya dengan parameter baru sampai kondisi tepenuhi
			return hitungPnl($metode, $fungsi, $selang_a, $selang_b, $esilon, $count, $data);
		}
	}
}

// fungsi menghitung menggunakan fungsi tertentu
function hitungFungsi($fungsi, $x)
{
	$e = new MathExpression();
	$e->evaluate('f(x) = '. $fungsi);
	return $e->evaluate('f((' . $x . '))');
}

// fungsi mengecek apakah tanda f(c) sama dengan tanda f(a)
function cekFungsiCSamaFungsiA($fungsi_c, $fungsi_a)
{
	if (($fungsi_c < 0 && $fungsi_a < 0) || ($fungsi_c > 0 && $fungsi_a > 0)) {
		return true; // tanda sama
	} else {
		return false; // tanda beda
	}
}

// fungsi mengecek apakah mutlak f(c) lebih besar dari esilon
function cekMutlakFungsiCSamaEsilon($fungsi_c, $esilon)
{
	// jika mutlak f(c) <= esilon maka stop pengulangan
	if (abs($fungsi_c) <= $esilon) {
		return true; // stop pengulangan
	} else {
		return false; // masih mengulang
	}
}

/* =============================================================================================== */
/* ======================================== END FUNCTION ========================================= */
/* =============================================================================================== */

$metode = $_POST['metode'];
$fungsi = $_POST['fungsi'];
$esilon = $_POST['esilon'];
$selang_a = $_POST['selang_a'];
$selang_b = $_POST['selang_b'];

// hitung Persamaan Non Linear Berdasarkan Parameter Inputan
echo json_encode(hitungPnl($metode, $fungsi, $selang_a, $selang_b, $esilon));
