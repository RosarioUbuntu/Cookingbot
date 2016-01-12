<?php
   //Controller di view
   require_once dirname(__FILE__). '/classes/Sessione.php';
    //Check se collegato
    $loggedin = Sessione::isLoggedIn(true);
    //Variabile per attivare contesto della topbar
    $is_addmenu = true;
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="GRI">
    <meta name="author" content="GRITeam">
    <title>CookingBot</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Tema material design -->
    <link href="css/material.min.css" rel="stylesheet">
    <link href="css/material-fullpalette.min.css" rel="stylesheet">
    <link href="css/ripples.min.css" rel="stylesheet">
    <link href="css/roboto.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">
    <!-- CSS Specifico della view-->
    <link href="css/wizardbase.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php require_once("components/topbar.php"); //Inclusione topbar?>

     <div class="container well">
        <div class="heading"><h2>Wizard Base</h2></div>
       <div class="row text-center" id="scopo" >
          <h3>Per quale scopo utilizzerai l'infrastruttura?</h3>
          <button type="button" class="btn btn-primary btn-lg  btn-w" onclick="scelte('WebSite');"><i class="fa fa-home" ></i><span>Sito Web</span></button>
           <button type="button" class="btn btn-primary btn-lg btn-w" onclick="scelte('Gaming');"><i class="fa fa-gamepad" ></i><span>Video Game</span></button>
            <button type="button" class="btn btn-primary btn-lg btn-w" onclick="scelte('Cloud');"><i class="fa fa-cloud" ></i><span>Cloud</span></button>
        </div>
        <div class="row text-center"  id="budget" >
          <h3>Qual'è il tuo budget?</h3>
          <button type="button" class="btn btn-primary btn-lg  btn-w" onclick="scelte('LowBudget');"><i class="fa fa-eur" ></i><span>Basso</span><span>0 &euro; - 10 &euro;</span></button>
           <button type="button" class="btn btn-primary btn-lg btn-w" onclick="scelte('HighBudget');"><i class="fa fa-eur" ></i><i class="fa fa-eur" ></i><span>Alto</span><span>51 &euro; - 100 &euro;</span></button>
        </div>
        <div class="row text-center" id="performance" >
          <h3>Che prestazioni cerchi?</h3>
          <button type="button" class="btn btn-primary btn-lg  btn-w" onclick="scelte('LowPerformance');"><i class="fa fa-bar-chart" ></i><span>Irrilevante</span></button>
           <button type="button" class="btn btn-primary btn-lg btn-w" onclick="scelte('HighPerformance');"><i class="fa fa-bar-chart" ></i><i class="fa fa-bar-chart" ></i><span>Alte prestazioni</span></button>
        </div>
      <input type="hidden" value="none" id="sceltautente">
      <input type="hidden" value="none" id="sceltautente1">
      <input type="hidden" value="none" id="sceltautente2">
      <div id="risultati"></div>
    </div><!-- /.container -->
    <?php require_once("components/modalegrafico.php"); //Modale per mostrare il grafico a ragno ?>
    <?php require_once("components/javascript-comune.php"); //Inclusione Javascript Comune ?>
    <!-- Script specifici di view -->
    <script src="js/wizardbase.js"></script>
    <script src="js/lib/highcharts.js"></script>
    <script src="js/lib/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/themes/dark-unica.js"></script>
  </body>
</html>