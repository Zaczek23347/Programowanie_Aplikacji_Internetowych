<?php
    error_reporting(E_ALL^E_NOTICE^E_WARNING);

    $aktorzy = "./html/aktorzy.html";
    $filmy = "./html/filmy.html";
    $galeria = "./html/galeria.html";
    $onas = "./html/onas.html";
    $kontakt = "./html/kontakt.html";
    $zmienkolor = "./html/zmienkolor.html";
    /*
    if (file_exists($historia)) {
        echo "The file $historia exists";
    } else {
        echo "The file $historia does not exists";
    }
    */

    if($_GET['idp']=='')$strona='./html/indexbody.html';
    if($_GET['idp']=='aktorzy')$strona='./html/aktorzy.html';
    if($_GET['idp']=='filmy')$strona='./html/filmy.html';
    if($_GET['idp']=='galeria')$strona='./html/galeria.html';
    if($_GET['idp']=='onas')$strona='./html/onas.html';
    if($_GET['idp']=='kontakt')$strona='./html/kontakt.html';
    if($_GET['idp']=='zmienkolor')$strona='./html/zmienkolor.html';
    if($_GET['idp']=='video')$strona='./html/video.html';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="pl" />
<meta name="Author" content="Kacper Zaczek" />
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="js/timedate.js" type="text/javascript"></script>
<script src="js/kolorujtlo.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<title>Filmy Oscarowe</title>

</head>
<body onload="startclock()">
<table>
	<tr>
		<div id="title">
			<h1><i>Filmy Oscarowe</i></h1>
			<div id="zegarek"></div>
			<div id="data"></div>
		</div>
	</tr>
	<tr>
	<div id="navigation"><a href="index.php?idp=">Strona Główna</a></div>
	<div id="navigation"><a href="index.php?idp=aktorzy">Aktorzy</a></div>
	<div id="navigation"><a href="index.php?idp=filmy">Filmy</a></div>
	<div id="navigation"><a href="index.php?idp=galeria">Galeria</a></div>
	<div id="navigation"><a href="index.php?idp=onas">O nas</a></div>
	<div id="navigation"><a href="index.php?idp=kontakt">Kontakt</a></div>
	<div id="navigation"><a href="index.php?idp=zmienkolor">Zmienkolor</a></div>
	<div id="navigation"><a href="index.php?idp=video">Video</a></div>
	</tr>
<?php
 include($strona);
?>	

<footer>Filmy Oscarowe</footer>

<?php
 $nr_indeksu = '164448';
 $nr_grupy ='4';
 echo 'Autor: Kacper Żaczek '.$nr_indeksu.' grupa '.$nr_grupy.' <br /><br />';
?>
</body>
</html>

