<?php
include_once dirname(__FILE__).'/../query_sparql.php';

function getRecipeSteps($recipeURI, $lang){
		
	$base = getPrefix();
	$query = $base . "
	SELECT ?s ?text WHERE{
		".$recipeURI." a fo:Recipe.
		?m a fo:Method.
		".$recipeURI." fo:method ?m.
		?s a fo:Step.
		?m ?x ?s.
		?s fo:instruction ?text.
		FILTER langmatches(lang(?text),\"".$lang."\").
		}ORDER BY (?x)
	";
	
	$results = sparqlQuery($query,"json");
	$dataStep = json_decode($results);
	$toCicleStep = $dataStep->results->bindings;
	$steps = [];
	if(sizeof($toCicleStep)>0){
		for($i = 0 ; $i<sizeof($toCicleStep); $i++){
			$steps[$i] = $toCicleStep[$i]->text->value;
		}
	}
	else{
		if($lang=="en"){
			$lang="it";
		}
		else{
			$lang="en";
		}
		$query = $base . "
		SELECT ?s ?text WHERE{
			".$recipeURI." a fo:Recipe.
			?m a fo:Method.
			".$recipeURI." fo:method ?m.
			?s a fo:Step.
			?m ?x ?s.
			?s fo:instruction ?text.
			FILTER langmatches(lang(?text),\"".$lang."\").
			}ORDER BY (?x)
		";
		
		$results = sparqlQuery($query,"json");
		$dataStep = json_decode($results);
		$toCicleStep = $dataStep->results->bindings;
		$steps = [];
		if(sizeof($toCicleStep)>0){
			for($i = 0 ; $i<sizeof($toCicleStep); $i++){
				$steps[$i] = $toCicleStep[$i]->text->value;
			}
		}
		else{
			if($lang=="en"){
				$steps[0]="Step ricetta non trovati";
			}
			else{
				$steps[0]="Recipe Steps not Found";
			}	
		}
	}
	return $steps;
}
?>