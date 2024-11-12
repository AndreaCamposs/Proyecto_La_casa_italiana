<?php 
include_once("conexion.php");
include_once("indexcopy.php");

// Comprobar si se ha pasado el parámetro 'dni'
if (isset($_GET['dni'])) {
    $coddni = $_GET['dni'];
} else {
    $coddni = null; // O algún valor predeterminado
}

// Consulta para buscar el usuario
$querybuscar = mysqli_query($conn, "SELECT user_id, first_name, last_name, email, mobile, address FROM user_info WHERE user_id='$coddni'");

$mostrar = mysqli_fetch_array($querybuscar);
if ($mostrar) {
    $usuid = $mostrar['user_id'];
    $usunom = $mostrar['first_name'];
    $usudni = $mostrar['last_name'];
    $usudir = $mostrar['email'];
    $usutel = $mostrar['mobile'];
    $usucorreo = $mostrar['mobile'];
    $usupais = $mostrar['address'];
} else {
    echo "Todo esta bien.";
    exit;
}
?>
<html>
<head>
    <title>Mi Casa Italiana Clientes</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="caja_popup2">
    <form class="contenedor_popup" method="POST">
        <table>
            <tr><th colspan="2">Modificar cliente</th></tr>
          
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="txtnom" value="<?php echo $usunom; ?>" required></td>
            </tr>
            <tr>
                <td>Apellidos</td>
                <td><input type="text" name="txtdni" value="<?php echo $usudni; ?>" required></td>
            </tr>
            <tr>
                <td>Correo</td>
                <td><input type="email" name="txtdir" value="<?php echo $usudir; ?>" required></td>
            </tr>
            <tr>
                <td>Contacto</td>
                <td><input type="text" name="txtcorreo" value="<?php echo $usucorreo; ?>" required></td>
            </tr>
            <tr>
                <td>Dirección</td>
                <td><input type="text" name="txtpais" value="<?php echo $usupais; ?>" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo "<a href=\"editar.php?pag=$pagina\">Cancelar</a>"; ?>
                    <input type="submit" name="btnmodificar" value="Modificar" onClick="javascript: return confirm('¿Deseas modificar este usuario?');">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>

<?php
if(isset($_POST['btnmodificar']))
{
    $nom1 = $_POST['txtnom'];
    $dni1 = $_POST['txtdni'];
    $dir1 = $_POST['txtdir'];
    $correo1 = $_POST['txtcorreo'];
    $direcc= $_POST['txtpais'];
    
    // Consulta para modificar el usuario
    $querymodificar = mysqli_query($conn, "UPDATE user_info SET first_name='$nom1', last_name='$dni1', email='$dir1', mobile='$correo1', address='$direcc' WHERE user_id=$usuid");
    
    if ($querymodificar) {
        echo "<script>window.location= 'editar.php?pag=$pagina'</script>";
    } else {
        echo "Error al modificar los datos: " . mysqli_error($conn);
    }
}
?>