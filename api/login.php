<?php ini_set("display_errors, 1"); 
require_once("functions.php");
?>
<?php 
$users = [];
$filename = "users.json";

$user_json = file_get_contents($filename);
$users = json_decode($user_json, true);

$requst_method = $_SERVER["REQUEST_METHOD"];

if ($requst_method == "GET") {
    if (isset($users["username"], $users["password"])) {
        $message = ["message" => "Success!"];
        sendJSON($message);
    } else {
        $message = ["message" => "Please register!"]
    }
}
?>