<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Pedido</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        @media print {
            .print-hidden {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-4 mb-4">Factura de Consumidor final</h1>

        <?php
        require 'phpmailer/src/Exception.php';
        require 'phpmailer/src/PHPMailer.php';
        require 'phpmailer/src/SMTP.php';
        require ('fpdf/fpdf.php');
        include 'db_connect.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        function generarFacturaPDF($order, $order_list)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Factura de Consumidor Final', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Datos de la Pizzeria', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Nombre: Mi Casa Italiana, Correo: lacasaitaliana@gmail.com, Contacto: 2200-3422', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Acerca de nosotros', 0, 1, 'C');
            $pdf->Cell(0, 10, 'La Casa Italiana: En La Casa Italiana, nos enorgullecemos de ofrecer una experiencia culinaria', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Nuestro Proposito: Nuestro objetivo es ser tu lugar favorito ', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Nuestra Historia: Con anos de experiencia en la cocina italiana,', 0, 1, 'C');
            $pdf->Cell(0, 10, 'que refleja su pasion por la comida y su compromiso con la calidad.', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Datos del cliente:', 0, 1, 'C');


            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, 'N. Factura: ' . $order['id'], 0, 1);
            $pdf->Cell(0, 10, 'Nombre: ' . $order['name'], 0, 1);
            $pdf->Cell(0, 10, 'Direccion: ' . $order['address'], 0, 1);
            $pdf->Cell(0, 10, 'Email: ' . $order['email'], 0, 1);
            $pdf->Cell(0, 10, 'Telefono: ' . $order['mobile'], 0, 1);
            $pdf->Cell(0, 10, 'Estado: ' . ($order['status'] == 1 ? 'Confirmada' : 'Para Verificacion'), 0, 1);

            $pdf->Cell(40, 10, 'Producto', 1);
            $pdf->Cell(40, 10, 'Cantidad', 1);
            $pdf->Cell(40, 10, 'Precio Unitario', 1);
            $pdf->Cell(40, 10, 'Total', 1);
            $pdf->Ln();

            $total = 0;
            while ($item = $order_list->fetch_assoc()) {
                $pdf->Cell(40, 10, $item['name'], 1);
                $pdf->Cell(40, 10, $item['qty'], 1);
                $pdf->Cell(40, 10, number_format($item['price'], 2), 1);
                $pdf->Cell(40, 10, number_format($item['qty'] * $item['price'], 2), 1);
                $pdf->Ln();
                $total += $item['qty'] * $item['price'];
            }

            $pdf->Cell(120, 10, 'TOTAL', 1);
            $pdf->Cell(40, 10, number_format($total, 2), 1);

            $fileName = 'factura_' . $order['id'] . '.pdf';
            $pdf->Output('F', $fileName);

            return $fileName;
        }

        function enviarFacturaEmail($order, $fileName)
        {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu host SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'stanleyfigueroa2020@gmail.com'; // Cambia esto por tu email SMTP
                $mail->Password = 'nhkm fnfu szpw jdaa'; // Reemplaza con tu contraseña generada
        
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('tu-email@gmail.com', 'Tu Nombre');
                $mail->addAddress($order['email'], $order['name']);

                $mail->isHTML(true);
                $mail->Subject = 'Confirmación de Pedido';
                $mail->Body = 'Adjunto encontrará la factura de su pedido.';
                $mail->addAttachment($fileName);

                $mail->send();
                echo 'El mensaje ha sido enviado.<br>';
            } catch (Exception $e) {
                echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}<br>";
            }
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $order_id = $_GET['id'];
            $order_query = $conn->query("SELECT * FROM orders WHERE id = $order_id");
            if ($order_query) {
                $order = $order_query->fetch_assoc();
                if ($order) {
                    $order_list = $conn->query("SELECT o.*, p.name, p.price FROM order_list o INNER JOIN product_list p ON o.product_id = p.id WHERE o.order_id = $order_id");
                    if ($order_list) {
                        $fileName = generarFacturaPDF($order, $order_list);
                        enviarFacturaEmail($order, $fileName);
                        if (file_exists($fileName)) {
                            echo "<a href='$fileName' target='_blank'>Descargar Factura</a><br>";
                        }
                    } else {
                        echo "Order list not found.<br>";
                    }
                } else {
                    echo "Order not found.<br>";
                }
            } else {
                echo "Order query failed.<br>";
            }
        } else {
            echo "No order ID provided.<br>";
        }
        ?>

        <div class="container mt-4">

            <button id="volver" type="button" class="btn btn-primary">Volver</button>

        </div>
    </div>
</body>
<script>
    // script.js
    document.getElementById('volver').addEventListener('click', function () {
        window.history.back();
    });

</script>

</html>