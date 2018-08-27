<?php
require '../../inc/konekcija.php';
include_once "../../inc/sesija.php";  
		
		$datum = $_GET['datum'];
		$doktor = $_GET['doktor'];
		$vremena = "";
		$sqlQuery = "SELECT * FROM zakazani_pregled WHERE id_doktora ='$doktor' ORDER BY datum_vreme_start";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){
			
			if($datum == date('Y-m-d', strtotime($row['datum_vreme_start']))){
				$vremena .= date('H-i', strtotime($row['datum_vreme_start'])) . " - " . date('H-i', strtotime($row['datum_vreme_kraj'])) . ';<br>';
			}
		}
		echo $vremena;
	
?>