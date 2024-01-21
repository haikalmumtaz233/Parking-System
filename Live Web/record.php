<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';
include_once 'parkir.php';

$database = new Database();
$db = $database->getConnection();

$item = new Parkir($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The request is using the POST method
    $data = json_decode(file_get_contents("php://input"));
    $item->slot = $data->slot;
    $item->status = $data->status;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // The request is using the GET method
    $item->slot = isset($_GET['slot']) ? $_GET['slot'] : die('wrong structure!');
    $item->status = isset($_GET['status']) ? $_GET['status'] : die('wrong structure!');
} else {
    die('wrong request method');
}

if ($item->createLogData()) {
    echo 'Data created successfully.';
} else {
    echo 'Data could not be created.';
}
