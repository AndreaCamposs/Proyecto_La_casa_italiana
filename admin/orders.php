<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        include 'db_connect.php';
                        $qry = $conn->query("SELECT * FROM orders ");
                        while($row=$qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['address'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['mobile'] ?></td>
                            <?php if($row['status'] == 1): ?>
                                <td class="text-center"><span class="badge badge-success">Confirmada</span></td>
                            <?php else: ?>
                                <td class="text-center"><span class="badge badge-secondary">Para Verificacion</span></td>
                            <?php endif; ?>
                            <td>
                                <button class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id'] ?>" >Ver Orden</button>
                                <?php if($row['status'] == 1): ?>
                                    <a href="generate_invoice.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success">Generar PDF</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('.view_order').click(function(){
        uni_modal('Order','view_order.php?id='+$(this).attr('data-id'))
    })
    $('table').dataTable();
</script>