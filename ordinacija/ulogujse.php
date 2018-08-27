<?php 
	require "inc/konekcija.php";
	require "inc/header.php";
?>		
		<div id="loginmain">
			<div id="login">
				<form id="logovanje" name="logovanje" method="post" action=<?=htmlspecialchars($_SERVER['PHP_SELF'])?>>
					<br>
					<label>Korisnik: </label><br>
					<input type="text" name="korisnik" maxlength="30" value="<?=(isset($_REQUEST['korisnik']))?$_REQUEST['korisnik']:""?>" required placeholder="Vaše korisnicko ime"><br><br>
					<label>Lozinka: </label><br>
					<input type="password" name="lozinka" maxlength="30" value="<?=(isset($_REQUEST['lozinka']))?$_REQUEST['lozinka']:""?>" required placeholder="Vaša lozinka"><br><br>
					<input type="submit" name="ulogujse" value="Uloguj se">
				</form>
			</div>
				
			<div id="loginslika"></div>
			
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
<?php
	if(isset($_REQUEST['ulogujse'])){
		
		$korisnik = validacijaUlaznogPodatka($_POST['korisnik']);
		$lozinka = validacijaUlaznogPodatka($_POST['lozinka']);
		
		$sqlQuery = "SELECT * FROM korisnik WHERE korisnik='$korisnik'";

		$res = mysqli_query($conn, $sqlQuery);
		if(mysqli_num_rows($res) == 0){
			greska("Pogresno korisnicko ime ili sifra!");
		}
		
		if($row = mysqli_fetch_assoc($res)){
			if(password_verify($lozinka, $row['lozinka'])){
			session_start();
			$_SESSION['id'] = $row['id'];
			$_SESSION['korisnik'] = $row['korisnik'];
			$_SESSION['vrsta_korisnika'] = $row['id_vrsta_kor'];
				if($_SESSION['vrsta_korisnika'] == 1){	
					header("Location: podaci.php");
				}
				else if($_SESSION['vrsta_korisnika'] == 2){
					header("Location: pregledid.php");
				}
				else{
					header("Location: pregledip.php");
				}
			
			}
			else{
				greska("Pogresno korisnicko ime ili sifra!");
			}
		}
	}
?>
