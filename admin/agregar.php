<?php 
include_once("conexion.php"); 
include_once("indexcopy.php");

$pagina = $_GET['pag'];
?>
<html>
<head>    
		<title>Mi Casa Italiana</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="caja_popup2"> 
  <form class="contenedor_popup" method="POST">
        <table>
		<tr><th colspan="2" >Agregar cliente</th></tr>
        <tr>
                <td>Nombre</td>
                <td><input type="text" name="txtnom" value="<?php echo $usunom;?>" required></td>
            </tr>
            <tr>
                <td>apellidos</td>
                <td><input type="text" name="txtdni" value="<?php echo $usudni;?>" required readonly></td>
            </tr>
            <tr>
                <td>Correo</td>
                <td><input type="text" name="txtdir" value="<?php echo $usudir;?>" required></td>
            </tr>
            <tr>
                <td>Clave</td>
                <td><input type="text" name="txttel" value="<?php echo $usutel;?>" required></td>
            </tr>
            <tr>
                <td>Contacto</td>
                <td><input type="text" name="txtcorreo" value="<?php echo $usucorreo;?>" required></td>
            </tr>
            <tr>
                <td>Direccion</td>
                <td><input type="text" name="txtpais" value="<?php echo $usupais;?>" required></td>
            </tr>
            <tr> 	
               <td colspan="2" >
				  <?php echo "<a href=\"index.php?pag=$pagina\">Cancelar</a>";?>
				   <input type="submit" name="btnregistrar" value="Registrar" onClick="javascript: return confirm('¿Deseas registrar a este usuario');">
			</td>
            </tr>
        </table>
    </form>
 </div>
</body>
</html>
<?php

		if(isset($_POST['btnregistrar']))
{   
	$vaiusu 	= $_POST['txtnom'];
	$vaidni 	= $_POST['txtdni'];
    $vaidir 	= $_POST['txtdir'];
	$vaitel 	= $_POST['txttel'];
    $vaiemail 	= $_POST['txtcorreo'];
    $vaipais 	= $_POST['txtpais'];
    $vaieclave 	= $_POST['txtclave'];

	$queryadd	= mysqli_query($conn, "INSERT INTO clientes(nombres,apellidos,direccion,telefono,correo,clave,pais) VALUES('$vaiusu','$vaidni','$vaidir','$vaitel','$vaiemail','$vaipais','$vaieclave')");
	
 	if(!$queryadd)
	{
		// echo "Error con el registro: ".mysqli_error($conn);
		 echo "<script>alert('DNI duplicado, intenta otra vez');</script>";
		 
	}else
	{
		echo "<script>window.location= 'index.php?pag=1' </script>";
	}
}
?>


