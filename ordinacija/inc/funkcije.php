<?php

function validacijaUlaznogPodatka($podatak) {								// validacija podataka za upis u bazu
  $podatak = trim($podatak);
  $podatak = stripslashes($podatak);
  $podatak = htmlspecialchars($podatak);
  return $podatak;
}

function greska($tekst){													// ispis greske kao alert
    echo"<script type='text/javascript'>alert('" .$tekst."')</script>";
}

	
function proveraLinka($strana){												// provera dubine strana 
    
	$pojavljivanje = substr_count($_SERVER['PHP_SELF'], "/");
	
	if($pojavljivanje == 2){
		$link = $strana . ".php";
	}
	else if($pojavljivanje == 3){
		$link = "../" . $strana . ".php";
	}
	else if($pojavljivanje == 4){
		$link = "../../" . $strana . ".php";
	}
	
	echo $link;
}

?>

