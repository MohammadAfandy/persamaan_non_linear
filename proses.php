<?php

ini_set('serialize_precision', -1);

require_once("Expression.php");

use Expression as MathExpression;

$fungsi = $_POST['fungsi'];
$esilon = $_POST['esilon'];
$selang_a = $_POST['selang_a'];
$selang_b = $_POST['selang_b'];

// $data = [];
// echo json_encode($fungsi);
// hitung metode biseksi (bagi dua)
echo json_encode(biseksiBagiDua($fungsi, $selang_a, $selang_b, $esilon));

// fungsi menghitung menggunakan fungsi tertentu
function biseksiBagiDua($fungsi, $selang_a, $selang_b, $esilon, $count = 1, $data = [])
{
	if (($fungsi != '') && ($selang_a != '') && ($selang_b != '') && ($esilon != '')) {
		// step 2 (hitung fungsi f(a) dan f(b))
		$fungsi_a = hitungFungsi($fungsi, $selang_a);
		$fungsi_b = hitungFungsi($fungsi, $selang_b);

		// step 3 hitung c
		$c = ($selang_a + $selang_b) / 2;

		// step 4 hitung fungsi f(c)
		$fungsi_c = hitungFungsi($fungsi, $c, true);

		$data[$count] = [
			'selang_a' => (string) $selang_a,
			'fungsi_a' => (string) $fungsi_a,
			'selang_b' => (string) $selang_b,
			'fungsi_b' => (string) $fungsi_b,
			'c' => (string) $c,
			'fungsi_c' => (string) $fungsi_c,
		];
		// step 6 cek apakah mutlak f(c) <= esilon
		if (cekMutlakFungsiCSamaEsilon($fungsi_c, $esilon)) {
			// return nilai c jika f(c) <= esilon
			return $data; // stop pengulangan
		} else {
			// step 5 cek apakah f(c) bertanda sama / beda dengan f(a)
			if (cekFungsiCSamaFungsiA($fungsi_c, $fungsi_a)) {
				$selang_a = $c;
			} else {
				$selang_b = $c;
			}

			$count++;

			// jika sudah 10 iterasi tidak ditemukan hasil, maka stop iterasinya
			if ($count > 30) {
				$data[] = "Maximal 30 iterasi sudah dijalankan namun belum menemui hasil yang diinginkan";
				return $data;
			}
			// (recursive) ulang iterasinya sampai kondisi tepenuhi
			return biseksiBagiDua($fungsi, $selang_a, $selang_b, $esilon, $count, $data);
		}
	}
}

// fungsi menghitung menggunakan fungsi tertentu
function hitungFungsi($fungsi, $selang, $debug=false)
{
	// if ($fungsi && $selang) {
		// $math_exp = str_replace("x", $selang, $fungsi);
		$e = new MathExpression();
		$e->evaluate('f(x) = '. $fungsi);
		// if ($debug)
		// 	var_dump($e->evaluate('f(('.$selang.'))'));die();
		return $e->evaluate('f((' . $selang . '))');
	// }

	// return null;
}

// fungsi mengecek apakah tanda f(c) sama dengan tanda f(a)
function cekFungsiCSamaFungsiA($fungsi_c, $fungsi_a)
{
	// if ($fungsi_c && $fungsi_a) {
		if (($fungsi_c < 0 && $fungsi_a < 0) || ($fungsi_c > 0 && $fungsi_a > 0)) {
			return true; // tanda sama
		} else {
			return false; // tanda beda
		}
	// }

	// return null;
}

// fungsi mengecek apakah mutlak f(c) lebih besar dari esilon
function cekMutlakFungsiCSamaEsilon($fungsi_c, $esilon)
{
	// if ($fungsi_c && $esilon) {
		if (abs($fungsi_c) <= $esilon) {
			return true; // stop pengulangan
		} else {
			return false; // masih mengulang
		}
	// }

	// return null;
}

?>