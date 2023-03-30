<?php
ini_set("dispaly_errors", 1);

require_once "functions.php";

$method = $_SERVER["REQUEST_METHOD"];
$filename = "api/users.json";


$inputData = json_decode(file_get_contents("php://input"), true);

$json = file_get_contents($filename);
$userDatabase = json_decode($json, true);


if ($method == "POST") {

    $username = $inputData["username"];
    $password = $inputData["password"];

    for($i = 0; $i < count($userDatabase); $i++){

        if ($userDatabase[$i]["username"] == $username) {
            $userDatabase[$i]["points"] = $userDatabase[$i]["points"] + $inputData["points"];
            $newpoints = file_put_contents($filename, json_encode($userDatabase, JSON_PRETTY_PRINT));
            sendJSON(["points" => $userDatabase[$i]["points"]]);
        }
    }

} 


if ($method == "GET") {

        // Define the comparison function
    function cmp($a, $b) {
        if ($a["points"] == $b["points"]) {
            return 0;
        }
        return ($a["points"] > $b["points"]) ? -1 : 1;
    }

    // Sort the array using the comparison function
    usort($userDatabase, "cmp");

    // Shorten and send the sorted array
    $firstFive = array_slice($userDatabase, 0, 5);

    $usernamdeAndPoints = [];
    foreach($firstFive as $user){
        $oneUser = [
            "username" => $user["username"],
            "points" => $user["points"],
        ];
        $usernamdeAndPoints[] = $oneUser;
    };

    sendJSON($usernamdeAndPoints);
}


?>