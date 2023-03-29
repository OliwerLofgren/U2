<?php 
ini_set("display_errors", 1);
require_once("functions.php");
?>


<?php 

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "OPTIONS"){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: *");
    exit();

} else {
    header("Access-Control-Allow-Origin: *");
}

$filename = "api/users.json";
$users = [];
$user_json = file_get_contents($filename);
$users = json_decode($user_json, true);

$request_json = file_get_contents("php://input");
$request_data = json_decode($request_json, true);

if ($request_method == "POST") {
    if(!file_exists($filename)){
        file_put_contents($filename, $users);
    }else{
        $users = json_decode(file_get_contents($filename), true);
    }
    $username = $request_data["username"];
    $password = $request_data["password"];

    foreach($users as $user){
        if ($user["username"] == $username) {
            $error = ["error" => "Conflict! Username is already taken, please try again!"];
            sendJSON($error, 409);
        }
    }

    if ($password == "" or $username == "") {
        $error = ["error" => "Please type a Username and Password"];
        sendJSON($error, 400);
    }
    
    $new_user = [
        "username" => $username,
        "password" => $password,
        "points" => 1
    ];

    $users[] = $new_user;
    $user_json = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents($filename, $user_json);

    sendJSON($new_user);

}
?>