<?php ini_set("display_errors", 1); 
require_once("functions.php");
?>
<?php 
$filename = "api/users.json";
$users = [];
$user_json = file_get_contents($filename);
$users = json_decode($user_json, true);

$request_json = file_get_contents("php://input");
$request_data = json_decode($request_json, true);
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "POST") {
    $username = $request_data["username"];
    $password = $request_data["password"];
    
    foreach($users as $user){
        if ($user["username"] == $username && $user["password"] == $password ) {
            
            $points = $user["points"];
            $loggedInUser = [
                "username" => $username,
                "password" => $password,
                "points" => $points
            ];

            $users[] = $loggedInUser;
            // $user_json = json_encode($users, JSON_PRETTY_PRINT);
            // file_get_contents($filename, $user_json);
            sendJSON($loggedInUser);
                
        }else {
                $message = ["message" => "Please register!"];
                sendJSON($message, 404);
        }
    }
   
}
?>