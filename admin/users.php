<?php 

?>

<div class="container-fluid">
	
	<div class="row">
	<div class="col-lg-12">
			<button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i> New user</button>
	</div>
	</div>
	<br>
	<div class="row">
		<div class="card col-lg-12">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table-striped table-bordered">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Name</th>
								<th class="text-center">Username</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								include 'db_connect.php';
								$users = $conn->query("SELECT * FROM users order by name asc");
								$i = 1;
								while($row= $users->fetch_assoc()):
							?>
							<tr>
								<td>
									<?php echo $i++ ?>
								</td>
								<td>
									<?php echo $row['name'] ?>
								</td>
								<td>
									<?php echo $row['username'] ?>
								</td>
								<td>
									<center>
											<div class="btn-group">
											<button type="button" class="btn btn-primary">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item edit_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Delete</a>
											</div>
											</div>
											</center>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
<div class="container-fluid">
	
	<div class="row">
								
	</div>
	<br>
	<div class="row">
		<div class="card col-lg-12">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table-striped table-bordered">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Nambre</th>
								<th class="text-center">Usuario</th>
								<th class="text-center">Correo</th>
								<th class="text-center">Contraseña</th>
								<th class="text-center">Contacto</th>
								<th class="text-center">Dirección</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								include 'db_connect.php';
								$users = $conn->query("SELECT * FROM user_info order by first_name asc");
								$i = 1;
								while($row= $users->fetch_assoc()):
							?>
							<tr>
								<td>
									<?php echo $i++ ?>
								</td>
								<td>
									<?php echo $row['first_name'] ?>
								</td>
								<td>
									<?php echo $row['last_name'] ?>
								</td>
								<td>
									<?php echo $row['email'] ?>
								</td>
								<td >
									<?php echo $row['password'] ?>
								</td>
								<td>
									<?php echo $row['mobile'] ?>
								</td>
								<td>
									<?php echo $row['address'] ?>
								</td>
								<td>
									<center>
											<div class="btn-group">
										
										
												<a type="button" class="btn btn-primary" href="editar.php" data-id = '<?php echo $row['user_id'] ?>'>Editar usuario</a>
											
											
											</center>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
<script>
	
$('#new_user').click(function(){
	uni_modal('New User','manage_user.php')
})
$('.edit_user').click(function(){
	uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'))
})
$('table').dataTable()
</script>