<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Anime.php';

$database = new Database();
$db = $database->getConnection();
$anime = new Anime($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->judul)) {
    $anime->judul = $data->judul;
    $anime->episode_total = $data->episode_total ?? null;
    $anime->episode_nonton = $data->episode_nonton ?? 0;
    $anime->status = $data->status ?? 'Plan to Watch';
    $anime->rating = $data->rating ?? null;

    if($anime->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Anime berhasil ditambahkan ke watchlist."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Gagal menambahkan anime."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Data tidak lengkap. Judul wajib diisi."));
}
?>