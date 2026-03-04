<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

    if($anime->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Anime berhasil dihapus dari watchlist."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Gagal menghapus anime."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID anime tidak boleh kosong."));
}
?>