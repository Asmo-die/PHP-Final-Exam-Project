<?php
	include_once "inc/sesija.php";
	require "inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ulogujse.php");  
	}
?>
	<div id="podacimain">
		<div id="podaci">
			<dl id="podacilista">
				<dt><a href="maticniPodaci/korisnici/pregled.php">&#9755; Upravljanje korisnicima</a></dt>
				<dd>Dodavanje novog, izmena ili brisanje postojećeg korisnika i tipa korisnika.</dd>
				<dt><a href="maticniPodaci/adrese/pregled.php">&#9755; Upravljanje adresama</a></dt>
				<dd>Dodavanje novih, izmena ili brisanje postojećih ulica, gradova i adresa.</dd>
				<dt><a href="maticniPodaci/usluge/pregled.php">&#9755; Upravljanje uslugama</a></dt>
				<dd>Dodavanje novih, izmena ili brisanje postojećih usluga i tipova usluga.</dd>
				<dt><a href="prometniPodaci/izvestaji/zpregledi.php">&#9755; Zakazani pregledi</a></dt>
				<dd>Brisanje zakazanih pregleda.</dd>
				<dt><a href="prometniPodaci/izvestaji/pregled.php">&#9755; Izveštaji</a></dt>
				<dd></dd>
			</dl>
		</div>
	</div>
	

		<footer>
			<div id="facebook"></div>
			<div id="twitter"></div>
			<p id="p1">Stomatoloska ordinacija <br>
			Ugrinovacka 2, Zemun, Beograd <br>
			063/780-44-85  011/2-102-426 <br>
			office@zubarskaordinacija.rs</p>
			<p id="p2">© Copyright 2018 - Design by Ljubomir Brmbolic</p>
			<a href="">Back to top</a>
		</footer>
	</body>
</html>
