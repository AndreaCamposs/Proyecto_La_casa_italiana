<?php
include_once("conexion.php"); 

?>
<!--Busca por VaidrollTeam para más proyectos. -->
<html>
<head>    
		<title>Mi casa Italiana</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<link rel="icon" href="logo.png">
</head>
<body>
    <?php
 
    $filasmax = 5;
 
    if (isset($_GET['pag'])) 
	{
        $pagina = $_GET['pag'];
    } else 
	{
        $pagina = 1;
    }
 
 if(isset($_POST['btnbuscar']))
{
$buscar = $_POST['txtbuscar'];

 $sqlusu = mysqli_query($conn, "SELECT * FROM user_info where first_name = '".$buscar."'");

}
else
{
 $sqlusu = mysqli_query($conn, "SELECT * FROM user_info ORDER BY first_name ASC LIMIT " . (($pagina - 1) * $filasmax)  . "," . $filasmax);
}
 
    $resultadoMaximo = mysqli_query($conn, "SELECT count(*) as num_usuarios FROM user_info");
 
    $maxusutabla = mysqli_fetch_assoc($resultadoMaximo)['num_usuarios'];
	
    ?>
	<div class="cont" >
	<form method="POST">
	<h1>Clientes de Mi Casa Italiana</h1>
	
	<a href="index.php">Inicio</a>
	
		
			<input type="submit" value="Buscar" name="btnbuscar">
			<input type="text" name="txtbuscar"  placeholder="Ingresar Nombre de usuario" autocomplete="off" style='width:20%'>
			</form>
    <table>
			<tr>
            <th>Id</th> 
			<th>Nombre</th>
			<th>Apellidos</th>
            <th>correo</th>
            <th>Clave</th>
            <th>Contacto</th>
            <th>direccion</th>
          
			<th>Acción</th>
			</tr>
 
        <?php
 
        while ($mostrar = mysqli_fetch_assoc($sqlusu)) 
		{
			
            echo "<tr>";
            echo "<td>".$mostrar['user_id']."</td>";
            echo "<td>".$mostrar['first_name']."</td>";
            echo "<td>".$mostrar['last_name']."</td>";
			echo "<td>".$mostrar['email']."</td>";
            echo "<td>".$mostrar['password']."</td>";    
			echo "<td>".$mostrar['mobile']."</td>";  
            echo "<td>".$mostrar['address']."</td>"; 
			
            echo "<td style='width:24%'>
	
			<a href=\"editar.php?dni=$mostrar[user_id]&pag=$pagina\">Modificar</a> 
			<a href=\"eliminar.php?dni=$mostrar[user_id]&pag=$pagina\" onClick=\"return confirm('¿Estás seguro de eliminar a $mostrar[first_name]?')\">Eliminar</a>
			</td>";  
			
        }
 
        ?>
    </table>
	<div style='text-align:right'>
	<br>
	<?php echo "Total de usuarios: ".$maxusutabla;?>
	</div>
	</div>
<div style='text-align:right'>
<br>
</div>
    <div style="text-align:center">
        <?php
        if (isset($_GET['pag'])) {
		   if ($_GET['pag'] > 1) {
                ?>
					<a href="indexcopy.php?pag=<?php echo $_GET['pag'] - 1; ?>">Anterior</a>
            <?php
					} 
				else 
					{
                    ?>
					<a href="#" style="pointer-events: none">Anterior</a>
            <?php
					}
                ?>
 
        <?php
        } 
		else 
		{
            ?>
            <a href="#" style="pointer-events: none">Anterior</a>
            <?php
        }
 
        if (isset($_GET['pag'])) {
            if ((($pagina) * $filasmax) < $maxusutabla) {
                ?>
            <a href="indexcopy.php?pag=<?php echo $_GET['pag'] + 1; ?>">Siguiente</a>
        <?php
            } else {
                ?>
            <a href="#" style="pointer-events: none">Siguiente</a>
        <?php
            }
            ?>
        <?php
        } else {
            ?>
            <a href="indexcopy.php?pag=2">Siguiente</a>
        <?php
        }
 
        ?>
    </div>

    </form>
</div>
</body>
</html>

