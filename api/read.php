<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Anime.php';

$database = new Database();
$db = $database->getConnection();
$anime = new Anime($db);

$stmt = $anime->read();
$num = $stmt->rowCount();

if($num > 0) {
    $anime_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $anime_item = array(
            "id" => $id,
            "judul" => $judul,
            "episode_total" => $episode_total,
            "episode_nonton" => $episode_nonton,
            "status" => $status,
            "rating" => $rating,
            "updated_at" => $updated_at
        );
        array_push($anime_arr, $anime_item);
    }
    http_response_code(200);
    echo json_encode($anime_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Data tidak ditemukan."));
}
?>