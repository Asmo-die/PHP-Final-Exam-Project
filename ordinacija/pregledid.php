<?php
	include_once "inc/sesija.php";
	require "inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ulogujse.php");  
	}
?>
	<div id="korisnikmain">
		<div id="korisnik">
			<table id="doktortable" align="center" border="0" width="97%" style="text-align:center;">
				
<?php
			$sqlQuery = "SELECT P.id_zakazani_pregled, P.id_zakazana_usluga, Z.datum_vreme_start AS pocetak, Z.datum_vreme_kraj AS kraj, Z.id_korisnika, Z.id_doktora,
							U.naziv as nazivusluge, U.cena AS cena, K.ime_prezime as ime
						FROM pregled_usluga P
						LEFT JOIN usluga U ON P.id_zakazana_usluga=U.id
						LEFT JOIN zakazani_pregled Z ON P.id_zakazani_pregled=Z.id
						LEFT JOIN korisnik K ON Z.id_korisnika=K.id
						WHERE Z.id_doktora=${_SESSION['id']}
						ORDER BY P.id_zakazani_pregled";
			
			$res = mysqli_query($conn, $sqlQuery);
			if(mysqli_num_rows($res) == 0){
?>			
				<div id="nemapregleda">
					<h2>NEMATE ZAKAZANIH PREGLEDA!</h2>
				</div>
<?php	
				}
			$pomocna = 0;
			$usluge = "";
			$ukupnaCena = 0;
			while($row = mysqli_fetch_assoc($res)){	
				if(date('Y-m-d', strtotime($row['pocetak'])) >= date('Y-m-d', time()) && date('Y-m-d', strtotime($row['pocetak'])) <= date('Y-m-d', strtotime("+7 day"))){  // provera za ispisivanje pregleda u narednih 7 dana
					if($pomocna != 0 && $pomocna != $row['id_zakazani_pregled']){
?>
						<td class="podaci"><?=$usluge?><br></td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$ukupnaCena?> din.</td>
					</tr>
<?php
					}
					if($pomocna != $row['id_zakazani_pregled']){
						$usluge = "";
						$ukupnaCena = 0;
	?>					<tr><td style="border:none; height:20px;"></td></tr>	
						<tr class="naslov" align="center">
							<td width="20%">DATUM I VREME</td>
							<td width="20%">PACIJENT</td>
							<td>USLUGE</td>
							<td width="7%">RAČUN</td>
						</tr>
						<tr align="center">
							<td class="podaci"><?=date('d-m-Y H:i', strtotime($row['pocetak']))?></td>
							<td class="podaci"><?=$row['ime']?></td>
<?php
					}
					$usluge .= $row['nazivusluge'] . "; ";
					$pomocna = $row['id_zakazani_pregled'];	
					$ukupnaCena += $row['cena'];
				}
			}
?>
						<td class="podaci"><?=$usluge?><br></td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$ukupnaCena?> din.</td>
					</tr>
			</table>
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
