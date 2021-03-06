<?php
include_once dirname(__FILE__).'/http_API.php';
include_once dirname(__FILE__).'/microsoft_translate_API.php';


function getPrefix(){
	
	return "
	PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	PREFIX owl: <http://www.w3.org/2002/07/owl#>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
	PREFIX comp: <http://www.foodontology.it/ontology#>
	PREFIX fo: <http://purl.org/ontology/fo/>
	PREFIX co: <http://rhizomik.net/ontologies/copyrightonto.owl#>
	PREFIX com: <http://purl.org/commerce#>
	PREFIX food: <http://data.lirmm.fr/ontologies/food#>
	 ";
	
}


/*
/ this function takes a food and return true if the food exists in the ontology, false otherwise

*/
function existsFood($food)
{
	
	$base = getPrefix();
	
	$query = $base . " select ?o where {comp:".$food." rdf:type ?o .
		comp:".$food." rdf:type comp:Food
		}"	;
	
	$res = sparqlQuery($query);
	
	$dom = new DOMDocument;
	$dom->loadXML($res);
	$tr = $dom->getElementsByTagName('results');
	$str = $tr->item(0)->nodeValue;
	
	if(trim($str) == "")
		return false;
	
	
	return true;
	
}

//	TEST
/*
$res = existsFood("tomatoes");
echo $res;
*/




function insertLabel($foodTranslate,$food,$lang)
{
	
	
	$base = getPrefix();
	
	$query = $base . " INSERT DATA { comp:".$foodTranslate." rdfs:label \"".$food."\"@".$lang." }"	;
	
	sparqlUpdate($query);
	
	
}



function existsFoodLabel($label,$lang)
{
	
	$base = getPrefix();
	
	$query = $base . "	SELECT ?uri ?label
						WHERE {
						?uri rdfs:label \"".$label."\"@".$lang." .
						
						}";
	
	//echo 	$query;
	$res = sparqlQuery($query);
	
	$dom = new DOMDocument;
	$dom->loadXML($res);
	$tr = $dom->getElementsByTagName('results');
	if($tr->length == 0) return false;
	
	$str = $tr->item(0)->nodeValue;
	
	if(trim($str) == "")
		return false;
	
	
	return true;
	

	
	
}

//TEST
/*
//In the ontology we have the food "apples" with label "apples"@en
echo existsFoodLabel("apples","en");
*/



/*
*this function return true if the function insert the food in the ontology with success using label or comp:Food 
* otherwise, if the food exists like comp:Food or like label in another languages the function return false
*/
function insertFoodCtrlLang($food,$kilocal,$kilojaul,$shopping)
{	$food = trim($food);
	$food = strtolower($food);
	
	
	$kilocal = trim($kilocal);
	if($kilocal != "")
		$kilocal = strtolower($kilocal);
	
	
	$kilojaul = trim($kilojaul);
	if($kilojaul != "")
		$kilojaul = strtolower($kilojaul);
	
	
	
	$shopping = trim($shopping);
	if($shopping != "")
	$shopping = strtolower(translate($shopping,"it","en"));  //translate shopping category to english
	$shopping = str_ireplace(" ","_",$shopping);
	
	
	
	$from = detectLang($food);
	if($from != "en")
	{
		if($from != "it")
		{
			$from = "it";
		}
	}
	
	
	if(! existsFoodLabel($food,$from))
	{
		//i'm sure, the food don't exists!
		//then i enter the food in the ontology
			
		insertFood($food,$from,$kilocal,$kilojaul,$shopping);
		return true;
			
	}
	else
	{
		// the label exists, we have this food codified in the ontology!!!
		return false;
			
	}
		
	

	
}


/*
* the function insertFood return true if the operation has been succes,
* otherwise, if the Food are alredy in the ontology, the function return false
*/
function insertFood($food,$from,$kilocal,$kilojaul,$shopping)
{	


	$food;
	$labelIT;
	$label;
	$lang = "en";
	$langIT = "it";
	$to;
	
	if($from == "en")
	{
		$to = "it";
		
	}else
	{
		$to = "en";
	}

	
	$foodTranslate = translate($food,$from,$to);
	$foodTranslate = strtolower($foodTranslate);
	
	
	if($from != "en")
	{
		//the food is not in english
			
		$labelIT = $food; // <--- italian translation of food 
		$food = $foodTranslate; // <--- take the food in english
		$label = $foodTranslate;
		
		
		
	}else
	{
		$labelIT = $foodTranslate;
		$label = $food;
	}

	
	
	$food = str_ireplace(" ","_",$food);
	
	$base = getPrefix();
	
	$query = $base . "	INSERT DATA { comp:".$food." rdf:type comp:Food ;
    rdfs:label \"". $label ."\"@".$lang." ;
	 rdfs:label \"".$labelIT."\"@".$langIT ;
	 
	if($kilocal != "")
		 $query .= " ; 
			food:energyPer100g	\"".$kilocal."\"   	

			";
	
	if($kilojaul != "")
		 $query .= " ; 
			food:energyPer100g	\"".$kilojaul."\"   	

			";
	
	
	if($shopping != "")
		 $query .= " ; 
			fo:shopping_category comp:".$shopping." .	

			";
	
	
	$query .=				"}";
	//echo $query;
	sparqlUpdate($query);
	
	
	
	
}

function insertRecipe($name,$food,$numberp,$cuisine,$diet,$occasion,$course,$language)
{	
	echo ' la lingua è '.$language;

	if($language == 'en'){
	
	$nameEN = $name;
	$cuisineEN = $cuisine;
	$dietEN = $diet;
	$occasionEN = $occasion;
	$courseEN = $course;

	$nameIT = translate($name,'en','it');
	if($cuisine != ""){
	$cuisineIT = translate($cuisine,'en','it');
						}
	if($diet != ""){
	$dietIT =  translate($diet,'en','it');
					}
	if($occasion != ""){
	$occasionIT = translate($occasion,'en','it');
						}
	if($course != ""){				
	$courseIT = translate($course,'en','it');
					}

	$name = str_ireplace(" ","_",$name);
	$cuisine = str_ireplace(" ","_",$cuisine);
	$diet = str_ireplace(" ","_",$diet);
	$occasion = str_ireplace(" ","_",$occasion);
	$course = str_ireplace(" ","_",$course);

	}
	else
		if($language == 'it'){

	$nameIT = $name;
	$cuisineIT = $cuisine;
	$dietIT = $diet;
	$occasionIT = $occasion;
	$courseIT = $course;

	$nameEN = translate($name,'it','en');
	if($cuisine != ""){
	$cuisineEN = translate($cuisine,'it','en');
					}
	if($diet != ""){
	$dietEN=  translate($diet,'it','en');
					}
	if($occasion != ""){				
	$occasionEN = translate($occasion,'it','en');
						}
	if($course != ""){					
	$courseEN = translate($course,'it','en');
				 	}

	$name = str_ireplace(" ","_",$name);
	$cuisine = str_ireplace(" ","_",$cuisine);
	$diet = str_ireplace(" ","_",$diet);
	$occasion = str_ireplace(" ","_",$occasion);
	$course = str_ireplace(" ","_",$course);
	}



	$base = getPrefix();
	
	$query = $base . "	
	INSERT DATA { comp:Recipe_".$name." a fo:Recipe ;
    fo:produces comp:".$food." ;
	fo:serves \"".$numberp."\""
	
	if($cuisine != ""){
	$query= $query.". comp:Cuisine_".$cuisine." a fo:Cuisine; rdfs:label\"".$cuisineIT."\"@it , \"".$cuisineEN."\"@en.  
	
	comp:Recipe_".$name." fo:cuisine comp:Cuisine_".$cuisine;
					}

if($diet != ""){
	$query = $query.". comp:Diet_".$diet." a fo:Diet; rdfs:label\"".$dietIT."\"@it , \"".$dietEN."\"@en.  
	
	comp:Recipe_".$name." fo:diet comp:Diet_".$diet;

					}
	if($occasion != ""){
	$query = $query.". comp:Occasion_".$occasion." a fo:Occasion; rdfs:label\"".$occasionIT."\"@it , \"".$occasionEN."\"@en.  
	comp:Recipe_".$name." fo:occasion comp:Occasion_".$occasion;
						}
	if($course != ""){
	$query = $query.". comp:Course_".$course." a fo:Course; rdfs:label\"".$courseIT."\"@it , \"".$courseEN."\"@en.  
	comp:Recipe_".$name." fo:course comp:Course_".$course;
						}

	$query = $query.".}";
				
	$risultati = sparqlUpdate($query);
	echo $query;
	
	
}



function insertShoppingCategory($shopping)
{	
	$shopping = trim($shopping);
	
	$base = getPrefix();
	
	$italian = strtolower(translate($shopping,"en","it"));
	
	$english = strtolower(translate($shopping,"it","en"));
	
	$shopping = str_ireplace(" ","_",$english);
	
	//inserisco 
	$query = $base . "INSERT DATA { comp:".$shopping." rdf:type fo:ShoppingCategory ;
						rdfs:label \"".$english."\"@en ;
						rdfs:label \"".$italian."\"@it 						
						}";
	//echo $query;
	sparqlUpdate($query);
	

	
}



function insertIngredient($ingredient,$quantity,$unit,$mis,$name,$i)
{	

	$name = str_ireplace(" ","_",$name);
	$ingredient = str_ireplace(" ","_",$ingredient);
	$ingredient = strtolower(translate($ingredient, "it", "en"));
	$quantity = str_ireplace(" ","_",$quantity);
	$unit = str_ireplace(" ","_",$unit);
	$mis = str_ireplace(" ","_",$mis);
	$i = str_ireplace(" ","_",$i);
	echo "3 ho ricevuto".$ingredient." - ".$quantity." - ".$unit." - ".$mis." - ".$name."\n";
	$base = getPrefix();
	
	$query = $base . "	INSERT DATA { comp:Ing_".$name."_".$ingredient." a fo:Ingredient ;
    fo:food comp:".$ingredient.";";
    if($mis == 'unit') {
    	$query = $query."fo:quantity \"".$quantity." ".$unit."\"";
    }

    if($mis == 'metric') {
    	$query = $query."fo:metric_quantity \"".$quantity." ".$unit."\"";
    }

    if($mis == 'imperial') {
    	$query = $query."fo:imperial_quantity \"".$quantity." ".$unit."\"";
    }
	
				
	$query = $query.". }";
	
	$risultato = sparqlUpdate($query);
	
	$query = $base . "	INSERT DATA { comp:IngList_".$name." a fo:IngredientList;
	rdf:_".$i." comp:Ing_".$name."_".$ingredient.".
	comp:Recipe_".$name." fo:ingredients comp:IngList_".$name.". }";

	$risultato2 = sparqlUpdate($query);

	echo $risultato." ".$risultato2;
	
}

function insertStep($i,$step,$name)
{	
	
	$i = str_ireplace(" ","_",$i);
	//$step = str_ireplace(" ","_",$step);
	$name = str_ireplace(" ","_",$name);
	$base = getPrefix();
	
	//inserisco step
	$query = $base . "	INSERT DATA { comp:Step_".$name."_".$i." a fo:Step ;
		fo:instruction \"".$step."\"@".detectLang($step).".
	}";
	
	sparqlUpdate($query);

	//inserisco lo step nel method
	$query = $base . "	INSERT DATA { comp:Method_".$name." a fo:Method ;
	rdf:_".$i." comp:Step_".$name."_".$i.".
					}";

	sparqlUpdate($query);

	// inserisco il metod nella ricetta

	$query = $base . "	INSERT DATA { comp:Recipe_".$name." fo:method comp:Method_".$name.".
					}";
	$risultato= sparqlUpdate($query);
	echo $risultato;
}


function getRecipesByWords($language,$input){

	$ingredienti = split(",",$input);


$query ="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX comp: <http://www.foodontology.it/ontology#>
PREFIX fo: <http://purl.org/ontology/fo/>
PREFIX food: <http://data.lirmm.fr/ontologies/food#>
SELECT ?recipe (COUNT(?recipe) AS ?count) WHERE{";
				
for ($i = 0;$i< count($ingredienti);$i++){

		if($i>0)
		{
			$query = $query."UNION";
		}

		$ingrediente = $ingredienti[$i];

$query = $query."


  	{
		SELECT DISTINCT ?recipe WHERE{
			{
				?recipe a fo:Recipe.
				?m a fo:Method.
				?recipe fo:method ?m.
				?s a fo:Step.
				?m ?x ?s.
				?s fo:instruction ?text.
				FILTER contains(?text,\"".$ingrediente."\")"."
				FILTER langMatches(lang(?text), \"".$language."\").
			}
			UNION
			{
				?recipe a fo:Recipe;
					rdfs:label ?text.
				FILTER contains(?text,\"".$ingrediente."\").
				FILTER langMatches(lang(?text),\"".$language."\").
				}
			UNION
			{
				?recipe a fo:Recipe;
				fo:produces ?food.
				?food rdfs:label ?label.
				FILTER contains(?label,\"".$ingrediente."\").
				FILTER langMatches(lang(?text),\"".$language."\").
				}
			UNION
			{
				?recipe a fo:Recipe.
				?list a fo:IngredientList.
				?recipe fo:ingredients ?list.
				?ing a fo:Ingredient.
				?list ?x ?ing.
				?ing fo:food ?food.
				?food rdfs:label ?text.
				FILTER contains(?text,\"".$ingrediente."\").
				FILTER langMatches(lang(?text), \"".$language."\").
			}
		}
	}



";


}

$query =$query."} GROUP BY ?recipe
ORDER BY DESC (?count)";

$results =  sparqlQuery($query,'JSON');
return $results;
}


function insertSubstitution($food,$quantityResult,$arrFoodSub,$arrQuantity,$fakeIngredient,$ingList,$typeResult,$arrTypeResult)
{	
	//controllo food
	insertFoodCtrlLang($food,"","","");
	for($i=0;$i<count($arrFoodSub)-1;$i++)
	{
		insertFoodCtrlLang($arrFoodSub[$i],"","","");
	}
	
	//creazione ingrediente fake
		$base = getPrefix();
	
		$query = $base . "insert data {";
				
		for($i=0;$i<count($fakeIngredient) - 1;$i++)
		{
			
			
			$query .= "comp:".$fakeIngredient[$i]." rdf:type comp:Ingredient_substitute;
				comp:hasFood comp:".strtolower(translate($arrFoodSub[$i],"it","en"))." ; " ;
				
			//aggiungo la quantità	
			if($arrTypeResult[$i] == 'unit') {
				$query = $query."fo:quantity \"".$arrQuantity[$i]."\" .";
			}

			if($arrTypeResult[$i] == 'metric') {
				$query = $query."fo:metric_quantity \"".$arrQuantity[$i]."\" .";
			}

			if($arrTypeResult[$i] == 'imperial') {
				$query = $query."fo:imperial_quantity \"".$arrQuantity[$i]."\" .";
			}		
		}			
			
		$query .= "}";
		//echo $query;
		sparqlUpdate($query);
		
	//creazione ingredientList
			$query = $base . "insert data { comp:".$ingList." rdf:type fo:IngredientList ;";
			
			if($typeResult == 'unit') {
				$query = $query."fo:quantity \"".$quantityResult."\" ";
			}

			if($typeResult == 'metric') {
				$query = $query."fo:metric_quantity \"".$quantityResult."\" ";
			}

			if($typeResult == 'imperial') {
				$query = $query."fo:imperial_quantity \"".$quantityResult."\" ";
				
			}
		
		
		for($i=0;$i<count($fakeIngredient) - 1;$i++)
		{
			
			
			$query .= "; comp:hasIngredient comp:".$fakeIngredient[$i]." ";
				
					
				
			
						
		}
		
		
		
		$query .= ". }";
		//echo "<br/>";
		//echo $query;
		sparqlUpdate($query);
		
		//collego al food
		
		$query = $base . "insert data {comp:".strtolower(translate($food,"it","en"))." comp:hasSubstitution comp:".$ingList." }";
		sparqlUpdate($query);
	
}


function getAllSubstitutionsFood($food,$lang)
{
	
	$base = getPrefix();
	
	$query = $base . "
	SELECT ?food ?subs ?quantity ?subs ?quantity WHERE { ?food comp:hasSubstitution ?o.
      ?o comp:hasIngredient ?i .
  		?i comp:hasFood ?subs .
  		?i fo:quantity ?quantity .
		?food rdf:label \"".$food."\"@".$lang."
    }
		";
	
	$res = sparqlQuery($query,"json");
	return $res;
	
	
	
}

/*
* get all shopping category with label in english
*/
function getAllShoppingCaterogyJson()
{
	
	$base = getPrefix();
	
	$query = $base . "
select ?shopping ?label where { ?shopping rdf:type fo:ShoppingCategory ;
    				rdfs:label ?label ;
    filter(lang(?label)='en')

}"	;
	
	$res = sparqlQuery($query,"json");
	return $res;
	
	
}

function getRecipeInfo($recipe,$lang){


$base = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX comp: <http://www.foodontology.it/ontology#>
PREFIX fo: <http://purl.org/ontology/fo/>
PREFIX food: <http://data.lirmm.fr/ontologies/food#>";

$query= $base."
SELECT ?textfood ?serves ?textcuisine ?textdiet ?textoccasion ?textcourse WHERE{
comp:".$recipe." a fo:Recipe;
			fo:produces ?food;
			fo:serves ?serves.
	OPTIONAL{comp:".$recipe." fo:cuisine ?cuisine;
			fo:diet ?diet;
			fo:occasion ?occasion;
			fo:course ?course.
			?cuisine rdfs:label ?textcuisine.
FILTER langmatches(lang(?textcuisine),\"".$lang."\").
FILTER langmatches(lang(?textdiet),\"".$lang."\").
?occasion rdfs:label ?textfoccasion.
FILTER langmatches(lang(?textoccasion),\"".$lang."\").
?course rdfs:label ?textcourse.
FILTER langmatches(lang(?textcourse),\"".$lang."\").
}
?food rdfs:label ?textfood.
FILTER langmatches(lang(?textfood),\"".$lang."\").
}";

echo $query;



}




 
?>