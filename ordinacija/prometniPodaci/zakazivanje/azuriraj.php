<?php
	ob_start();
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
	if($_REQUEST['akcija'] == 'n'){
		
	$selectDoktor = "<option value=''> - Izaberite doktora - </option>";
		$sqlQuery = "SELECT K.*, V.naziv as naziv
					FROM korisnik K 
					LEFT JOIN vrsta_kor V ON K.id_vrsta_kor=V.id
					WHERE naziv = 'doktor'
					ORDER BY naziv";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
			$selectedDoktor = "";
			if(isset($_REQUEST['doktor'])){
				if($row['id'] == $_REQUEST['doktor'])
					$selectedDoktor = "selected";
			}
			$selectDoktor .= "<option value=${row['id']} $selectedDoktor> ${row['ime_prezime']} </option>";
		}
?>
		<div id="zakazivanjemain">
			<div id="zakazivanje">
				<form action="" method="get">
					<div id="zakazi">
						<p>Zakažite termin: </p><br>
						<input type="submit" name="submit" value="Zakaži">
						<input type="hidden" name="akcija" value="n">
					</div>
					<table>
						<tr class="naslov" >
							<td>DOKTOR: </td>
							<td>DATUM: </td>
							<td>VREME: </td>
						</tr>
						<tr>
							<td style="text-align:center;"><select style="text-align:center;" id="doktor" name="doktor" required><?=$selectDoktor?></select></td>
							<td style="text-align:center;"><input type="date" id="datum" name="datum" required onchange="ajaxDatum()"><br></td>
							<td style="text-align:center;"><input id="time" name="vreme" type="text" style="text-align:center;"></td>
						</tr>
					</table>
					
					<div id="zauzeti">
						<p>Zauzeti termini:</p>
						<div id="zauzetitermini"></div>
					</div>
					<table id="ispisusluga" style="border:0">
<?php

						$sqlQuery = "SELECT U.*, V.naziv as vrsta
									FROM usluga U
									LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge = V.id
									ORDER BY V.id";
						$res = mysqli_query($conn, $sqlQuery);
						$pomocna = 0;
						while($row = mysqli_fetch_assoc($res)){	
							if($pomocna != $row['id_vrsta_usluge']){
?>
							<tr class="blank"></tr>
							<th class="naslov" colspan="3"><?=$row['vrsta']?></th>
							<tr class="blank"></tr>
<?php
								}
?>
							<tr>
								<td><input type="checkbox" id="usluga" name="usluga[]" value='<?=$row['id']?>'><?=$row['naziv']?></td>
								<td align="right" name="trajanje[]"><?=($row['trajanje']/60)?> min.</td>
								<td align="right" name="cena[]"><?=$row['cena']?> din.</td>
							</tr>
<?php	
							$pomocna = $row['id_vrsta_usluge'];
						}
?>
					</table>
				</form>
			</div>
		</div>
<?php	
	}
	
	if(isset($_REQUEST['submit'])){
		
		if($_REQUEST['usluga'] == ""){
			greska("Niste odabrali nijednu uslugu!");
		}
		else{
		$pocetak = $_REQUEST['datum'] ." ". $_REQUEST['vreme']; 
		$usluga = $_REQUEST['usluga'];
		$trajanjeUsluga = 300;
		$sqlQueryV = "SELECT id, trajanje FROM usluga";
		$resV = mysqli_query($conn, $sqlQueryV);
			while($rowV = mysqli_fetch_assoc($resV)){	
				foreach($usluga as $id){
					if($id == $rowV['id']){
						$trajanjeUsluga += $rowV['trajanje'];
					}
				}
			}
		$kraj = date('Y-m-d H:i',(strtotime($pocetak) + $trajanjeUsluga));
		$krajVreme = date('H:i', strtotime($kraj));
		$krajRV = date('20:00');
		$pauzaP = date('16:00');
		$pauzaK = date('16:30');
		
		
		if($_REQUEST['datum'] < date('Y-m-d')){
			greska("Niste odabrali dobar datum!");
		}
		else if($_REQUEST['vreme'] == ""){
			greska("Niste odabrali vreme!");
		}
		else if($_REQUEST['datum'] == date('Y-m-d') && $_REQUEST['vreme'] < date('H:i')){
			greska("Niste odabrali ispravno vreme!");
		}
		else if(date('N', strtotime($_REQUEST['datum'])) >= 6){
			greska("Ne radimo vikendom. Odaberite drugi datum."); 
		}
		else if($_REQUEST['datum'] > date('Y-m-d', strtotime("+30 day"))){
			greska("Možete zakazati samo do 30 dana unapred."); 
		}
		else if(strtotime($krajVreme) > strtotime($krajRV)){
			greska("Kraj termina je izvan radnog vremena. Zakazite malo ranije.");
		}
		else if(strtotime($krajVreme) >= strtotime($pauzaP) && strtotime($krajVreme) <= strtotime($pauzaK)){
			greska("Termin se poklapa sa pauzom. Zakažite termin malo ranije ili nakon pauze.");
		}
		else if(strtotime($_REQUEST['vreme']) < strtotime($pauzaP) && strtotime($krajVreme) > strtotime($pauzaK)){
			greska("Termin se poklapa sa pauzom. Zakažite termin malo ranije ili nakon pauze.");
		}
		else{
			$sqlQueryProvera = "SELECT * FROM zakazani_pregled WHERE id_doktora = ${_REQUEST['doktor']}";
			$resProvera = mysqli_query($conn, $sqlQueryProvera);
			$greska = 0;
			while($rowProvera = mysqli_fetch_assoc($resProvera)){
				
				if(strtotime($pocetak) >= strtotime($rowProvera['datum_vreme_start']) && strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_kraj'])){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else if(strtotime($kraj) >= strtotime($rowProvera['datum_vreme_start']) && strtotime($kraj) <= strtotime($rowProvera['datum_vreme_kraj'])){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else if( (strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_start']) && strtotime($rowProvera['datum_vreme_start']) <= strtotime($kraj)) || (strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_kraj']) 
							&& strtotime($rowProvera['datum_vreme_kraj']) <= strtotime($kraj))){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else{
					continue;
				}
			}
			
			if($greska == 0){
				
				$sqlQueryZakazi = "INSERT INTO zakazani_pregled (id, id_korisnika, id_doktora, datum_vreme_start, datum_vreme_kraj)
								VALUES (null, ${_SESSION['id']}, ${_REQUEST['doktor']}, '$pocetak', '$kraj' )";
				$resZakazi = mysqli_query($conn, $sqlQueryZakazi);
					if(!$resZakazi)
						greska("Pregled nije upisan u bazu! <br> opis: ".mysqli_error($conn));
					else{
						header("Location: ../../pregledip.php");
						$idPregleda = mysqli_insert_id($conn);
					}
					
				foreach($usluga as $idU){
					$sqlQuery = "INSERT INTO pregled_usluga (id_zakazani_pregled, id_zakazana_usluga) VALUES ('$idPregleda', '$idU')";
					$res = mysqli_query($conn, $sqlQuery);
						if(!$resP){
						greska("Zakazana usluga nije upisana u bazu!".mysqli_error($conn));
						}
				}
			}
		}
		}
	}	
	
	
	if($_REQUEST['akcija'] == 'b'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM zakazani_pregled WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Zakazani pregled nije obrisan! <br> opis: ".mysqli_error($conn));
			else
				header("Location: ../../pregledip.php");
		}
		else{
			greska("Nije korektan id zakazanog pregleda!");
		}
	}
	
	if($_REQUEST['akcija'] == 'i'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){

			$sqlQuery = "SELECT Z.*, K.ime_prezime AS ime_prezime
						FROM zakazani_pregled Z
						LEFT JOIN korisnik K ON Z.id_doktora=K.id
						WHERE Z.id=${_REQUEST['id']}";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){		
				
				$selectDoktor = "";
				$sqlQueryD = "SELECT K.*, V.naziv as naziv
								FROM korisnik K 
								LEFT JOIN vrsta_kor V ON K.id_vrsta_kor=V.id
								WHERE naziv = 'doktor'
								ORDER BY naziv";
				$resD = mysqli_query($conn, $sqlQueryD);
				while($rowD = mysqli_fetch_assoc($resD)){	
					$selectedDoktor = "";
					if($row['id_doktora'] == $rowD['id']){
						$selectedDoktor = "selected";
					}
					$selectDoktor .= "<option value=${rowD['id']} $selectedDoktor> ${rowD['ime_prezime']} </option>";
				}
				
?>		
				<div id="zakazivanjemain">
					<div id="zakazivanje">
						<form action="" method="get">
							<div id="zakazi">
								<p>Izmenite termin: </p><br>
								<input type="submit" name="izmeni" value="Izmeni">
								<input type="hidden" name="akcija" value="i">
								<input type="hidden" name="id" value="<?=$row['id']?>">
							</div>
							<table>
								<tr class="naslov" >
									<td>DOKTOR: </td>
									<td>DATUM: </td>
									<td>VREME: </td>
								</tr>
								<tr>
									<td style="text-align:center;"><select style="text-align:center;" id="doktor" name="doktor" required><?=$selectDoktor?></select></td>
									<td style="text-align:center;"><input type="date" id="datum" name="datum" onchange="ajaxDatum()" value=<?=date('Y-m-d', strtotime($row['datum_vreme_start']))?> required><br></td>
									<td style="text-align:center;"><input id="time" name="vreme"  value='<?=date('H:i', strtotime($row['datum_vreme_start']))?>' type="text" style="text-align:center;"></td>
								</tr>
							</table>
							
							<div id="zauzeti">
								<p>Zauzeti termini:</p>
								<div id="zauzetitermini"></div>
							</div>
							<table id="ispisusluga" style="border:0">
						<?php
								$oznaceneUsluge = array();
								$sqlQueryOznaceno = "SELECT * FROM pregled_usluga WHERE id_zakazani_pregled = ${_REQUEST['id']}";
								$resO = mysqli_query($conn, $sqlQueryOznaceno);
								while($rowO = mysqli_fetch_assoc($resO)){
									$oznaceneUsluge[] .= $rowO['id_zakazana_usluga'];
								}
			
								$sqlQueryU = "SELECT U.*, U.id AS idU, V.naziv AS vrsta, V.id AS idV
												FROM usluga U
												LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge = V.id
												ORDER BY idV";
								$resU = mysqli_query($conn, $sqlQueryU);
								$pomocna = 0;
								while($rowU = mysqli_fetch_assoc($resU)){	
									if($pomocna != $rowU['idV']){
						?>
									<tr class="blank"></tr>
									<th class="naslov" colspan="3"><?=$rowU['vrsta']?></th>
									<tr class="blank"></tr>
						<?php
									}					
						?>
									<tr>
										<td><input type="checkbox" name="usluga[]" value='<?=$rowU['idU']?>'
						<?php						
											foreach($oznaceneUsluge as $id){
												$stanje = '';
												if($rowU['idU']==$id){
													$stanje = 'checked';
												}
												else{
													$stanje = '';
												}
												echo $stanje;
											}			
						?>						
										><?=$rowU['naziv']?></td>
										<td align="right" id="trajanje"><?=($rowU['trajanje']/60)?> min.</td>
										<td align="right" id="cena"><?=$rowU['cena']?> din.</td>
									</tr>
						<?php	
									$pomocna = $rowU['idV'];
								}
						?>
							</table>
						</form>
					</div>
				</div>
<?php	
			}
			
		}
	}		
	
	if(isset($_REQUEST['izmeni'])){
		
		if($_REQUEST['usluga'] == ""){
			greska("Niste odabrali nijednu uslugu!");
		}
		else{
		$pocetak = $_REQUEST['datum'] ." ". $_REQUEST['vreme']; 
		$usluga = $_REQUEST['usluga'];
		$trajanjeUsluga = 300;
		$sqlQueryV = "SELECT id, trajanje FROM usluga";
		$resV = mysqli_query($conn, $sqlQueryV);
			while($rowV = mysqli_fetch_assoc($resV)){	
				foreach($usluga as $id){
					if($id == $rowV['id']){
						$trajanjeUsluga += $rowV['trajanje'];
					}
				}
			}
		$kraj = date('Y-m-d H:i',(strtotime($pocetak) + $trajanjeUsluga));
		$krajVreme = date('H:i', strtotime($kraj));
		$krajRV = date('20:00');
		$pauzaP = date('16:00');
		$pauzaK = date('16:30');
		
		
		if($_REQUEST['datum'] < date('Y-m-d')){
			greska("Niste odabrali dobar datum!");
		}
		else if($_REQUEST['vreme'] == ""){
			greska("Niste odabrali vreme!");
		}
		else if($_REQUEST['usluga'] == ""){
			greska("Niste odabrali nijednu uslugu!");
		}
		else if($_REQUEST['datum'] == date('Y-m-d') && $_REQUEST['vreme'] < date('H:i')){
			greska("Niste odabrali ispravno vreme!");
		}
		else if(date('N', strtotime($_REQUEST['datum'])) >= 6){
			greska("Ne radimo vikendom. Odaberite drugi datum."); 
		}
		else if($_REQUEST['datum'] > date('Y-m-d', strtotime("+30 day"))){
			greska("Možete zakazati samo do 30 dana unapred."); 
		}
		else if(strtotime($krajVreme) > strtotime($krajRV)){
			greska("Kraj termina je izvan radnog vremena. Zakazite malo ranije.");
		}
		else if(strtotime($krajVreme) >= strtotime($pauzaP) && strtotime($krajVreme) <= strtotime($pauzaK)){
			greska("Termin se poklapa sa pauzom. Zakažite termin malo ranije ili nakon pauze.");
		}
		else if(strtotime($_REQUEST['vreme']) < strtotime($pauzaP) && strtotime($krajVreme) > strtotime($pauzaK)){
			greska("Termin se poklapa sa pauzom. Zakažite termin malo ranije ili nakon pauze.");
		}
		else{
			$sqlQueryProvera = "SELECT * FROM zakazani_pregled WHERE id_doktora = ${_REQUEST['doktor']}";
			$resProvera = mysqli_query($conn, $sqlQueryProvera);
			$greska = 0;
			while($rowProvera = mysqli_fetch_assoc($resProvera)){

				if(strtotime($pocetak) >= strtotime($rowProvera['datum_vreme_start']) && strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_kraj']) && $rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else if(strtotime($kraj) >= strtotime($rowProvera['datum_vreme_start']) && strtotime($kraj) <= strtotime($rowProvera['datum_vreme_kraj']) && $rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else if( (strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_start']) && ((strtotime($rowProvera['datum_vreme_start']) <= strtotime($kraj)) || (strtotime($pocetak) <= strtotime($rowProvera['datum_vreme_kraj']))) && strtotime($rowProvera['datum_vreme_kraj']) <= strtotime($kraj)) && $rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					break;
				}
				else{
					continue;
				}
			}
			
			if($greska == 0){
				$sqlQueryIzmeni = "UPDATE zakazani_pregled SET id=${_REQUEST['id']}, id_doktora=${_REQUEST['doktor']}, id_korisnika=${_SESSION['id']}, datum_vreme_start='$pocetak', datum_vreme_kraj='$kraj' WHERE id='${_REQUEST['id']}'";		
				$resIzmeni = mysqli_query($conn, $sqlQueryIzmeni);
				if(!$resIzmeni)
					greska("Pregled nije izmenjen u bazi! Opis: ".mysqli_error($conn));
				else{
					header("Location: ../../pregledip.php");
				}
				
				$sqlQueryObrisi = "DELETE FROM pregled_usluga WHERE id_zakazani_pregled=${_REQUEST['id']}";
				$resOb = mysqli_query($conn, $sqlQueryObrisi);
				
				foreach($usluga as $idU){
					$sqlQuery = "INSERT INTO pregled_usluga (id_zakazani_pregled, id_zakazana_usluga) VALUES ('${_REQUEST['id']}', '$idU')";
					$res = mysqli_query($conn, $sqlQuery);
						if(!$res){
						greska("Zakazana usluga nije upisana u bazu!".mysqli_error($conn));
						}
				}
			}	
		}
		}
	}
	
	ob_end_flush();
?>
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

<script>
function ajaxDatum(){
	var xmlhttp = createRequest();
	
	xmlhttp.onreadystatechange = function(){					// ajax upit za ispisivanje zauzetih termina
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("zauzetitermini").innerHTML = this.responseText;
		}
	}
	
	var datum = document.getElementById("datum").value;
	var doktor = document.getElementById("doktor").value;
	
	xmlhttp.open("GET", "ajaxZakazivanje.php?datum="+datum+"&doktor="+doktor, true);
	xmlhttp.send();
}
</script>