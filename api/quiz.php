<?php 
ini_set("display_errors", 1);
require_once("functions.php");
?>

<?php 

$filename = 'images/images/';
$dir = scandir($filename);
$dogs_json = "dogs.json";

$array_of_all_the_dogs = [];

foreach($dir as $dog){
    $dogsname = $dog;
    $replace_words = ["_", ".jpg"];
    $new_name = str_replace($replace_words, " ", $dogsname);
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

while (count($alternatives) <4) {
    $i = 0;
    $random = array_rand($array_of_all_the_dogs, 1);
    $new_dog = [
        "name" => $array_of_all_the_dogs[$random]["name"],
        "url" => $array_of_all_the_dogs[$random]["url"],
    ];
    $alternatives[] = $new_dog;
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

function check_answer($image, $dog){
    if (str_contains($image["name"], $dog)) {
        return true;
    } else {
        return false;
    }
}

sendJSON($alt);
?>