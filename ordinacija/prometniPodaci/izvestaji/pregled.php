<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
			header("Location: ../../ulogujse.php");  
		}

			if(isset($_REQUEST['reset2'])){
				$_REQUEST['fltDoktor'] = '';
			}

			$selectDoktor = "<option value='' selected> - Izaberite doktora - </option>";
			$sqlQuery = "SELECT K.*, V.naziv as naziv
					FROM korisnik K 
					LEFT JOIN vrsta_kor V ON K.id_vrsta_kor=V.id
					WHERE naziv = 'doktor'
					ORDER BY naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectedDoktor = "";
				if(isset($_REQUEST['fltDoktor'])){
					if($row['id'] == $_REQUEST['fltDoktor'])
						$selectedDoktor = "selected";
				}
				$selectDoktor .= "<option value=${row['id']} $selectedDoktor> ${row['ime_prezime']} </option>";
			}
?>
		<div id="izvestajimain">
			<div id="izvestaji">
				<br>
				<a class="linknazad" href="../../podaci.php">&#8666; Povratak na upravljanje podacima</a>
				<br><br>
				<h2 style="text-align:center; color:#1a8cff;">Izveštaj:</h2>
				<table id="fltTable" >
					<form action="" name="filtriranje">
			<?php
						if(isset($_REQUEST['reset'])){
							$_REQUEST['fltPacijent'] = '';
						}
            ?>
					
						<tr>
							<td>Pacijent:</td>
							<td><input type="text" size="15" name="fltPacijent" value=<?= (isset($_REQUEST['fltPacijent'])) ? $_REQUEST['fltPacijent'] : ""; ?>></td>
							<td><input type="submit" name="filtrirajP" value="Primeni filter"></td>
							<td><input type="submit" name="reset" value="Resetuj filter"></td>
						</tr>
					
					</form>
				<br>
					<form action="" name="filtriranje2">
						<tr>
							<td>Doktor:</td>
							<td><select id="doktor" name="fltDoktor" ><?=$selectDoktor?></select></td>
							<td><input type="submit" name="filtrirajD" value="Primeni filter"></td>
							<td><input type="submit" id="reset2" name="reset2" value="Resetuj filter"></td>
						<tr>
					</form>
				<br>
					<form action="" name="filtriranje3">
						<tr>
							<td>Datum od:</td><td><input required type="date" name="fltDatumOd"></td>
							<td>Datum do:</td><td><input required type="date" name="fltDatumDo"></td>
							<td><input type="submit" name="filtrirajDatum" value="Primeni filter"></td>
							<td><input type="submit" id="reset3" name="reset3" value="Resetuj filter"></td>
						<tr>
					</form>
				</table>
				<br>
				<table align="center" border="0" id="izvTable"width="97%" style="text-align:center;">
					<tr class="naslov">
						<td width="8%">DATUM</td>
						<td width="16%">DOKTOR</td>
						<td width="16%">PACIJENT</td>
						<td >USLUGE</td>
						<td width="8%">RAČUN</td>
					</tr>
			<?php
					$sqlUslovi = "1=1"; 
					
					$trenutniDatum = date('Y-m-d H-i');
					$sqlUslovi .= " AND Z.datum_vreme_kraj < '$trenutniDatum'";       // uslov da su zakazani pregledi prosli danasnji datum
					
					if(isset($_REQUEST['filtrirajP'])){
						if($_REQUEST['fltPacijent'] != ""){
							$imeprezime = validacijaUlaznogPodatka($_REQUEST['fltPacijent']);
							$sqlUslovi .= " AND P.ime_prezime LIKE '%$imeprezime%'";
						}
					}
					
					if(isset($_REQUEST['filtrirajD'])){
						if($_REQUEST['fltDoktor'] != ""){
							$doktor = validacijaUlaznogPodatka($_REQUEST['fltDoktor']);
							$sqlUslovi .= " AND Z.id_doktora = $doktor";
						}
					}
					
					if(isset($_REQUEST['filtrirajDatum'])){
						if($_REQUEST['fltDatumOd'] != "" && $_REQUEST['fltDatumDo'] != ""){
							$sqlUslovi .= " AND date(Z.datum_vreme_start) >= '${_REQUEST['fltDatumOd']}' AND date(Z.datum_vreme_start) <= '${_REQUEST['fltDatumDo']}'";
						}
					}
					
					
					$sqlQuery = "SELECT Z.id AS idPregleda, Z.datum_vreme_start AS pocetak, Z.datum_vreme_kraj AS kraj, 
					Z.id_doktora, Z.id_korisnika, P.ime_prezime AS pacijent, D.ime_prezime AS doktor
					FROM zakazani_pregled Z 
					Left join korisnik P on (Z.id_korisnika=P.id)
					Left join korisnik D on (Z.id_doktora=D.id)
					WHERE $sqlUslovi
					ORDER BY Z.datum_vreme_start";
					
					$usluge="";
					$ukupno=0;
					$pomocna=0;
					$zbirCena=0;
					$res = mysqli_query($conn, $sqlQuery);
					while($row = mysqli_fetch_assoc($res)){
			?>
					<tr>
						<td><?=date('d/m/Y', strtotime($row['pocetak']))?></td>
						<td><?=$row['doktor']?></td>
						<td><?=$row['pacijent']?></td>
			<?php
						$sqlQueryU = "SELECT PU.*, U.naziv AS naziv, U.trajanje AS trajanje, U.cena AS cena
								FROM pregled_usluga PU
								LEFT JOIN usluga U ON PU.id_zakazana_usluga=U.id
								WHERE id_zakazani_pregled=${row['idPregleda']}
								ORDER BY U.naziv";
						$resU = mysqli_query($conn, $sqlQueryU);
						while($rowU = mysqli_fetch_assoc($resU)){
							
							if($pomocna != $rowU['id_zakazani_pregled']){
								$usluge = "";
								$ukupno=0;
							}
							$usluge .= $rowU['naziv'] . "; ";
							$ukupno += $rowU['cena'];
							$pomocna = $rowU['id_zakazani_pregled'];
						}
			?>			
						<td><?=$usluge?><br></td>	
						<td><?=$ukupno?> din.<br></td>	
			<?php		
						$zbirCena += $ukupno;
					}		
			?>	
					<tr>
						<td colspan="4" style="text-align:right; background-color:#1a8cff; color:white;">UKUPNO: </td>
						<td style="font-weight:bold; background-color:lightgrey;"><?=$zbirCena?> din.</td>
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