<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
	
	if($_REQUEST['akcija'] == 'b'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM zakazani_pregled WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Zakazani pregled nije obrisan! <br> opis: ".mysqli_error($conn));
			else
				header("Location: zpregledi.php");
		}
		else{
			greska("Nije korektan id zakazanog pregleda!");
		}
	}
	
?>

