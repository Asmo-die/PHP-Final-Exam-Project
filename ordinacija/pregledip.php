<?php
	include_once "inc/sesija.php";
	require "inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ulogujse.php");  
	}
?>
	<div id="korisnikmain">
		<div id="korisnik">
			<br><br>
			<p style="font-weight:bold; text-align:left; margin-left:20px;"><a href="prometniPodaci/zakazivanje/azuriraj.php?akcija=n">ZAKAŽITE PREGLED</a></p>
			<br>
			<h3 style="color:#1a8cff; text-align:center;">VAŠI ZAKAZANI PREGLEDI:</h3>
			<table id="korisniktable" align="center" border="0" width="97%" style="text-align:center;">
				
<?php
			$sqlQuery = "SELECT P.id_zakazani_pregled as idZakPre, P.id_zakazana_usluga, U.naziv AS nazivusluge, U.trajanje AS trajanje, U.cena AS cena,Z.id AS idPregleda, Z.id_korisnika, 
								Z.id_doktora, Z.datum_vreme_start AS datumvreme, Z.datum_vreme_kraj AS kraj, K.ime_prezime AS ime
						FROM pregled_usluga P
						LEFT JOIN usluga U ON P.id_zakazana_usluga=U.id
						LEFT JOIN zakazani_pregled Z ON P.id_zakazani_pregled=Z.id
						LEFT JOIN korisnik K ON Z.id_doktora=K.id
						WHERE Z.id_korisnika=${_SESSION['id']} ORDER BY datumvreme";
			
			$res = mysqli_query($conn, $sqlQuery);
			if(mysqli_num_rows($res) == 0){
?>			
				<div id="nemapregleda">
					<h2>NEMATE ZAKAZANIH PREGLEDA!</h2>
				</div>
<?php	
				}
			$pomocna = 0;
			$ukupnaCena = 0;
			while($row = mysqli_fetch_assoc($res)){	
			
				
				if($pomocna != 0 && $pomocna != $row['idZakPre']){  // uslov koji ne ispisuje prvi prolaz i zadnji prolaz 
?>
					<tr>
						<td colspan="2" style="text-align:right; background-color:#1a8cff; color:white;">UKUPNO: </td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$trajanje?> min.</td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$ukupnaCena?> din.</td>
					</tr>
<?php
				}
				if($pomocna != $row['idZakPre']){					
					$ukupnaCena = 0;
					$trajanje = 0;
?>					<tr><td colspan="4" style="border:none; height:50px;"></td></tr>	
					<tr class="naslov" align="center">
						<td width="20%">DATUM I VREME</td>
						<td>DOKTOR</td>
						<td width="10%">IZMENI</td>
						<td width="10%">OTKAŽI</td>
					</tr>
					<tr align="center">
						<td><?=date('d-m-Y H:i', strtotime($row['datumvreme']))?></td>
						<td><?=$row['ime']?></td>
						<td><a href="prometniPodaci/zakazivanje/azuriraj.php?akcija=i&id=<?=$row['idPregleda']?>">Izmeni</a></td>
						<td><a href="prometniPodaci/zakazivanje/azuriraj.php?akcija=b&id=<?=$row['idPregleda']?>" onClick="return potvrdiIPosalji('Da li želite da otkazete zakazani pregled?');">Otkazi</a></td>
					</tr>
					<tr>
						<td class="naslov" colspan="3">USLUGE</td>
						<td style="background-color:#1a8cff;"></td>
					</tr>
<?php
				}	
?>
					<tr>
						<td colspan="3"><?=$row['nazivusluge']?></td>
						<td><?=$row['cena']?> din.</td>
					</tr>
<?php
				$ukupnaCena += $row['cena'];
				$pomocna = $row['idZakPre'];
				$trajanje = ((strtotime($row['kraj']) - 300) - strtotime($row['datumvreme'])) / 60;				
			}
				if($pomocna != 0){     						// ispisuje zadnji prolaz 
?>			
					<tr>
						<td colspan="2" style="text-align:right; background-color:#1a8cff; color:white;">UKUPNO: </td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$trajanje?> min.</td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$ukupnaCena?> din.</td>
					</tr>	
<?php
				}
?>
			</table>
			<br><br>
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
