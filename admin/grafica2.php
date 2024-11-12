<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";  // Ajusta con tu nombre de usuario
$password = "";      // Ajusta con tu contraseña
$dbname = "opos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obtener las tres pizzas más vendidas
$sql = "
    SELECT p.name, COUNT(o.product_id) as count
    FROM order_list o
    JOIN product_list p ON o.product_id = p.id
    GROUP BY o.product_id
    ORDER BY count DESC
    LIMIT 3
";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Salida de datos de cada fila
    while($row = $result->fetch_assoc()) {
        $data[] = array("name" => $row["name"], "y" => (int)$row["count"]);
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<script>
var chartData = <?php echo json_encode($data); ?>;
</script>


<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<div id="container" style="height: 400px;"></div>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<div id="container" style="height: 400px;"></div>

<script>
Highcharts.chart('container', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Top 3  Pizzas más Ventidas'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%'
            }
        }
    },
    series: [{
        name: 'Pizzas',
        colorByPoint: true,
        data: chartData
    }]
});
</script>

</body>
</html>
