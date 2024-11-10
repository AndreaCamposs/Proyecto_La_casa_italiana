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

// Contraseña encriptada 'staff123'
$hashed_password = password_hash('staff123', PASSWORD_DEFAULT);

// Consulta para insertar los datos
$sql = "INSERT INTO users (NAME, username, PASSWORD, TYPE) VALUES
        ('Alice', 'alice123', '$hashed_password', 2),
        ('Bob', 'bob456', '$hashed_password', 2),
        ('Charlie', 'charlie789', '$hashed_password', 2),
        ('David', 'david101', '$hashed_password', 2),
        ('Eva', 'eva202', '$hashed_password', 2),
        ('Frank', 'frank303', '$hashed_password', 2),
        ('Grace', 'grace404', '$hashed_password', 2),
        ('Henry', 'henry505', '$hashed_password', 2),
        ('Ivy', 'ivy606', '$hashed_password', 2),
        ('Jack', 'jack707', '$hashed_password', 2),
        ('Karen', 'karen808', '$hashed_password', 2),
        ('Liam', 'liam909', '$hashed_password', 2),
        ('Mia', 'mia010', '$hashed_password', 2),
        ('Noah', 'noah111', '$hashed_password', 2),
        ('Olivia', 'olivia222', '$hashed_password', 2),
        ('Paul', 'paul333', '$hashed_password', 2),
        ('Quinn', 'quinn444', '$hashed_password', 2),
        ('Rachel', 'rachel555', '$hashed_password', 2),
        ('Sam', 'sam666', '$hashed_password', 2),
        ('Tina', 'tina777', '$hashed_password', 2),
        ('Uma', 'uma888', '$hashed_password', 2),
        ('Vince', 'vince999', '$hashed_password', 2),
        ('Wendy', 'wendy1010', '$hashed_password', 2),
        ('Xander', 'xander1111', '$hashed_password', 2),
        ('Yara', 'yara1212', '$hashed_password', 2),
        ('Zane', 'zane1313', '$hashed_password', 2)";

// Ejecutar la consulta
if ($conn->multi_query($sql) === TRUE) {
    echo "Datos insertados correctamente.";
} else {
    echo "Error al insertar datos: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
