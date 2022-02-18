<?php

use Service\Db;

// get item
$app->get('/tes_2/get_item', function ($request, $response) {
    $params = $request->getParams();
    $db = Db::db();
    $menu = $db->select("id, kode,nama as item")
               ->from("m_item");

    $result['detail'] = $menu->findAll();

    $result['total_data'] = $menu->count();

    echo json_encode($result);
});


// get cabang
$app->get('/tes_2/get_cabang', function ($request, $response) {
	$params = $request->getParams();
    $db = Db::db();
    $menu = $db->select("id, nama as cabang")
               ->from("m_cabang");


    $result['detail'] = $menu->findAll();

    $result['total_data'] = $menu->count();

    echo json_encode($result);
});

// get gudang
$app->get('/tes_2/get_gudang', function ($request, $response) {
	$params = $request->getParams();
    $db = Db::db();
    $gudang = $db->select("id, nama as gudang")
               ->from("m_gudang");

    $gudang = (isset($params['cabang']) && !empty($params['cabang'])) ? $db->andwhere('cabang_id', '=', $params['cabang']) : $gudang;
    

    $result['detail'] = $gudang->findAll();

    $result['total_data'] = $gudang->count();

    echo json_encode($result);
});

// get data invt
$app->get('/tes_2/kartu_stok', function ($request, $response) {
	$params = $request->getParams();
    $db = Db::db();
    $data_stok = $db->select("inv_kartu_stok.kode as no_referensi,
    					   inv_kartu_stok.tanggal,
    					   inv_kartu_stok.jenis_stok,
    					   inv_kartu_stok.jumlah_masuk,
    					   inv_kartu_stok.harga_masuk,
    					   inv_kartu_stok.jumlah_keluar,
    					   inv_kartu_stok.harga_keluar,
    					   inv_kartu_stok.catatan as keterangan,
    					   m_item.nama as produk,
    					   m_item.kode as kode_produk")
               ->from("inv_kartu_stok")
               ->leftJoin("m_item", "m_item.id = inv_kartu_stok.m_item_id")
               ->orderBy("inv_kartu_stok.tanggal ASC");

    $data_stok = (isset($params['item']) && !empty($params['item'])) ? $db->where('inv_kartu_stok.m_item_id', '=', $params['item']) : $data_stok;

    $data_stok = (isset($params['gudang']) && !empty($params['gudang'])) ? $db->andwhere('inv_kartu_stok.m_gudang_id', '=', $params['gudang']) : $data_stok;
    $data_stok = (isset($params['periode_awal']) && !empty($params['periode_awal'])) ? $db->andwhere('inv_kartu_stok.tanggal', '>=', $params['periode_awal']) : $data_stok;
    $data_stok = (isset($params['periode_akhir']) && !empty($params['periode_akhir'])) ? $db->andwhere('inv_kartu_stok.tanggal', '<=', $params['periode_akhir']) : $data_stok;
    $data_stok = (isset($params['periode_before']) && !empty($params['periode_before'])) ? $db->andwhere('inv_kartu_stok.tanggal', '<', $params['periode_before']) : $data_stok;


    $result['detail'] = $data_stok->findAll();

    $result['total_data'] = $data_stok->count();

    echo json_encode($result);
});