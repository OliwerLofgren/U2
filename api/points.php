<?php
ini_set("dispaly_errors", 1);

require_once "functions.php";

$method = $_SERVER["REQUEST_METHOD"];
$filename = "api/users.json";


$inputData = json_decode(file_get_contents("php://input"), true);

$user_json = file_get_contents($filename);
$user_data = json_decode($user_json, true);


if ($method == "POST") {

    $username = $inputData["username"];
    $password = $inputData["password"];

    for($i = 0; $i < count($user_data); $i++){

        if ($user_data[$i]["username"] == $username) {
            $user_data[$i]["points"] = $user_data[$i]["points"] + $inputData["points"];
            file_put_contents($filename, json_encode($user_data, JSON_PRETTY_PRINT));
            sendJSON(["points" => $user_data[$i]["points"]]);
        }
    }

} 


if ($method == "GET") {

        // Define the comparison function
    function compare($a, $b) {
        if ($a["points"] == $b["points"]) {
            return 0;
        }
        return ($a["points"] > $b["points"]) ? -1 : 1;
    }

    // Sort the array using the comparison function
    usort($user_data, "compare");

    // Shorten and send the sorted array
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