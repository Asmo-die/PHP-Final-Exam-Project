<?php
	require "../../inc/sesija.php";
	require "../../inc/header.php";
	if(!isset($_SESSION['korisnik'])){
		header("Location: ../../ulogujse.php");  
	}
?>
<div id="podaciadresamain">
    <div id="podaciadresa">
        <br>
        <a class="linknazad" href="../../podaci.php">&#8666; Povratak na upravljanje podacima</a>
        <br><br>
        <p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=n">UNOS NOVE ADRESE</a></p><br>
        <form action="" name="filtriranje" style="margin-left:12px;">
            <?php
            if (isset($_REQUEST['reset1'])) {
                $_REQUEST['fltAdr'] = '';
            }
            ?>
            &nbsp; Adresa: <input type="text" size="15" name="fltAdr" value=<?= (isset($_REQUEST['fltAdr'])) ? $_REQUEST['fltAdr'] : ""; ?>>&nbsp;
            <input type="submit" name="filtrirajA" value="Primeni filter">
            <input type="submit" name="reset1" value="Resetuj filter">
        </form>
        <br>
        <table align="center" border="1" width="97%">
            <tr align="center" class="naslov">
                <td width="5%">ID</td>
                <td>ULICA</td>
                <td width="10%">BROJ</td>
                <td>GRAD</td>
                <td width="10%">IZMENI</td>
                <td width="10%">OBRISI</td>
            </tr>
            <?php
            $sqlUslovi = " 1=1 ";
            if (isset($_REQUEST['filtrirajA'])) {
                if ($_REQUEST['fltAdr'] != "") {
                    $rec = validacijaUlaznogPodatka($_REQUEST['fltAdr']);
                    $sqlUslovi .= " AND U.naziv LIKE '%$rec%' OR G.naziv LIKE '%$rec%' OR A.broj LIKE '%$rec%'";
                }
            }

            $sqlQuery = "SELECT A.*, U.naziv AS ulica, G.naziv AS grad
								FROM adresa A
									LEFT JOIN ulica U ON A.id_ulica=U.id
									LEFT JOIN grad G ON A.id_grad=G.id
								WHERE $sqlUslovi
								ORDER BY grad";
            $res = mysqli_query($conn, $sqlQuery);
            while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <tr align="center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['ulica'] ?></td>
                    <td><?= $row['broj'] ?></td>
                    <td><?= $row['grad'] ?></td>
                    <td><a href="azuriraj.php?akcija=i&id=<?= $row['id'] ?>">Izmena</a></td>
                    <td><a href="azuriraj.php?akcija=b&id=<?= $row['id'] ?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš adresu <?= $row['ulica']
            . " " . $row['broj'] . ", " . $row['grad']
                ?>?');">Brisanje</a></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br><br><br>
        <p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=nu">UNOS NOVE ULICE</a></p><br>
        <form action="" name="filtriranje" style="margin-left:12px;">
            <?php
            if (isset($_REQUEST['reset2'])) {
                $_REQUEST['fltUl'] = '';
            }
            ?>
            &nbsp; Ulica: <input type="text" size="15" name="fltUl" value=<?= (isset($_REQUEST['fltUl'])) ? $_REQUEST['fltUl'] : ""; ?>>&nbsp;
            <input type="submit" name="filtrirajU" value="Primeni filter">
            <input type="submit" name="reset2" value="Resetuj filter">
        </form>
        <br>
        <table align="left" border="1" width="65%" style="margin-left: 18px;">
            <tr align="center" class="naslov">
                <td width="8%">ID</td>
                <td>NAZIV ULICE</td>
                <td width="15%">IZMENI</td>
                <td width="15%">OBRISI</td>
            </tr>
            <?php
            $sqlUsloviU = " 1=1 ";
            if (isset($_REQUEST['filtrirajU'])) {
                if ($_REQUEST['fltUl'] != "") {
                    $recU = validacijaUlaznogPodatka($_REQUEST['fltUl']);
                    $sqlUsloviU .= " AND naziv LIKE '%$recU%'";
                }
            }

            $sqlQueryU = "SELECT * FROM ulica WHERE $sqlUsloviU ORDER BY id";
            $resU = mysqli_query($conn, $sqlQueryU);
            while ($rowU = mysqli_fetch_assoc($resU)) {
                ?>
                <tr align="center">
                    <td><?= $rowU['id'] ?></td>
                    <td><?= $rowU['naziv'] ?></td>
                    <td><a href="azuriraj.php?akcija=iu&id=<?= $rowU['id'] ?>">Izmena</a></td>
                    <td><a href="azuriraj.php?akcija=bu&id=<?= $rowU['id'] ?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš ulicu <?= $rowU['naziv'] ?>?');">Brisanje</a></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br><br><br><br><br><br><br><br><br>
        <p style="text-align:left; margin-left:20px;"><a href="azuriraj.php?akcija=ng">UNOS NOVOG GRADA</a></p><br>
        <form action="" name="filtriranje" style="margin-left:12px;">
            <?php
            if (isset($_REQUEST['reset3'])) {
                $_REQUEST['fltGr'] = '';
            }
            ?>
            &nbsp; Grad: <input type="text" size="15" name="fltGr" value=<?= (isset($_REQUEST['fltGr'])) ? $_REQUEST['fltGr'] : ""; ?>>&nbsp;
            <input type="submit" name="filtrirajG" value="Primeni filter">
            <input type="submit" name="reset3" value="Resetuj filter">
           
        </form>
        <br>
        <table align="left" border="1" width="65%" style="margin-left: 18px;">
            <tr align="center" class="naslov">
                <td width="8%">ID</td>
                <td>NAZIV GRADA</td>
                <td width="15%">IZMENI</td>
                <td width="15%">OBRISI</td>
            </tr>
            <?php
            $sqlUsloviG = " 1=1 ";
            if (isset($_REQUEST['filtrirajG'])) {
                if ($_REQUEST['fltGr'] != "") {
                    $recG = validacijaUlaznogPodatka($_REQUEST['fltGr']);
                    $sqlUsloviG .= " AND naziv LIKE '%$recG%'";
                }
            }

            $sqlQueryG = "SELECT * FROM grad WHERE $sqlUsloviG ORDER BY id";
            $resG = mysqli_query($conn, $sqlQueryG);
            while ($rowG = mysqli_fetch_assoc($resG)) {
                ?>
                <tr align="center">
                    <td><?= $rowG['id'] ?></td>
                    <td><?= $rowG['naziv'] ?></td>
                    <td><a href="azuriraj.php?akcija=ig&id=<?= $rowG['id'] ?>">Izmena</a></td>
                    <td><a href="azuriraj.php?akcija=bg&id=<?= $rowG['id'] ?>" onClick="return potvrdiIPosalji('Da li želiš da obrišeš grad <?= $rowG['naziv'] ?>?');">Brisanje</a></td>
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

