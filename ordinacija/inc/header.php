<!DOCTYPE html>
<?php
	@include_once ("inc/funkcije.php");
	@include_once ("../../inc/funkcije.php");
?>

<html lang="sr">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<meta name="description" content="zubarska ordinacija">
		<meta name="keywords" content="zubar, zubarska ordinacija, ordinacija">
		<meta name="author" content="LjubomirBrmbolic">
		<title>Zubarska ordinacija</title>
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script type="text/javascript" src="../../js/jquery.timepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="../../css/jquery.timepicker.css">
		<script src="../../js/funkcije.js"></script>
		<script src="../js/funkcije.js"></script>
		<script src="js/funkcije.js"></script>
		<link rel="stylesheet" type="text/css" href="css/stil.css">
		<link rel="stylesheet" type="text/css" href="../css/stil.css">
		<link rel="stylesheet" type="text/css" href="../../css/stil.css">
    </head>
	<body>
		<header>
			<img id="logo" src=<?=(substr_count($_SERVER['PHP_SELF'], "/")==2)?"css/slike/dental-logo.png":"../../css/slike/dental-logo.png"?> alt="logo">
			<ul id="meni">
				<li><a href=<?=proveraLinka("index")?>>Početna</a></li>
				<li><a href=<?=proveraLinka("onama")?>>O nama</a></li>
				<li><a href=<?=proveraLinka("usluge")?>>Usluge</a></li>
				<li><a href=<?=proveraLinka("cenovnik")?>>Cenovnik</a></li>
			<?php if(!isset($_SESSION['korisnik'])){
			?>
				<li><a href=<?=proveraLinka("ulogujse")?>>Uloguj se</a></li>
			<?php	
				}
			?>
			<?php if(isset($_SESSION['korisnik'])){
				
				if($_SESSION['vrsta_korisnika'] == 2){
					
			?>
					<li><a href=<?=proveraLinka("pregledid")?>>Pregledi</a></li>
					<li><a href=<?=proveraLinka("odjava")?>>Odjavi se</a></li>
			<?php
				}
				else if($_SESSION['vrsta_korisnika'] == 3){
			?>
					<li><a href=<?=proveraLinka("pregledip")?>>Pregledi</a></li>
					<li><a href=<?=proveraLinka("odjava")?>>Odjavi se</a></li>
			<?php
				}
				else{
			?>
					<li><a href=<?=proveraLinka("podaci")?>>Podaci</a></li>
					<li><a href=<?=proveraLinka("odjava")?>>Odjavi se</a></li>
			<?php
				}
			}
			?>
				<li><a href=<?=proveraLinka("kontakt")?>>Kontakt</a></li>
				
			</ul>
		</header>