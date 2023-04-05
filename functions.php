<?php 
function sendJSON($data, $status_code = 200){
    header("Content-Type: application/json");
    http_response_code($status_code);
    $json = json_encode($data);
    echo $json;
    exit();
}
function check_answer($image, $dog){
    if (str_contains($image["name"], $dog)) {
        return true;
    } else {
        return false;
    }
}
?>