<?php
// Configuración de la conexión a la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = '';

// Conexión a la base de datos
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Contraseña encriptada 'cliente123'
$hashed_password = password_hash('cliente123', PASSWORD_DEFAULT);

// Datos para generar los usuarios
$first_names = ['John', 'Jane', 'Michael', 'Sarah', 'William', 'Jessica', 'Daniel', 'Emily', 'Matthew', 'Ashley', 'James', 'Amanda', 'David', 'Jennifer', 'Joseph', 'Elizabeth', 'Charles', 'Megan', 'Thomas', 'Hannah'];
$last_names = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson'];
$addresses = ['123 Main St', '456 Elm St', '789 Oak St', '101 Maple Ave', '202 Pine St', '303 Cedar St', '404 Birch St', '505 Spruce St', '606 Willow St', '707 Cherry St', '808 Aspen St', '909 Magnolia Ave', '1001 Walnut St', '1102 Chestnut St', '1203 Hickory St', '1304 Poplar St', '1405 Beech St', '1506 Sycamore St', '1607 Alder St', '1708 Sequoia St'];

// Generar 400 usuarios
$users = [];
for ($i = 0; $i < 400; $i++) {
    $first_name = $first_names[array_rand($first_names)];
    $last_name = $last_names[array_rand($last_names)];
    $email = strtolower($first_name) . '.' . strtolower($last_name) . $i . '@example.com';
    $mobile = '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    $address = $addresses[array_rand($addresses)];
    $users[] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $hashed_password,
        'mobile' => $mobile,
        'address' => $address
    ];
}

// Preparar la consulta
$sql = "INSERT INTO user_info (first_name, last_name, email, password, mobile, address) VALUES ";

// Agregar los valores de los usuarios a la consulta
$values = [];
foreach ($users as $user) {
    $values[] = "('" . $conn->real_escape_string($user['first_name']) . "', '" . $conn->real_escape_string($user['last_name']) . "', '" . $conn->real_escape_string($user['email']) . "', '" . $conn->real_escape_string($user['password']) . "', '" . $conn->real_escape_string($user['mobile']) . "', '" . $conn->real_escape_string($user['address']) . "')";
}
$sql .= implode(", ", $values);

// Ejecutar la consulta
if ($conn->multi_query($sql) === TRUE) {
    echo "Datos insertados correctamente.";
} else {
    echo "Error al insertar datos: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
