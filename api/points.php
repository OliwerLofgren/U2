<?php 
ini_set("dispaly_errors", 1);
require_once "functions.php";
?>

<?php

$filename = "api/users.json";

if (!file_exists($filename)) {
    $message = ["message" => $filename . "does not exist!"];
    sendJSON($message, 404);
}

$request_method = $_SERVER["REQUEST_METHOD"];
$allowed_methods = ["POST", "GET"];

if (!in_array($request_method, $allowed_methods)) {
    $message = ["message" => "Invalid HTTP method!"];
    sendJSON($message, 405);
}

$input_data = json_decode(file_get_contents("php://input"), true);
$user_json = file_get_contents($filename);
$user_data = json_decode($user_json, true);


if ($request_method == "POST") {

    $username = $input_data["username"];
    $password = $input_data["password"];

    for($i = 0; $i < count($user_data); $i++){

        if ($user_data[$i]["username"] == $username) {
            $user_data[$i]["points"] = $user_data[$i]["points"] + $input_data["points"];

            file_put_contents($filename, json_encode($user_data, JSON_PRETTY_PRINT));
            sendJSON(["points" => $user_data[$i]["points"]]);
        }
    }
    $message = ["message" => "User not found!"];
    sendJSON($message, 404);
} 


if ($request_method == "GET") {

    
    function compare($a, $b) {
        if ($a["points"] == $b["points"]) {
            return 0;
        }
        return ($a["points"] > $b["points"]) ? -1 : 1;
    }
    
    usort($user_data, "compare");
    
    $top_five = array_slice($user_data, 0, 5);

    $user_and_points = [];
    foreach($top_five as $user){
        $user = [
            "username" => $user["username"],
            "points" => $user["points"],
        ];
        $user_and_points[] = $user;
    };

    sendJSON($user_and_points);
}


?>