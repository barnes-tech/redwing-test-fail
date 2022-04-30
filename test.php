<?php

/*
    Test page for Redwing Interactive
    November 2021, Caractacus Downes
    A series of simple tests to assess familiarity and capability with some core languages and techniques.
*/

require_once 'data.php';

function formatList($json) {
  $list = json_decode($json);
  $listString = '';
  foreach($list as $name) {
    $listString.=$name.',';
  }
  $listString = rtrim($listString,',');
  $listString.='.';
  return $listString;
}

/*

For this test you need this file and the supplied mcu.json file in the same directory.

This page lists (some of) the films from the Marvel Cinematic Universe, including their title, director(s), main characters and an image.

Task 1:     Modify the css of the page to show the film blocks in rows across the page, making whatever changes you think will improve the overall appearance of the page.
            The layout should be responsive so that you see an appropriate number of films in a row depending on screen size.

Task 2:     The $mcuFilms php variable holds data about more of the MCU films.  Populate the film blocks from the data in the variable when the page loads instead of having it hard coded.

Task 3:     There are two select boxes at the top of the page.
            One of them should filter the visible films by character (so if you select Iron Man from the select you should only see films in which Iron Man appears).
            The other should sort the films by either year or title.
            Populate the select filter from the $mcuFilms data and make the two selects work.

*/


?>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Redwing Test</title>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Google fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="responsive.css" type="text/css">
    </head>

    <body>

        <div id="divWrapper">

            <header>
                <h1>Marvel Cinematic Universe</h1>
            </header>

            <nav>
                <div>

                    <label for="selCharacterFilter">Filter by Character</label>
                    <select id="selCharacterFilter">
                        <option value="all">Show All</option>
                        <?php foreach($allCharacters as $option):?>
                        <option value="<?=$option?>"><?=$option?></option>
                        <?php endforeach;?>
                    </select>

                    <label for="selSort">Sort</label>
                    <select id="selSort">
                        <option value="year">Year</option>
                        <option value="title">Title</option>
                    </select>

                </div>
            </nav>

            <main class="grid">
<!--Loop through the dataset-->
<?php foreach($mcuFilms as $film):
  $directors = json_encode($film->director);
  $characters = json_encode($film->characters);

  ?>
                <div class="card">

                    <img src="<?=$film->image?>" />

                    <ul>
                        <li><h2><?=$film->title?></h2></li>
                        <li><p><span class="spLabel">Director:</span><span><?=formatList($directors)?></span></p></li>
                        <li><p><span class="spLabel">Characters:</span></span><?=formatList($characters)?></span></p></li>
                        <li><p><span>Year:</span><span><?=$film->year?></span></p></li>
                    </ul>

                </div>
<?php endforeach;?>

            </main>

        </div>

    </body>
    <script>
    //character select

    function sortByTitle(a, b){
      var aTitle = a.title.toLowerCase();
      var bTitle = b.title.toLowerCase();
      return ((aTitle < bTitle) ? -1 : ((aTitle > bTitle) ? 1 : 0));
    }

    function sortByYear(a, b){
      var aYear = a.year.toString();
      var bYear = b.year.toString();
      return ((aYear < bYear) ? -1 : ((aYear > bYear) ? 1 : 0));
    }

      $('select').on('change', function() {
        //get sort value
        let sort = $('#selSort').val()
        let character = $('#selCharacterFilter').val()
        //request data
        jQuery.ajax({
		        url: 'data.php',
		        type: 'POST',
		        data: {
              character: character,
            },
		        success: function(data){
              let results = data.data
              //remove current data
              $('.card').remove();
              //organise the returned data
              results = ((sort=="title") ? results.sort(sortByTitle) : results.sort(sortByYear));
              //populate html
              $.each(results, function(k,v) {
                let title = v.title
                let img = v.image
                let year = v.year
                let director = v.director
                let character = v.characters

                $(".grid").append(
                  `<div class="card">
                  <img src="`+ img +`" />
                  <ul>
                      <li><h2>`+ title +`</h2></li>
                      <li><p><span class="spLabel">Director: </span><span>`+ director +`</span></p></li>
                      <li><p><span class="spLabel">Characters: </span></span>`+ character +`</span></p></li>
                      <li><p><span>Year: </span><span>`+ year +`</span></p></li>
                  </ul>
                  </div>`)
              });

		           },
		       error: function(){
             alert("Something went wrong.")},
	          });
      });
    </script>

</html>
