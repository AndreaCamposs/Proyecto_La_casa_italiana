<?php
include('header.php');
include('admin/db_connect.php');
session_start();

if (isset($_GET['query'])) {
    $search_query = $_GET['query'];
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo opaco negro para el menú de navegación */
        #mainNav {
            background-color: #000 !important; /* Aseguramos que el fondo sea negro */
            opacity: 1 !important; /* Asegura que no sea transparente */
        }

        /* Cambiar el color del texto en el menú a blanco */
        #mainNav .nav-link {
            color: white !important;
        }

        #mainNav .navbar-brand {
            color: white !important;
        }

        /* Cambiar el color del texto en los enlaces hover */
        #mainNav .nav-link:hover {
            color: #ddd !important;
        }
    </style>
</head>
<body>
    <!-- Navbar: Incluir el menú de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="./"><?php echo $_SESSION['setting_name'] ?></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto my-2 my-lg-0">
                    <!-- Search Form -->
                    <form class="form-inline my-2 my-lg-0" action="search.php" method="GET" style="background-color: #000; padding: 5px; border-radius: 5px;">
    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Buscar productos" aria-label="Search" style="background-color: #333; color: white; border: none; border-radius: 3px;">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="background-color: #28a745; color: white; border-radius: 3px;">Buscar</button>
</form>

                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=home">Inicio</a></li>
                    <?php 
                    $categories = $conn->query("SELECT * FROM category_list order by name asc");
                    if($categories->num_rows > 0):
                    ?>
                    <li class="nav-item position-relative" id="cat-menu-link">
                      <a class="nav-link" href="#">Categorias</a>
                      <div id="category-menu" class="">
                        <ul>
                          <?php 
                            while($row = $categories->fetch_assoc()):
                          ?>
                            <li><a href="index.php?page=category&id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                          <?php endwhile; ?>
                        </ul>
                      </div>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=cart_list"><span> <span class="badge badge-danger item_count">0</span> <i class="fa fa-shopping-cart"></i>  </span>Carrito</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=about">Acerca de</a></li>
                    <?php if(isset($_SESSION['login_user_id'])): ?>
                      <li class="nav-item"><a class="nav-link js-scroll-trigger" href="editaruser.php">Datos usuario</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="admin/ajax.php?action=logout2"><?php echo "Welcome ". $_SESSION['login_first_name'].' '.$_SESSION['login_last_name'] ?> <i class="fa fa-power-off"></i></a></li>
                  <?php else: ?>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="javascript:void(0)" id="login_now">Iniciar Sesion</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./admin">Iniciar Sesion Admin</a></li>
                  <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido de búsqueda -->
    <div class="container mt-5 pt-5">
        <h2>Resultados de búsqueda para "<?php echo htmlspecialchars($search_query); ?>"</h2>
        <div class="row">
            <?php
            $stmt = $conn->prepare("SELECT * FROM product_list WHERE (name LIKE ? OR description LIKE ?) AND status = 1");
            $search_term = "%{$search_query}%";
            $stmt->bind_param("ss", $search_term, $search_term);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="assets/img/<?php echo $row['img_path']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text">Precio: <?php echo htmlspecialchars($row['price']); ?> 
                                    <?php echo isset($_SESSION['setting_currency']) ? htmlspecialchars($_SESSION['setting_currency']) : 'USD'; ?></p>
                                <a href="javascript:void(0);" onclick="viewProduct(<?php echo $row['id']; ?>)" class="btn btn-primary">Ver producto</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No se encontraron resultados para tu búsqueda.</p>";
            }

            $stmt->close();
            ?>
        </div>
    </div>

    <!-- Modal para mostrar los detalles del producto -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Detalles del Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <img src="" id="productImage" class="img-fluid" alt="Product Image">
                    <h5 id="productName"></h5>
                    <p id="productDescription"></p>
                    <p id="productPrice"></p>
                    <div class="quantity">
                        <label for="quantity">Cantidad</label>
                        <input type="number" id="quantity" value="1" min="1" class="form-control">
                    </div>
                    <input type="hidden" id="productId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addToCart()">Añadir al Carrito</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para mostrar el modal con los detalles del producto
        function viewProduct(productId) {
            fetch(`view_prod.php?id=${productId}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('productImage').src = `assets/img/${data.img_path}`;
                        document.getElementById('productName').innerText = data.name;
                        document.getElementById('productDescription').innerText = data.description;
                        document.getElementById('productPrice').innerText = `Precio: ${data.price} ${data.currency}`;
                        document.getElementById('productId').value = data.id;

                        $('#productModal').modal('show');
                    }
                })
                .catch(error => console.error('Error al cargar el producto:', error));
        }

        // Función para añadir el producto al carrito
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            const productId = document.getElementById('productId').value;

            if (quantity < 1) {
                alert("La cantidad debe ser al menos 1.");
                return;
            }

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                } else if (data.error) {
                    alert(data.error);
                }
                $('#productModal').modal('hide');
            })
            .catch(error => {
                console.error('Error al añadir al carrito:', error);
                alert('Producto añadido al carrito.');
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
