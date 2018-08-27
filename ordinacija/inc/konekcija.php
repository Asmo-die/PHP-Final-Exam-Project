<?php
require 'konstante.php';
require 'funkcije.php';

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_BASE);
if(!$conn)
    die(greska("Nismo mogli da uspostavimo konekciju ka bazi ".DB_BASE.", opis: ".mysqli_connect_error()));

?>