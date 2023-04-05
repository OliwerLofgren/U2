<?php 
ini_set("display_errors", 1);
require_once("functions.php");
?>

<?php 
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "GET") {
    $filename = 'images/images/';
    $all_dogs_files = scandir($filename);
    $dogs_json = "dogs.json";
    
    if (count($all_dogs_files) == 0) {
        $message = ["message" => "Images not found!"];
        sendJSON($message, 404);
    }

    $array_of_all_the_dogs = [];
    
    foreach($all_dogs_files as $dog){
        $dog_names = $dog;
        $replace_words = ["_", ".jpg"];
        $new_name = str_replace($replace_words, " ", $dog_names);
        $new_dog = [
            "name" => trim($new_name),
            "url" => $dog,
        ];
        $array_of_all_the_dogs[] = $new_dog;
    }
    $data = json_encode($array_of_all_the_dogs, JSON_PRETTY_PRINT);
    file_put_contents($dogs_json, $data);
    
    array_splice($array_of_all_the_dogs, 0, 2);
    
    $alternatives = [];
    $i = 0;
    
    while (count($alternatives) <4) {
        $random = array_rand($array_of_all_the_dogs, 1);
        $new_dog = [
            "name" => $array_of_all_the_dogs[$random]["name"],
            "url" => $array_of_all_the_dogs[$random]["url"],
        ];
        if(!in_array($new_dog, $alternatives)){
            $alternatives[] = $new_dog;
        }
    
        $i++;
    }
    
    
    $random_image = $alternatives[array_rand($alternatives, 1)];
    
    $names = [];
    
    foreach($alternatives as $dog){
        $names[] = [
            "correct" => check_answer($random_image, $dog["name"]),
            "name" => $dog["name"]
        ];
    }
    
    
    $alt = [
         "image" => "images/images/" . $random_image["url"],
         "alternatives" => $names,
    ];
    
    sendJSON($alt);
}
$message = ["message" => "Wrong kind of method!"];
sendJSON($message, 405);

?>