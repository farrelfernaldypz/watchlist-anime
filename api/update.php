<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

if(!empty($data->id)) {
    $anime->id = $data->id;
    $anime->judul = $data->judul;
    $anime->episode_total = $data->episode_total;
    $anime->episode_nonton = $data->episode_nonton;
    $anime->status = $data->status;
    $anime->rating = $data->rating;

    if($anime->update()) {
        http_response_code(200); //
        echo json_encode(array("message" => "Data anime berhasil diperbarui."));
    } else {
        http_response_code(503); 
        echo json_encode(array("message" => "Gagal memperbarui data anime."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID anime tidak boleh kosong."));
}
?>