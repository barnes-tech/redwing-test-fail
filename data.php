<?php
//handle response
function json_response($response) {
      header("Access-Control-Allow-Origin: * ");
      header("Content-Type: application/json; charset=UTF-8");
      http_response_code(200);
      echo json_encode($response);
      exit;
    }

$mcuFilms = json_decode(file_get_contents('mcu.json'));



//get characters for select box
$allCharacters = [];
foreach($mcuFilms as $film) {
  $chars = $film->characters;
  if(is_array($chars)) {
    foreach($chars as $character) {
      if(!in_array($character,$allCharacters)) {
        $allCharacters[]=$character;
      }
    }
  }
}

//sort by character
if(isset($_POST['character'])) {
  $resp = [
        'success' => false,
        'msg' => 'Something went wrong.'
  ];
  $character = $_POST['character'];
  //check for show all
  if($character != 'all') {
    //emptyfiltered results array
    $filteredFilms = [];
    foreach($mcuFilms as $film ) {
      //get character array from unfiltered result
      $starring = $film->characters;
      //check if character exists in array
      if(in_array($character, $starring)) {
        //if character in character array add film to filtered films
        $filteredFilms[] = $film;
      }
      //response for selected character
      $resp = [
        'success'=>true,
        'data' => $filteredFilms
      ];
    }
  } else {
    //response for all films
  $resp = [
    'success'=>true,
    'data' => $mcuFilms
  ];
  }
json_response($resp);
}
