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

// Consulta para contar los estados
$sql = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Salida de datos de cada fila
    while($row = $result->fetch_assoc()) {
        $status = $row["status"] == 0 ? "No confirmada" : "Confirmada";
        $data[] = array("name" => $status, "y" => (int)$row["count"]);
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<script>
var chartData = <?php echo json_encode($data); ?>;
</script>


<script>
var chartData = <?php echo json_encode($data); ?>;
</script>


<!DOCTYPE html>
<html>
<head>
    <title>Highcharts Pie Chart</title>
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
        text: 'Order Status Distribution'
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
        name: 'Orders',
        colorByPoint: true,
        data: chartData
    }]
});
</script>

</body>
</html>
