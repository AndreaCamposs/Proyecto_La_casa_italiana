<?php 
include_once("conexion.php");
include_once("indexcopy.php");

$pagina = $_GET['pag'];
$coddni = $_GET['user_id'];

$querybuscar = query($conn, "SELECT * FROM user_info WHERE user_id=$coddni");
 
while($mostrar = mysqli_fetch_array($querybuscar))
{
	$usunom 	= $mostrar['nombres'];
	$usudni 	= $mostrar['apellidos'];
    $usudir 	= $mostrar['direccion'];
	$usutel 	= $mostrar['telefono'];
    $usucorreo 	= $mostrar['correo'];
    $usupais 	= $mostrar['pais'];
    $usuclave 	= $mostrar['clave'];
}
?>
<html>
<head>    
		<title>MiniTiendaOnline</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="caja_popup2">
  <form class="contenedor_popup" method="POST">
        <table>
		<tr><th colspan="2">Ver usuario</th></tr>
            <tr> 
                <td>Nombre: </td>
                <td><?php echo $usunom;?></td>
            </tr>
			   <tr> 
                <td>Apellidos: </td>
                <td><?php echo $usudni;?></td>
            </tr>
        
            <tr> 
                <td>Dirección: </td>
                <td><?php echo $usudir;?></td>
            </tr>
			  <tr> 
                <td>Telefono: </td>
                <td><?php echo $usutel;?></td>
            </tr>
			  <tr> 
                <td>Correo: </td>
                <td><?php echo $usucorreo;?></td>
            </tr>
            <tr> 
                <td>Pais: </td>
                <td><?php echo $usuclave;?></td>
            </tr>
            <tr> 
                <td>Clave: </td>
                <td><?php echo $usupais;?></td>
            </tr>
            
            <tr>
				
                <td colspan="2">
				 <?php echo "<a href=\"indexcopy.php?pag=$pagina\">Regresar</a>";?>
				</td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>


	