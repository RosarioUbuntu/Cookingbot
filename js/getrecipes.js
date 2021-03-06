$(document).ready(function(){
	$(".btn-ingredients").hide();
	$(".btn-words").hide();
	
	
})

var offset = 0;

function getRecipesByIngredients(num){
    $("#results").html("");
   offset = offset + num;
   if(offset < 0)offset = 0;
   if(num == 0)offset = 0;
   lang = $("input[name=lang]:checked").val();
    npeople = $('#npeople').val();
    n = +$('#ningredient').val() +1;
    cuisine = $('#cuisine').val().trim();
    diet = $('#diet').val().trim();
    occasion = $('#occasion').val().trim();
    course = $('#course').val().trim();
    input ="";
	liquidMeasure = $('#liquidMeasure').val();
	solidMeasure = $('#solidMeasure').val();
    for (i=1;i<n;i++)
    {   
            ingredient = $('#ingredient'+i).val().trim();
			console.log("ing:" + ingredient);
           // quantity = $('#quantity'+i).val().trim();
            //unit = $('#misurazione'+i).val().trim();
            //mis = $('input[name=mis'+i+']:checked').val().trim();
            input=input+ingredient+";";
    }
        console.log(npeople+" "+input);
	
        $.post( "API/get_recipes_API.php", {liquidMeasure:liquidMeasure,solidMeasure:solidMeasure,type: "ingredients", lang:lang,npeople: npeople, input: input, cuisine: cuisine, diet: diet, occasion: occasion, course: course,offset:offset})
            .done(function( data ) {
				hideAllButton();
                console.log("Data Loaded: " + data );
                $('#results').append(data);
				$(".btn-ingredients").show();
	
	
        });

}


function getRecipesByWords(num){
    $("#results").html("");
    offset = offset + num;
	if(offset < 0)offset = 0;
	 if(num == 0)offset = 0;
	console.log(offset);
	lang = $("input[name=lang]:checked").val();
	console.log(lang);
	npeople = $('#npeopleWords').val();
    input = $('#words').val().trim();
    cuisine = $('#cuisine').val().trim();
    diet = $('#diet').val().trim();
    occasion = $('#occasion').val().trim();
    course = $('#course').val().trim();
	console.log(course);
	liquidMeasure = $('#liquidMeasure').val();
	solidMeasure = $('#solidMeasure').val();
	
    $.post( "API/get_recipes_API.php", {liquidMeasure:liquidMeasure,solidMeasure:solidMeasure, type: "words", lang:lang ,npeople: npeople, input: input, cuisine: cuisine, diet: diet, occasion: occasion, course: course,offset:offset})
            .done(function( data ) {
				hideAllButton();
                console.log("Data Loaded: " + data );
                $('#results').append(data);
				$("#btn-ingredients").hide();
				$(".btn-words").show();
        });
	
}


function visibile(element) {
	element="#"+element;
	console.log(element);
    $(element).prop('disabled',false);
}

function add(tipo){
    if (tipo == 'step') {
     n = +$('#nstep').val() +1;
     console.log(n);
     $('#nstep').val(n);
     stringa = "<div class='form-group'><h4>Step " +n+"</h4><div class='col-lg-12'><input type='text' id='step"+n+"' class='form-control' /></div></div>";
     $('#steps').append(stringa);
                        }
    else
    {
	
	if(country == "IT")
		strIng = "Ingrediente";
	else strIng = "Ingredient";
		
    n = +$('#ningredient').val() +1;
    console.log(n);
    $('#ningredient').val(n);
    stringa = "<div class=\"form-group\"><i class=\"fa fa-shopping-cart fa-2x red\"></i><div><span class=\"heading\" for=\"comment\">"+strIng+" "+n+"</span></div><input type='text'  class=\"ingredients\" id='ingredient"+n+"' /></div>";
    $('#ingredients1').append(stringa);
	setUpTypeahed();
    }
}

function remov(tipo){
    if(tipo =='step') {
    n = $('#nstep').val();
    if(n== '1') ;
        else{
            n =+$('#nstep').val()-1;
    console.log(n);
    $('#nstep').val(n);
    $('#steps .form-group:last-child').remove();
            }
                        }
    else{

        n = $('#ningredient').val();
    if(n== '1') ;
        else{
            n =+$('#ningredient').val()-1;
    console.log(n);
    $('#ningredient').val(n);
    $('#ingredients1 .form-group:last-child').remove();
            }
    }

}

function visible(name1,name2,tipo){
	$("#tipo").empty();
       // console.log(name1+name2);
        $('#'+name1).show();
        $('#'+name2).hide();
		$("#tipo").append(tipo);
}




function hideAllButton()
{
	$(".btn-words").hide();
	$(".btn-ingredients").hide();
	
}

