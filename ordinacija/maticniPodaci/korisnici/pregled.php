<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
	
			if(isset($_REQUEST['reset2'])){
				$_REQUEST['fltVrsta'] = '';
			}
	
			$selectVrsta = "<option value=''> - Izaberite vrstu - </option>";
			$sqlQuery = "SELECT * FROM vrsta_kor ORDER BY naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectedVrsta = "";
				if(isset($_REQUEST['fltVrsta'])){
					if($row['id'] == $_REQUEST['fltVrsta'])
						$selectedVrsta = "selected";
				}
				$selectVrsta .= "<option value=${row['id']} $selectedVrsta> ${row['naziv']} </option>";
			}
?>
		<div id="podacikorisnikmain">
			<div id="podacikorisnik">
				<br>
				<a class="linknazad" href="../../podaci.php">&#8666; Povratak na upravljanje podacima</a>
				<br><br>
				<p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=n">UNOS NOVOG KORISNIKA</a></p><br>
			<?php
				if(isset($_REQUEST['reset'])){
					$_REQUEST['fltKor'] = "";
				}
			?>
				<form action="" name="filtriranje" style="margin-left:12px;">
					&nbsp; Korisnik: <input type="text" size="15" name="fltKor" value=<?=(isset($_REQUEST['fltKor']))?$_REQUEST['fltKor']:"";?>>&nbsp;
					<input type="submit" name="filtriraj" value="Primeni filter">
					<input type="submit" name="reset" value="Resetuj filter">
			
				</form>
				<br>
				<form action="" name="filtriranje" style="margin-left:20px;">
					Vrsta korisnika: <select name="fltVrsta"><?=$selectVrsta?></select>
					<input type="submit" name="filtrirajV" value="Primeni filter">
					<input type="submit" name="reset2" value="Resetuj filter">
				</form>
				<br>
				<table align="center" border="1" width="97%">
					<tr align="center" class="naslov">
						<td>ID</td>
						<td>KORISNIK</td>
						<td>LOZINKA</td>
						<td>VRSTA KOR.</td>
						<td>IME I PREZIME</td>
						<td>ADRESA</td>
						<td>TELEFON</td>
						<td>IZMENI</td>
						<td>OBRISI</td>
					</tr>
				<?php
					$sqlUslovi = " 1=1 ";
					
					if(isset($_REQUEST['filtriraj'])){
						if($_REQUEST['fltKor']!=""){
							$imeprezime = validacijaUlaznogPodatka($_REQUEST['fltKor']);
							$sqlUslovi .= " AND ime_prezime LIKE '%$imeprezime%'";
						}
					}
					
					if(isset($_REQUEST['filtrirajV'])){
						if($_REQUEST['fltVrsta']!=""){
							$vrsta = validacijaUlaznogPodatka($_REQUEST['fltVrsta']);
							$sqlUslovi .= " AND k.id_vrsta_kor = $vrsta";
						}
					}
					
					$sqlQuery = "SELECT k.id as id, k.korisnik as korisnik, k.lozinka as lozinka, k.id_vrsta_kor, k.id_adresa as adresa, v.naziv as vrsta, k.telefon as telefon,
									k.ime_prezime as ime_prezime, u.naziv as ulica, g.naziv as grad, a.broj as broj
								FROM korisnik k
									LEFT JOIN vrsta_kor v ON k.id_vrsta_kor=v.id
									LEFT JOIN adresa a ON k.id_adresa=a.id
									LEFT JOIN ulica u ON a.id_ulica=u.id
									LEFT JOIN grad g ON a.id_grad=g.id
								WHERE $sqlUslovi
								ORDER BY id";
								
					$res = mysqli_query($conn, $sqlQuery);
					while($row = mysqli_fetch_assoc($res)){	
				?>
					<tr align="center">
						<td><?=$row['id']?></td>
						<td><?=$row['korisnik']?></td>
						<td> *** </td>
						<td><?=$row['vrsta']?></td>
						<td><?=$row['ime_prezime']?></td>
						<td><?=$row['ulica'] ." ". $row['broj'] .", ". $row['grad']?></td>
						<td><?=$row['telefon']?></td>
						<td><a href="azuriraj.php?akcija=i&id=<?=$row['id']?>">Izmena</a></td>
						<td><a href="azuriraj.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš korisnika <?=$row['korisnik']?>?');">Brisanje</a></td>
						
					</tr>
				<?php
					}
				?>
				</table>
		
				<br><br><br>
				<table border="1" width="33%" style="margin-left:17px">
					<tr align="center" class="naslov">
						<td>ID</td>
						<td>VRSTA KORISNIKA</td>
					</tr>
				<?php
					$sqlQuery = "SELECT * FROM vrsta_kor ORDER BY id";
					$res = mysqli_query($conn, $sqlQuery);
					while($row = mysqli_fetch_assoc($res)){	
				?>
					<tr align="center">
						<td><?=$row['id']?></td>
						<td><?=$row['naziv']?></td>
					</tr>
				<?php
					}
				?>
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
