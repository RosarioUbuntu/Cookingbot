$(document).ready(function () {
			
			$("#btn-subs").hide();
			
			$("#btn-insert").click(function()
			{	
				var food = $("#food").val().trim();
				if(food != "")
				{	/* controllo che il food esista */
					$.post( "API/exists_food_API.php", { nameFood: food  })
					.done(function( data ) 
					{	$("#toAppend").empty();
						$("#toAppendSubs").empty();
						console.log("exists: " + data);
							if(data.trim() == "1")
							{
								$.post( "API/search_food_API.php", { nameFood: food  })
								.done(function( data ) 
								{		
									
									if(data != "")
									{
										
										//console.log(data);
										var json = $.parseJSON(data);
										res = json['results'];
										foods = res['bindings'];
										if(foods.length == 0)
										{	
											if(country == "IT")
												$("#toAppend").append('<div class="smallSpaceTop"><strong>Ci spiace ma non abbiamo informazioni energetiche riguardo questo alimento :(</strong></div>');
											
											else
												$("#toAppend").append('<div class="smallSpaceTop"><strong>sorry we don\'t have energetic information for this food :(</strong></div>');
											
											$("#btn-subs").show();
											return;
										}
										$("#toAppend").append('<div class="heading"><h2>'+food+'</h2></div>');
										
										kcal = 0;
										kj = 0;
										divisore = 0;
										if(foods.length > 2)
										{
												//faccio una media
											
												for(i=0;i<foods.length;i++)
												{
													//cerco le kilocalorie
													str = foods[i].energy['value'];
													var n = str.search(/kcal/);
													if(n > 0)
													{
														tmp = str.substr(0, n);
														console.log(tmp);
														kcal = kcal + parseInt(tmp);
														
													}
													//cerco i kj
													var n = str.search(/kj/);
													if(n > 0)
													{
														tmp = str.substr(0, n);
														console.log(tmp);
														kj = kj + parseInt(tmp);
														
													}
												}
											
											
										
											console.log("kc: "+kcal + " - kj: " + kj  );
											
											if(foods.length % 2 == 0)
												divisore = foods.length / 2;
											
											kcal = kcal / divisore;
											kj = kj / divisore;
											strlang = "";
											if(country == "IT") strlang = "Proprietà per 100g ( o 100ml per i liquidi)";
											else strlang = "Property per 100g ( or 100ml for liquids)";
											$("#toAppend").append('<div><h3>'+strlang+'</h3></div>');
											$("#toAppend").append('<div><h4>'+kcal+'kcal</h4></div>');
											$("#toAppend").append('<div><h4>'+kj+'kj</h4></div>');
										}else{
											//ciclo i foods
											strlang= "";
											if(country == "IT") strlang = "Proprietà per 100g ( o 100ml per i liquidi)";
											else strlang = "Property per 100g ( or 100ml for liquids)";
											$("#toAppend").append('<div><h3>'+strlang+'</h3></div>');
											for(i=0;i<foods.length;i++)
											{
													//console.log(foods[i].food);
													//console.log(foods[i].energy);
													$("#toAppend").append('<div><h3>'+foods[i].energy['value']+'</h3></div>');
											}	
										}
										$("#btn-subs").show();
									}//fine if	
								
								})//fine function
								
							}else
							{
								if(country == "IT")
									$("#toAppend").append('<div><h3>Alimento non trovato</h3></div>');
								else
									$("#toAppend").append('<div><h3>food not found</h3></div>');
								$("#btn-subs").hide();;
							
							}
							
					})//fine post exist_food_API
				}
			})	//fine btn-insert
			
		$("#btn-subs").click(function()
		{	var food = $("#food").val().trim();
				if(food != "")
				{	
					console.log("btn-subs");
					$.post( "API/get_substitution_API.php", { nameFood: food , lang: country })
							.done(function( data ) 
							{
								$("#toAppendSubs").empty();
								//console.log(data);
								$("#toAppendSubs").append(data);
								
								// initialize with defaults
								$(".rating").rating();
								$( ".rating").on('rating.change', function(event, value, caption) {
										console.log(value);
										console.log(caption);
										console.log( $( this ).attr("id"));
										var id = $( this ).attr("id");
										console.log(id);
										$.post( "API/insert_vote_substitution_API.php", { nomeSub: id , voto: value  })
											.done(function( data ) 
											{
														$("#btn-subs").click();
												
											})
									});
								
							})
				}
		})//fine btn-sub
		
		
		
});
		
function addSubstitution(food)
{
	window.open("addSubstitution.php?food=" + food)
	
}
		
		