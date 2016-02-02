<?php
require dirname(__FILE__) . "/query_sparql.php";
include_all_php("/API_SPARQL");

function showImage($name){
	
	
	$file= 'http://localhost/git/CookingBot/img/recipes/'.$name.'.jpg';
	//echo $file;
    /*if (file_exists($file) == false) {
		//non esiste foto, non mostro nulla
		echo $file.'<br>Nessuna Immagine<br>';
    }
	else {  */
		echo '<img src="'. $file. '" alt="'. $name. '" style="max-height: 500px; max-width: 500px;"/><br>';
    //}
}

//stampa le info
function printInfos($originalserves,$serves,$cuisine="",$course="",$occasion="",$diet=""){
	echo "<h4>";
	echo "Recipe for <b>".$serves."</b> people";
	if($originalserves!=$serves)
		echo " (originally for ".$originalserves.")";
	echo "<br>";
	if($cuisine!=""){
		echo "Cuisine: <b>".$cuisine."</b><br>";
	}
	if($course!=""){
		echo "Course: <b>".$course."</b><br>";
	}
	if($occasion!=""){
		echo "Occasion: <b>".$occasion."</b><br>";
	}
	if($diet!=""){
		echo "Diet: <b>".$diet."</b><br>";
	}
	echo "</h4>";
}

//stampa gli ingredienti, nel caso si visualizza per il numero di persone della ricetta
function printIngredients($recipeURI, $liquidMeasure, $solidMeasure, $lang){
	$ingredients = getRecipeIngredients($recipeURI, $lang);
	if(!empty($ingredients))
	{
		
		echo '<h3>INGREDIENTS</h3>';
		echo "<h4>";
		foreach ($ingredients as $key => $value){
			echo $key." ";
			if(array_key_exists($liquidMeasure,$value)){
				echo $value[$liquidMeasure];
			}
			else{
				if(array_key_exists($solidMeasure,$value)){
					echo $value[$solidMeasure];
				}
				else{
					if(array_key_exists("unit",$value)){
						echo $value["unit"];
					}
				}
			}
			echo "<br>";
		}
		echo "</h4>";
	}
}

//stampa gli ingredienti, nel caso si visualizza per un numero di persone diverso da quello della ricetta
function printScaledIngredients($recipeURI, $liquidMeasure, $solidMeasure, $lang, $serves){
	$ingredients = getRecipeIngredientsScaled($recipeURI, $lang, $serves);
	if(!empty($ingredients))
	{
		if($serves<2){
			echo '<h3>INGREDIENTS (FOR '.$serves.' PERSON)</h3>';
		}
		else{
			echo '<h3>INGREDIENTS (FOR '.$serves.' PEOPLE)</h3>';
		}
		
		echo "<h4>";
		foreach ($ingredients as $key => $value){
			echo $key." ";
			if(array_key_exists($liquidMeasure,$value)){
				echo $value[$liquidMeasure];
			}
			else{
				if(array_key_exists($solidMeasure,$value)){
					echo $value[$solidMeasure];
				}
				else{
					if(array_key_exists("unit",$value)){
						echo $value["unit"];
					}
				}
			}
			echo "<br>";
		}
		echo "</h4>";
	}
}

//stampa gli step
function printSteps($recipeURI, $lang){
	$steps = getRecipeSteps($recipeURI, $lang);
	echo '<h3>STEPS</h3>';
	echo "<h4>";
	for($i = 0 ; $i<count($steps); $i++){
		$j=$i+1;
		echo $j.") ".$steps[$i]."<br>";
	}
	echo "</h4>";
}

//formatta e invoca i metodi
function getRecipeDetails($name, $originalserves, $serves, $cuisine, $course, $occasion, $diet, $recipeURI, $liquidMeasure, $solidMeasure, $lang,$cooktime,$preptime){
	echo '<h2>'.$name.'</h2>';
	
	//echo $recipeURI;
	$imgPath = str_ireplace("Recipe_","",$recipeURI);
	$imgPath = str_ireplace("_"," ",$imgPath);
	showImage($imgPath);
	
	printInfos($originalserves,$serves,$cuisine,$course,$occasion,$diet);
	if($originalserves==$serves){
		printIngredients($recipeURI, $liquidMeasure, $solidMeasure, $lang);
	}
	else{
		printScaledIngredients($recipeURI, $liquidMeasure, $solidMeasure, $lang, $serves);
	}
	printTime($cooktime,$preptime);
	printSteps($recipeURI, $lang);
	
}

function printTime($cooktime,$preptime)
{
	echo "<div><strong>Cooking time:</strong>".$cooktime."</div>";
	echo "<div><strong>Preparation time:</strong>".$preptime."</div>";
}

//esecuzione vera e propria del codice
	$name = $_POST['name'];
	$lang = $_POST['lang'];
	$originalserves = $_POST['originalserves'];
	$serves = $_POST['serves'];
	$cuisine = $_POST['cuisine'];
	$course = $_POST['course'];
	$occasion = $_POST['occasion'];
	$diet = $_POST['diet'];
	$recipeURI = $_POST['recipeURI'];
	$liquidMeasure = $_POST['liquidMeasure'];
	$solidMeasure = $_POST['solidMeasure'];
	$cooktime = $_POST['cooktime'];					//cooktime
	$preptime = $_POST['preptime'];					//preptime
	getRecipeDetails($name, $originalserves, $serves, $cuisine, $course, $occasion, $diet, $recipeURI, $liquidMeasure, $solidMeasure, $lang,$cooktime,$preptime);
?>