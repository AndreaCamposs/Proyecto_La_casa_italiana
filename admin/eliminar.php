<?php
include_once("conexion.php");

$pagina = $_GET['pag'];
$coddni = $_GET['dni'];

mysqli_query($conn, "DELETE FROM user_info WHERE user_id=$coddni");
 
header("Location:index.php?pag=$pagina");

?>