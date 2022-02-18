<?php

function get_data($api_get){
	$api_get = str_replace(' ', '%20', $api_get);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_get);
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result   

	// Fetch and return content, save it.
	$raw_data = curl_exec($ch);
	curl_close($ch);

	// If the API is JSON, use json_decode.
	$data = json_decode($raw_data);
	// var_dump($data);

	return $raw_data;
}

$app->post('/olah_data/get_data_report', function ($request, $response) {
	$param      = $request->getParams();

	$item          = $param['item'];
	$gudang        = $param['gudang'];
	$periode_awal  = $param['periode_awal'];
	$periode_akhir = $param['periode_akhir'];

	$params = "gudang={$gudang}&periode_awal={$periode_awal}&periode_akhir={$periode_akhir}&item={$item}";

	$get_stok_before = json_decode(get_data("http://localhost/training-angular-9/api/tes_2/kartu_stok?gudang={$gudang}&periode_before={$periode_awal}&item={$item}"));

	$get_stok 	= json_decode(get_data("http://localhost/training-angular-9/api/tes_2/kartu_stok?{$params}"));

	$saldo_awal   = [];
	$saldo_global = [];

	$tgl_saldo_awal = str_replace("/", "-", $periode_awal);
	$tgl_saldo_awal = date('Y-m-d', strtotime($tgl_saldo_awal));

	$saldo_awal[0]->jumlah = 0;
	$saldo_awal[0]->harga  = 0;
	$saldo_awal[0]->saldo  = 0;
	$saldo_awal[0]->tanggal = $tgl_saldo_awal;

	foreach ($get_stok_before->detail as $key => $value) {


		if ($value->jenis_stok == 'masuk') {
			$saldo_awal[0]->jumlah += $value->jumlah_masuk;
			$saldo_awal[0]->harga   = $value->harga_masuk;
			$saldo_awal[0]->saldo  += $value->harga_masuk * $value->jumlah_masuk;

			$saldo_global[0]->jumlah += $value->jumlah_masuk;
			$saldo_global[0]->harga   = $value->harga_masuk;
			$saldo_global[0]->saldo  += $value->harga_masuk * $value->jumlah_masuk;

		}else if($value->jenis_stok == 'keluar'){
			$saldo_awal[0]->jumlah -= $value->jumlah_keluar;
			$saldo_awal[0]->harga   = $value->harga_keluar;
			$saldo_awal[0]->saldo  -= $value->harga_keluar * $value->jumlah_keluar;

			$saldo_global[0]->jumlah -= $value->jumlah_keluar;
			$saldo_global[0]->harga   = $value->harga_keluar;
			$saldo_global[0]->saldo  -= $value->harga_keluar * $value->jumlah_keluar;
		}
	}

	$data = [];
	foreach ($get_stok->detail as $key => $value) {
		$data[$key]->no_referensi = $value->no_referensi;
		$data[$key]->tanggal      = $value->tanggal;
		$data[$key]->keterangan   = $value->keterangan;
		$data[$key]->masuk         = [];
		$data[$key]->keluar        = [];
		$data[$key]->saldo         = [];

		if($value->jenis_stok == 'masuk'){
			$data[$key]->masuk['jumlah']        = $value->jumlah_masuk;
			$data[$key]->masuk['harga']         = $value->harga_masuk;
			$data[$key]->masuk['saldo']         = $value->harga_masuk * $value->jumlah_masuk;

			$data[$key]->saldo['jumlah']        = $saldo_global[0]->jumlah + $value->jumlah_masuk;
			$data[$key]->saldo['harga']         = $value->harga_masuk;
			$data[$key]->saldo['saldo']         = $saldo_global[0]->saldo + ($value->harga_masuk * $value->jumlah_masuk);
			$data[$key]->masuk['label']         = 'PCS';

			$saldo_global[0]->jumlah += $value->jumlah_masuk;
			$saldo_global[0]->harga +=  $value->harga_masuk;
			$saldo_global[0]->saldo +=  $value->harga_masuk * $value->jumlah_masuk;

			
		
		}else if($value->jenis_stok == 'keluar'){
			$data[$key]->keluar['jumlah']        = $value->jumlah_keluar;
			$data[$key]->keluar['harga']         = $value->harga_keluar;
			$data[$key]->keluar['saldo']         = $value->harga_keluar * $value->jumlah_keluar;

			$data[$key]->saldo['jumlah']        = $saldo_global[0]->jumlah - $value->jumlah_keluar;
			$data[$key]->saldo['harga']         = $value->harga_keluar;
			$data[$key]->saldo['saldo']         = $saldo_global[0]->saldo - ($value->harga_keluar * $value->jumlah_keluar);
			$data[$key]->keluar['label']         = 'PCS';

			$saldo_global[0]->jumlah -= $value->jumlah_keluar;
			$saldo_global[0]->harga  -= $value->harga_keluar;
			$saldo_global[0]->saldo  -= $value->harga_keluar * $value->jumlah_keluar;
			
		}

	}


	return successResponse($response, [
		'data_master' => $data,
		'saldo_awal'  => $saldo_awal,
		// 'tanggal_awal'=> $get_stok_before
	]);

});
