<?php
session_start();
include('admin/db_connect.php');

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);

    // Verificar si la cantidad es válida
    if ($quantity <= 0) {
        echo "Cantidad inválida.";
        exit;
    }

    // Obtener el ID del usuario si está logueado o la IP del cliente si no está logueado
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Si el usuario está logueado
    $clientIp = $_SERVER['REMOTE_ADDR']; // Si no está logueado, usamos la IP

    // Obtener los datos del producto desde la base de datos
    $stmt = $conn->prepare("SELECT id, name, price FROM product_list WHERE id = ? AND status = 1");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Producto no encontrado o no disponible.";
        exit;
    }

    $product = $result->fetch_assoc();
    $stmt->close();

    // Verificar si el producto ya está en el carrito
    if ($userId) {
        // Si el usuario está logueado, verificamos por user_id
        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $productId, $userId);
    } else {
        // Si el usuario no está logueado, verificamos por client_ip
        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND client_ip = ?");
        $stmt->bind_param("is", $productId, $clientIp);
    }
    $stmt->execute();
    $existingItem = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Si el producto ya está en el carrito, actualizamos la cantidad
    if ($existingItem) {
        $newQuantity = $existingItem['qty'] + $quantity;
        if ($userId) {
            $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE product_id = ? AND user_id = ?");
            $stmt->bind_param("iii", $newQuantity, $productId, $userId);
        } else {
            $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE product_id = ? AND client_ip = ?");
            $stmt->bind_param("iis", $newQuantity, $productId, $clientIp);
        }
        $stmt->execute();
        $stmt->close();
        echo "Producto actualizado en el carrito.";
    } else {
        // Si el producto no está en el carrito, lo agregamos
        if ($userId) {
            $stmt = $conn->prepare("INSERT INTO cart (product_id, qty, user_id) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $productId, $quantity, $userId);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (product_id, qty, client_ip) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $productId, $quantity, $clientIp);
        }
        $stmt->execute();
        $stmt->close();
        echo "Producto añadido al carrito.";
    }
} else {
    echo "Método no permitido.";
}
?>
