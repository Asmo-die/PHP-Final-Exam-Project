<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
	if($_REQUEST['akcija'] == 'n'){
		
		$selectUlica = "<option value=''> - Izaberite ulicu - </option>";
		$sqlQuery = "SELECT * FROM ulica ORDER BY naziv";
		$resU = mysqli_query($conn, $sqlQuery);
		while($rowU = mysqli_fetch_assoc($resU)){	
			$selectedUlica = "";
			if(isset($_REQUEST['ulica'])){
				if($rowU['id'] == $_REQUEST['ulica'])
					$selectedUlica = "selected";
			}
			$selectUlica .= "<option value=${rowU['id']} $selectedUlica> ${rowU['naziv']} </option>";
		}
		
		$selectGrad = "<option value=''> - Izaberite grad - </option>";
		$sqlQuery = "SELECT * FROM grad ORDER BY naziv";
		$resG = mysqli_query($conn, $sqlQuery);
		while($rowG = mysqli_fetch_assoc($resG)){	
			$selectedGrad = "";
			if(isset($_REQUEST['grad'])){
				if($rowG['id'] == $_REQUEST['grad'])
					$selectedGrad = "selected";
			}
			$selectGrad .= "<option value=${rowG['id']} $selectedGrad> ${rowG['naziv']} </option>";
		}
		
?>
	<div id="podacikorisnikmain">
		<div id="podacikorisnik">
			<br>
			<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
			<br><br>
			<h4>Unesite podatke za novu adresu:</h4>			
			<form id="novikorisnik">
				<table cellpadding="2px" cellspacing="3px" style="margin-left:14px;">
					<tr>
						<td class="naslov"><label>Odaberite ulicu: </label></td>
						<td><select name="ulica" required><?=$selectUlica?></select></td>
					</tr>
					<tr>
						<td class="naslov"><label>Broj: </label></td>
						<td><input type="text" size="40" name="broj" required></td>
					</tr>
					<tr>
						<td class="naslov"><label>Odaberite grad: </label></td>
						<td><select name="grad" required><?=$selectGrad?></select></td>
					</tr>
				</table>
					<br>
					<input type="submit" id="ksubmit" name="Kreiraj" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 74px;">
					<input type="hidden" name="akcija" value="n">
			</form>
			<p>***Svi podaci su obavezni!</p>
		</div>
	</div>
<?php
	}
	
	if(isset($_REQUEST['Kreiraj'])){
			
		if($_REQUEST['ulica']<=0 || !is_numeric($_REQUEST['ulica'])){
			greska("Niste izabrali ulicu!");
		}
		else if($_REQUEST['grad']<=0 || !is_numeric($_REQUEST['grad'])){
			greska("Niste izabrali grad!");
		}
		else{
			$broj = validacijaUlaznogPodatka($_REQUEST['broj']);
			$sqlQuery = "INSERT INTO adresa (id, id_ulica, broj, id_grad) VALUES 
				(null, ${_REQUEST['ulica']}, '$broj', ${_REQUEST['grad']})";
			$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("<br><br><br><br><br><br><br><br><br><br><br><br>Adresa nije upisana u bazu! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");
		}
	}
	
	if($_REQUEST['akcija'] == 'b'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM adresa WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Adresa nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id adrese!");
		}
	}
	
	if($_REQUEST['akcija'] == 'i'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM adresa WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			
				$selectUlica = "";
				$sqlQuery = "SELECT * FROM ulica ORDER BY naziv";
				$resU = mysqli_query($conn, $sqlQuery);
				while($rowU = mysqli_fetch_assoc($resU)){	
					$selectedUlica = "";
					if($row['id_ulica'] == $rowU['id']){
						$selectedUlica = "selected";
					}
					$selectUlica .= "<option value=${rowU['id']} $selectedUlica> ${rowU['naziv']} </option>";
				}
		
				$selectGrad = "";
				$sqlQuery = "SELECT * FROM grad ORDER BY naziv";
				$resG = mysqli_query($conn, $sqlQuery);
				while($rowG = mysqli_fetch_assoc($resG)){	
					$selectedGrad = "";
						if($row['id_grad'] == $rowG['id']){
							$selectedGrad = "selected";
						}	
					$selectGrad .= "<option value=${rowG['id']} $selectedGrad> ${rowG['naziv']} </option>";
				}
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
						<br><br>
						<h4>Izmenite podatke adrese: </h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px" style="margin-left: 25px;">
								<tr>
									<td class="naslov"><label>Naziv ulice: </label></td>
									<td><select  name="ulica"><?=$selectUlica?></select></td>
								</tr>
								<tr>
									<td class="naslov"><label>Broj: </label></td>
									<td><input type="text" size="20" name="broj" required value="<?=$row['broj'];?>"></td>
								</tr>
								<tr>
									<td class="naslov"><label>Naziv grada: </label></td>
									<td><select  name="grad"><?=$selectGrad?></select></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="Izmeni" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 62px;">
							<input type="hidden" name="akcija" value="i">
							<input type="hidden" name="id" value="<?=$row['id'];?>">
						</form>
					</div>
				</div>
	<?php
			}
			
			if(isset($_REQUEST['Izmeni'])){
				
				$broj = validacijaUlaznogPodatka($_REQUEST['broj']);
				
				$sqlQuery = "UPDATE adresa SET id_ulica=${_REQUEST['ulica']}, broj='$broj', id_grad=${_REQUEST['grad']} WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Adresa nije izmenjena! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
		}
	}
	
	if($_REQUEST['akcija'] == 'nu'){
	?>
		<div id="podacikorisnikmain">
			<div id="podacikorisnik">
				<br>
				<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
				<br><br>
				<h4>Unesite naziv za novu ulicu:</h4>			
				<form id="novikorisnik">
					<table cellpadding="2px" cellspacing="3px" style="margin-left:26px;">
						<tr>
							<td class="naslov"><label>Naziv ulice: </label></td>
							<td><input type="text" size="40" name="ulica" required></td>
						</tr>
					</table>
					<br>
					<input type="submit" id="ksubmit" name="KreirajU" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 54px;">
					<input type="hidden" name="akcija" value="nu">
				</form>
				<p>***Svi podaci su obavezni!</p>
			</div>
		</div>
	<?php
	}
	
	if(isset($_REQUEST['KreirajU'])){
			
		$ulica = validacijaUlaznogPodatka($_REQUEST['ulica']);
		$sqlQuery = "INSERT INTO ulica (id, naziv) VALUES (null, '$ulica')";
		$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("<br><br><br><br><br><br><br><br><br><br><br><br>Ulica nije upisana u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
	
	}
	
	if($_REQUEST['akcija'] == 'bu'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM ulica WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Ulica nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id ulice!");
		}
	}
	
	if($_REQUEST['akcija'] == 'iu'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM ulica WHERE id=".$_REQUEST['id'];
			$resU = mysqli_query($conn, $sqlQuery);
			while($rowU = mysqli_fetch_assoc($resU)){	
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
						<br><br>
						<h4>Unesite novi naziv za ulicu:</h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px" style="margin-left:28px">
								<tr>
									<td class="naslov"><label>Naziv ulice: </label></td>
									<td><input type="text" size="40" name="ulica" required value="<?=$rowU['naziv']?>"></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="IzmeniU" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left:57px;">
							<input type="hidden" name="akcija" value="iu">
							<input type="hidden" name="id" value="<?=$rowU['id'];?>">
						</form>
						<p>***Svi podaci su obavezni!</p>
					</div>
				</div>
	<?php
			}
		}
		
		if(isset($_REQUEST['IzmeniU'])){
				
				$ulica = validacijaUlaznogPodatka($_REQUEST['ulica']);
				
				$sqlQuery = "UPDATE ulica SET naziv='$ulica' WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Ulica nije izmenjena! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
	}
	
	if($_REQUEST['akcija'] == 'ng'){
	?>
		<div id="podacikorisnikmain">
			<div id="podacikorisnik">
				<br>
				<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
				<br><br>
				<h4>Unesite naziv za novi grad:</h4>			
				<form id="novikorisnik">
					<table cellpadding="2px" cellspacing="3px" style="margin-left:26px;">
						<tr>
							<td class="naslov"><label>Naziv grada: </label></td>
							<td><input type="text" size="40" name="grad" required></td>
						</tr>
					</table>
					<br>
					<input type="submit" id="ksubmit" name="KreirajG" value="Submit" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left: 63px;">
					<input type="hidden" name="akcija" value="ng">
				</form>
				<p>***Svi podaci su obavezni!</p>
			</div>
		</div>
	<?php
	}
	
	if(isset($_REQUEST['KreirajG'])){
			
		$grad = validacijaUlaznogPodatka($_REQUEST['grad']);
		$sqlQuery = "INSERT INTO grad (id, naziv) VALUES (null, '$grad')";
		$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("<br><br><br><br><br><br><br><br><br><br><br><br>Grad nije upisan u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
	
	}
	
	if($_REQUEST['akcija'] == 'bg'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM grad WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			
			if(!$res)
				greska("Grad nije obrisan! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
		}
		else{
			greska("Nije korektan id grada!");
		}
	}
	
	if($_REQUEST['akcija'] == 'ig'){
		
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "SELECT * FROM grad WHERE id=".$_REQUEST['id'];
			$resG = mysqli_query($conn, $sqlQuery);
			while($rowG = mysqli_fetch_assoc($resG)){	
	?>
				<div id="podacikorisnikmain">
					<div id="podacikorisnik">
						<br>
						<a class="linknazad" href="pregled.php">&#8666; Povratak na pregled adresa</a>
						<br><br>
						<h4>Unesite novi naziv za grad:</h4>			
						<form id="novikorisnik">
							<table cellpadding="2px" cellspacing="3px" style="margin-left:28px">
								<tr>
									<td class="naslov"><label>Naziv grada: </label></td>
									<td><input type="text" size="20" name="grad" required value="<?=$rowG['naziv']?>"></td>
								</tr>
							</table>
							<br>
							<input type="submit" id="ksubmit" name="IzmeniG" value="Izmeni" style="background-color:#1a8cff; color:white; width:60px; height:26px; margin-left:65px;">
							<input type="hidden" name="akcija" value="ig">
							<input type="hidden" name="id" value="<?=$rowG['id'];?>">
						</form>
						<p>***Svi podaci su obavezni!</p>
					</div>
				</div>
	<?php
			}
		}
		
		if(isset($_REQUEST['IzmeniG'])){
				
				$grad = validacijaUlaznogPodatka($_REQUEST['grad']);
				
				$sqlQuery = "UPDATE grad SET naziv='$grad' WHERE id=${_REQUEST['id']}";
					
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Grad nije izmenjen! <br> opis: ".mysqli_error($conn));
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
	