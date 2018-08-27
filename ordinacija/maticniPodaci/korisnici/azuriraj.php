<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
	if($_REQUEST['akcija'] == 'n'){
		
		$selectVrsta = "<option value=''> - Izaberite vrstu - </option>";
		$sqlQuery = "SELECT * FROM vrsta_kor ORDER BY naziv";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
			$selectedVrsta = "";
			if(isset($_REQUEST['vrsta'])){
				if($row['id'] == $_REQUEST['vrsta'])
					$selectedVrsta = "selected";
			}
			$selectVrsta .= "<option value=${row['id']} $selectedVrsta> ${row['naziv']} </option>";
		}
		
		$selectAdresa = "<option value=''> - Izaberite adresu - </option>";
		$sqlQuery = "SELECT A.id as id, A.id_ulica, A.broj as broj, A.id_grad, U.naziv as ulica, G.naziv as grad
					FROM adresa A
					LEFT JOIN ulica U ON A.id_ulica=U.id
					LEFT JOIN grad G ON A.id_grad=G.id
					ORDER BY id";
		$resA = mysqli_query($conn, $sqlQuery);
		while($rowA = mysqli_fetch_assoc($resA)){	
			$selectedAdresa = "";
			if(isset($_REQUEST['adresa'])){
				if($rowA['id'] == $_REQUEST['adresa'])
					$selectedAdresa = "selected";
			}
			$selectAdresa .= "<option value=${rowA['id']} $selectedAdresa> ${rowA['ulica']} ${rowA['broj']}, ${rowA['grad']} </option>";
		}
		
?>
	<div id="podacikorisnikmain">
		<div id="podacikorisnik">
			<br>
			<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled korisnika</a>
			<br><br>
			<h4>Unesite podatke za novog korisnika:</h4>			
			<form id="novikorisnik">
				<table cellpadding="2px" cellspacing="3px">
					<tr>
						<td class="naslov"><label>Korisnicko ime: </label></td>
						<td><input type="text" size="40" name="korisnik" required></td>
					</tr>
					<tr>
						<td class="naslov"><label>Lozinka: </label></td>
						<td><input type="text" size="40" name="lozinka" required></td>
					</tr>
					<tr>
						<td class="naslov"><label>Vrsta korisnika: </label></td>
						<td><select  name="vrsta"><?=$selectVrsta?></select></td>
					</tr>
					<tr>
						<td class="naslov"><label>Ime i prezime: </label></td>
						<td><input type="text" size="40" name="imeprezime" required></td>
					</tr>
					</tr>
						<td class="naslov"><label>Adresa: </label></td> 
						<td><select  name="adresa"><?=$selectAdresa?></select></td>
					</tr>
					<tr>
						<td class="naslov"><label>Telefon: </label></td> 
						<td><input type="text" size="40" name="telefon" required></td>
					</tr>
				</table>
					<br>
					<input type="submit" id="ksubmit" name="Kreiraj" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 60px;">
					<input type="hidden" name="akcija" value="n">
			</form>
			<p>***Svi podaci su obavezni!</p>
		</div>
	</div>
<?php
	}
	
	if(isset($_REQUEST['Kreiraj'])){
			
		if($_REQUEST['vrsta']<=0 || !is_numeric($_REQUEST['vrsta'])){
			greska("Niste izabrali vrstu korisnika!");
		}
		else if($_REQUEST['adresa']<=0 || !is_numeric($_REQUEST['adresa'])){
			greska("Niste izabrali adresu korisnika!");
		}
		else{
			$korisnik = validacijaUlaznogPodatka($_REQUEST['korisnik']);
			$lozinka = validacijaUlaznogPodatka($_REQUEST['lozinka']);
			$lozinka = password_hash("$lozinka", PASSWORD_DEFAULT);
			$imeprezime = validacijaUlaznogPodatka($_REQUEST['imeprezime']);
			$telefon = validacijaUlaznogPodatka($_REQUEST['telefon']);
			$sqlQuery = "INSERT INTO korisnik (id, korisnik, lozinka, id_vrsta_kor, ime_prezime, id_adresa, telefon) VALUES 
				(null, '$korisnik', '$lozinka', ${_REQUEST['vrsta']}, '$imeprezime', ${_REQUEST['adresa']}, '$telefon')";
			$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("<br><br><br><br><br><br><br><br><br><br><br><br>Korisnik nije upisan u bazu! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");
		}
	}
	
	if($_REQUEST['akcija'] == 'b'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM korisnik WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Korisnik nije obrisan! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id korisnika!");
		}
	}
	
	if($_REQUEST['akcija'] == 'i'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM korisnik WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			
				$selectVrsta = "";
				$sqlQuery = "SELECT * FROM vrsta_kor ORDER BY naziv";
				$resV = mysqli_query($conn, $sqlQuery);
				while($rowV = mysqli_fetch_assoc($resV)){	
					$selectedVrsta = "";
					if($row['id_vrsta_kor'] == $rowV['id']){
						$selectedVrsta = "selected";
					}
					$selectVrsta .= "<option value=${rowV['id']} $selectedVrsta> ${rowV['naziv']} </option>";
				}
		
				$selectAdresa = "";
				$sqlQuery = "SELECT A.id as id, A.id_ulica, A.broj as broj, A.id_grad, U.naziv as ulica, G.naziv as grad
								FROM adresa A
								LEFT JOIN ulica U ON A.id_ulica=U.id
								LEFT JOIN grad G ON A.id_grad=G.id
								ORDER BY id";
				$resA = mysqli_query($conn, $sqlQuery);
				while($rowA = mysqli_fetch_assoc($resA)){	
					$selectedAdresa = "";
						if($row['id_adresa'] == $rowA['id']){
							$selectedAdresa = "selected";
						}	
					$selectAdresa .= "<option value=${rowA['id']} $selectedAdresa> ${rowA['ulica']} ${rowA['broj']}, ${rowA['grad']} </option>";
				}
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled korisnika</a>
						<br><br>
						<h4>Izmenite podatke korisnika:</h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px" style="margin-left: 10px;">
								<tr>
									<td class="naslov"><label>Korisnicko ime: </label></td>
									<td><input type="text" size="40" name="korisnik" required value="<?=$row['korisnik'];?>"></td>
								</tr>
								<tr>
									<td class="naslov"><label>Lozinka: </label></td>
									<td><input type="text" size="40" name="lozinka" required value=""></td>
								</tr>
								<tr>
									<td class="naslov"><label>Vrsta korisnika: </label></td>
									<td><select  name="vrsta"><?=$selectVrsta?></select></td>
								</tr>
								<tr>
									<td class="naslov"><label>Ime i prezime: </label></td>
									<td><input type="text" size="40" name="ime_prezime" required value="<?=$row['ime_prezime'];?>"></td>
								</tr>
								<tr>
									<td class="naslov"><label>Adresa: </label></td>
									<td><select  name="adresa"><?=$selectAdresa?></select></td>
								</tr>
								<tr>
									<td class="naslov"><label>Telefon: </label></td>
									<td><input type="text" size="40" name="telefon" required value="<?=$row['telefon'];?>"></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="Izmeni" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left:70px;">
							<input type="hidden" name="akcija" value="i">
							<input type="hidden" name="id" value="<?=$row['id'];?>">
						</form>
					</div>
				</div>
	<?php
			}
			
			if(isset($_REQUEST['Izmeni'])){
				$korisnik = validacijaUlaznogPodatka($_REQUEST['korisnik']);
				$lozinka = validacijaUlaznogPodatka($_REQUEST['lozinka']);
				$lozinka = password_hash($lozinka, PASSWORD_DEFAULT);
				$imeprezime = validacijaUlaznogPodatka($_REQUEST['ime_prezime']);
				$telefon = validacijaUlaznogPodatka($_REQUEST['telefon']);
				
				$sqlQuery = "UPDATE korisnik SET korisnik='$korisnik', lozinka='$lozinka', id_vrsta_kor=${_REQUEST['vrsta']}, 
						ime_prezime='$imeprezime', id_adresa=${_REQUEST['adresa']}, telefon='$telefon' WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Korisnik nije izmenjen! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
		}
	}
	
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
