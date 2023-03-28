<?php

ini_set("dispaly_errors", 1);

require_once "function.php";

$filename = "points.json";
$data = ["points" => 0];

if (!file_exists($filename)) {
    $json = json_encode($data);
    file_put_contents($filename, $json);
}else{
    $json = file_get_contents($filename);
    $data = json_decode($json, true);
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {

    if (isset($_POST["points"])) {

        $points = $_POST["points"];
        $data["points"] += $points;

    }

    file_put_contents($filename, $data);
    sendJSON($data);
}
$error = ["error" => "Can't load points"];
sendJSON($error, 400);


?>