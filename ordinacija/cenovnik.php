<?php 
	session_start();
	require "inc/konekcija.php";
	require "inc/header.php";
?>
	<div id="cenovnikmain">
		<div id="zaglavljeslikacenovnik"></div>
		
		<div id="cenovnik">
			<table id="cenovnikt" align="center" width="100%">
				<?php
				
				
				
				$sqlQuery = "SELECT U.id AS id, U.naziv AS naziv, U.trajanje AS trajanje, U.cena AS cena, U.id_vrsta_usluge, V.naziv AS naslov
							FROM usluga U
							LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge = V.id
							ORDER BY id_vrsta_usluge";
				$res = mysqli_query($conn, $sqlQuery);
				$pomocna = 0;
				while($row = mysqli_fetch_assoc($res)){	
					if($pomocna != $row['id_vrsta_usluge']){     // provera da bi se naziv vrste usluge ispisao samo jednom
						?>
					<tr class="blank"></tr>
					<th colspan="3"><?=$row['naslov']?></th>
					<tr class="blank"></tr>
						<?php
						}
						?>
					<tr>
						<td><?=$row['naziv']?></td>
						<td align="right"><?=($row['trajanje']/60)?> min.</td>
						<td align="right"><?=$row['cena']?> din.</td>
					</tr>
						<?php	
					$pomocna = $row['id_vrsta_usluge'];
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
			<a href="#">Back to top</a>
		</footer>
	</body>
</html>