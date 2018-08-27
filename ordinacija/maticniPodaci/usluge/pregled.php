<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
?>
	<div id="podaciuslugamain">
			<div id="podaciusluga">
				<br>
				<a class="linknazad" href="../../podaci.php">&#8666; Povratak na upravljanje podacima</a>
				<br><br>
				<p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=n">UNOS NOVE USLUGE</a></p><br>
			<?php
				if(isset($_REQUEST['reset1'])){
					$_REQUEST['fltUsl'] = "";
				}
			?>
				<form action="" name="filtriranje" style="margin-left:12px;">
					&nbsp; Usluga/ Vrsta usluge: <input type="text" size="15" name="fltUsl" value=<?=(isset($_REQUEST['fltUsl']))?$_REQUEST['fltUsl']:"";?>>&nbsp;
					<input type="submit" name="filtrirajU" value="Primeni filter">
					<input type="submit" name="reset1" value="Resetuj filter">
				</form>
				<br>
				<table align="center" border="1" width="97%">
					<tr align="center" class="naslov">
						<td>ID</td>
						<td>NAZIV USLUGE</td>
						<td>VRSTA USLUGE</td>
						<td>TRAJANJE</td>
						<td>CENA</td>
						<td>IZMENI</td>
						<td>OBRISI</td>
					</tr>
			<?php
				$sqlUslovi = " 1=1 ";
					if(isset($_REQUEST['filtrirajU'])){
						if($_REQUEST['fltUsl']!=""){
							$rec = validacijaUlaznogPodatka($_REQUEST['fltUsl']);
							$sqlUslovi .= " AND U.naziv LIKE '%$rec%' OR V.naziv LIKE '%$rec%'";
						}
					}
					
					$sqlQuery = "SELECT U.*, V.naziv AS vrsta
								FROM usluga U
									LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge=V.id
								WHERE $sqlUslovi
								ORDER BY vrsta";
					$res = mysqli_query($conn, $sqlQuery);
					while($row = mysqli_fetch_assoc($res)){	
				?>
					<tr align="center">
						<td><?=$row['id']?></td>
						<td><?=$row['naziv']?></td>
						<td><?=$row['vrsta']?></td>
						<td><?=$row['trajanje']/60?> min.</td>
						<td><?=$row['cena']?> din.</td>
						<td><a href="azuriraj.php?akcija=i&id=<?=$row['id']?>">Izmena</a></td>
						<td><a href="azuriraj.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš uslugu <?=$row['naziv']?>?');">Brisanje</a></td>
					</tr>
				<?php
					}
				?>
				</table>
				<br><br><br>
				<p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=nv">UNOS NOVE VRSTE USLUGE</a></p><br>
			<?php
				if(isset($_REQUEST['reset2'])){
					$_REQUEST['fltVr'] = "";
				}
			?>
				<form action="" name="filtriranje" style="margin-left:12px;">
					&nbsp; Vrsta usluge: <input type="text" size="15" name="fltVr" value=<?=(isset($_REQUEST['fltVr']))?$_REQUEST['fltVr']:"";?>>&nbsp;
					<input type="submit" name="filtrirajV" value="Primeni filter">
					<input type="submit" name="reset2" value="Resetuj filter">
				</form>
				<br>
				<table align="left" border="1" width="65%" style="margin-left: 18px;">
					<tr align="center" class="naslov">
						<td>ID</td>
						<td>VRSTA USLUGE</td>
						<td>IZMENI</td>
						<td>OBRISI</td>
					</tr>
				<?php
				$sqlUsloviV = " 1=1 ";
					if(isset($_REQUEST['filtrirajV'])){
						if($_REQUEST['fltVr']!=""){
							$recV = validacijaUlaznogPodatka($_REQUEST['fltVr']);
							$sqlUsloviV .= " AND naziv LIKE '%$recV%'";
						}
					}
					
					$sqlQueryV = "SELECT * FROM vrsta_usluge WHERE $sqlUsloviV ORDER BY id";
					$resV = mysqli_query($conn, $sqlQueryV);
					while($rowV = mysqli_fetch_assoc($resV)){	
				?>
					<tr align="center">
						<td><?=$rowV['id']?></td>
						<td><?=$rowV['naziv']?></td>
						<td><a href="azuriraj.php?akcija=iv&id=<?=$rowV['id']?>">Izmena</a></td>
						<td><a href="azuriraj.php?akcija=bv&id=<?=$rowV['id']?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš vrstu usluge <?=$rowV['naziv']?>?');">Brisanje</a></td>
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