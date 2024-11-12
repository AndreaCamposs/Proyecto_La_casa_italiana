<?php
include 'admin/db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $qry = $conn->query("SELECT * FROM product_list WHERE id = $product_id")->fetch_assoc();

    // Verificar si la solicitud es AJAX y enviar JSON en ese caso
    if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
        if ($qry) {
            // Añade el campo de moneda desde la sesión (si está disponible)
            $qry['currency'] = isset($_SESSION['setting_currency']) ? $_SESSION['setting_currency'] : 'USD';

            // Envía los datos del producto en formato JSON
            header('Content-Type: application/json');
            echo json_encode($qry);
        } else {
            // Si el producto no se encuentra, devuelve un error en formato JSON
            echo json_encode(['error' => 'Producto no encontrado']);
        }
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}
?>

<div class="container-fluid">
    <div class="card">
        <img src="assets/img/<?php echo htmlspecialchars($qry['img_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($qry['name']); ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($qry['name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($qry['description']); ?></p>
            <p class="card-text">Precio: <?php echo htmlspecialchars($qry['price']); ?> <?php echo isset($_SESSION['setting_currency']) ? htmlspecialchars($_SESSION['setting_currency']) : 'USD'; ?></p>
            <div class="form-group"></div>
            <div class="row">
                <div class="col-md-3"><label class="control-label">Cantidad</label></div>
                <div class="input-group col-md-7 mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button" id="qty-minus"><span class="fa fa-minus"></span></button>
                    </div>
                    <input type="number" readonly value="1" min="1" class="form-control text-center" name="qty">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-dark" type="button" id="qty-plus"><span class="fa fa-plus"></span></button>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-outline-dark btn-sm btn-block" id="add_to_cart_modal"><i class="fa fa-cart-plus"></i> Añadir al Carrito</button>
            </div>
        </div>
    </div>
</div>

<style>
    #uni_modal_right .modal-footer {
        display: none;
    }
</style>

<script>
    $('#qty-minus').click(function(){
        var qty = $('input[name="qty"]').val();
        if(qty == 1){
            return false;
        }else{
            $('input[name="qty"]').val(parseInt(qty) - 1);
        }
    });

    $('#qty-plus').click(function(){
        var qty = $('input[name="qty"]').val();
        $('input[name="qty"]').val(parseInt(qty) + 1);
    });

    $('#add_to_cart_modal').click(function(){
        start_load();
        $.ajax({
            url: 'admin/ajax.php?action=add_to_cart',
            method: 'POST',
            data: {pid: '<?php echo $product_id; ?>', qty: $('[name="qty"]').val()},
            success: function(resp) {
                if(resp == 1) {
                    alert_toast("Producto añadido al carrito exitosamente");
                    $('.item_count').html(parseInt($('.item_count').html()) + parseInt($('[name="qty"]').val()));
                    $('.modal').modal('hide');
                    end_load();
                }
            }
        });
    });
</script>
