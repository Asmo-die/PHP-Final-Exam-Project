<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
	if($_REQUEST['akcija'] == 'n'){
		
		$selectVrsta = "<option value=''> Izaberite vrstu usluge </option>";
		$sqlQuery = "SELECT * FROM vrsta_usluge ORDER BY naziv";
		$resV = mysqli_query($conn, $sqlQuery);
		while($rowV = mysqli_fetch_assoc($resV)){	
			$selectedVrsta = "";
			if(isset($_REQUEST['vrsta'])){
				if($rowV['id'] == $_REQUEST['vrsta'])
					$selectedVrsta = "selected";
			}
			$selectVrsta .= "<option value=${rowV['id']} $selectedVrsta> ${rowV['naziv']} </option>";
		}
?>
	<div id="podacikorisnikmain">
		<div id="podacikorisnik">
			<br>
			<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled usluga</a>
			<br><br>
			<h4>Unesite podatke za novu uslugu:</h4>			
			<form id="novikorisnik">
				<table cellpadding="2px" cellspacing="3px" style="">
					<tr>
						<td class="naslov"><label>Naziv usluge: </label></td>
						<td><input type="text" size="40" name="naziv" required></td>
					</tr>
					<tr>
						<td class="naslov"><label>Odaberite vrstu usluge: </label></td>
						<td><select name="vrsta" required><?=$selectVrsta?></select></td>
					</tr>
					<tr>
						<td class="naslov"><label>Trajanje usluge (min.): </label></td>
						<td><input type="text" size="40" name="trajanje" required></td>
					</tr>
					<tr>
						<td class="naslov"><label>Cena usluge (din.): </label></td>
						<td><input type="text" size="40" name="cena" required></td>
					</tr>
				</table>
					<br>
					<input type="submit" id="ksubmit" name="Kreiraj" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 109px;">
					<input type="hidden" name="akcija" value="n">
			</form>
			<p>***Svi podaci su obavezni!</p>
		</div>
	</div>
<?php
	}
	
	if(isset($_REQUEST['Kreiraj'])){
			
		if($_REQUEST['vrsta']<=0 || !is_numeric($_REQUEST['vrsta'])){
			greska("Niste izabrali vrstu!");
		}
		else if(!is_numeric($_REQUEST['trajanje'])){
			greska("Niste upisali ispravnu vrednost za trajanje usluge!");
		}
		else if(!is_numeric($_REQUEST['cena'])){
			greska("Niste upisali ispravnu vrednost za cenu usluge!");
		}
		else{
			$naziv = validacijaUlaznogPodatka($_REQUEST['naziv']);
			$trajanje = (validacijaUlaznogPodatka($_REQUEST['trajanje'])*60);
			$cena = validacijaUlaznogPodatka($_REQUEST['cena']);
			$sqlQuery = "INSERT INTO usluga (id, naziv, id_vrsta_usluge, trajanje, cena) VALUES 
				(null, '$naziv', ${_REQUEST['vrsta']}, '$trajanje', '$cena')";
			$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("<br><br><br><br><br><br><br><br><br><br><br><br>Usluga nije upisana u bazu! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");
		}
	}
	
	if($_REQUEST['akcija'] == 'b'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM usluga WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Usluga nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id usluge!");
		}
	}

	if($_REQUEST['akcija'] == 'i'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM usluga WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			
				$selectVrsta = "";
				$sqlQuery = "SELECT * FROM vrsta_usluge ORDER BY naziv";
				$resV = mysqli_query($conn, $sqlQuery);
				while($rowV = mysqli_fetch_assoc($resV)){	
					$selectedVrsta = "";
					if($row['id_vrsta_usluge'] == $rowV['id']){
						$selectedVrsta = "selected";
					}
					$selectVrsta .= "<option value=${rowV['id']} $selectedVrsta> ${rowV['naziv']} </option>";
				}
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled usluga</a>
						<br><br>
						<h4>Izmenite podatke usluge: </h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px">
								<tr>
									<td class="naslov"><label>Naziv usluge: </label></td>
									<td><input type="text" size="40" name="naziv" required value="<?=$row['naziv'];?>"></td>
								</tr>
								<tr>
									<td class="naslov"><label>Vrsta usluge: </label></td>
									<td><select  name="vrsta"><?=$selectVrsta?></select></td>
								</tr>
								<tr>
									<td class="naslov"><label>Trajanje usluge (min.): </label></td>
									<td><input type="text" size="40" name="trajanje" required value="<?=$row['trajanje']/60;?>"></td>
								</tr>
								<tr>
									<td class="naslov"><label>Cena usluge (din.): </label></td>
									<td><input type="text" size="40" name="cena" required value="<?=$row['cena'];?>"></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="Izmeni" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 107px;">
							<input type="hidden" name="akcija" value="i">
							<input type="hidden" name="id" value="<?=$row['id'];?>">
						</form>
						<p>***Svi podaci su obavezni!</p>
					</div>
				</div>
	<?php
			}
			
			if(isset($_REQUEST['Izmeni'])){
				
				$naziv = validacijaUlaznogPodatka($_REQUEST['naziv']);
				$trajanje = (validacijaUlaznogPodatka($_REQUEST['trajanje'])*60);
				$cena = validacijaUlaznogPodatka($_REQUEST['cena']);
				
				$sqlQuery = "UPDATE usluga SET naziv='$naziv', id_vrsta_usluge=${_REQUEST['vrsta']}, trajanje='$trajanje', cena='$cena' WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Usluga nije izmenjena! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
		}
	}
	
	if($_REQUEST['akcija'] == 'nv'){
	?>
		<div id="podacikorisnikmain">
			<div id="podacikorisnik">
				<br>
				<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled usluga</a>
				<br><br>
				<h4>Unesite naziv za novu vrstu usluge:</h4>			
				<form id="novikorisnik">
					<table cellpadding="2px" cellspacing="3px" style="margin-left:10px;">
						<tr>
							<td class="naslov"><label>Naziv vrste usluge: </label></td>
							<td><input type="text" size="40" name="vrsta" required></td>
						</tr>
					</table>
					<br>
					<input type="submit" id="ksubmit" name="KreirajV" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left:88px;">
					<input type="hidden" name="akcija" value="nv">
				</form>
				<p>***Svi podaci su obavezni!</p>
			</div>
		</div>
	<?php
	}
	
	if(isset($_REQUEST['KreirajV'])){
			
		$vrsta = validacijaUlaznogPodatka($_REQUEST['vrsta']);
		$sqlQuery = "INSERT INTO vrsta_usluge (id, naziv) VALUES (null, '$vrsta')";
		$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("<br><br><br><br><br><br><br><br><br><br><br><br>Vrsta usluge nije upisana u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
	
	}
	
	if($_REQUEST['akcija'] == 'bv'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM vrsta_usluge WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Vrsta usluge nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id vrste usluge!");
		}
	}
	
	if($_REQUEST['akcija'] == 'iv'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM vrsta_usluge WHERE id=".$_REQUEST['id'];
			$resV = mysqli_query($conn, $sqlQuery);
			while($rowV = mysqli_fetch_assoc($resV)){	
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled usluga</a>
						<br><br>
						<h4>Unesite novi naziv za vrstu usluge:</h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px" style="margin-left:11px">
								<tr>
									<td class="naslov"><label>Naziv vrste usluge: </label></td>
									<td><input type="text" size="40" name="vrsta" required value="<?=$rowV['naziv']?>"></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="IzmeniV" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left:90px;">
							<input type="hidden" name="akcija" value="iv">
							<input type="hidden" name="id" value="<?=$rowV['id'];?>">
						</form>
						<p>***Svi podaci su obavezni!</p>
					</div>
				</div>
	<?php
			}
		}
		
		if(isset($_REQUEST['IzmeniV'])){
				
				$vrsta = validacijaUlaznogPodatka($_REQUEST['vrsta']);
				
				$sqlQuery = "UPDATE vrsta_usluge SET naziv='$vrsta' WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Vrsta usluge nije izmenjena! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
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