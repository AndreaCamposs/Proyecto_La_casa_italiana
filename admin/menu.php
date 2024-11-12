<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-menu">
				<div class="card">
					<div class="card-header">
						    Formulario Menu
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Nombre Menu</label>
								<input type="text" class="form-control" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Descripcion Menu</label>
								<textarea cols="30" rows="3" class="form-control" name="description"></textarea>
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
								  <input type="checkbox" name="status" class="custom-control-input" id="availability" checked>
								  <label class="custom-control-label" for="availability">Disponible</label>
								</div>
							</div>	
							<div class="form-group">
								<label class="control-label">Categoria </label>
								<select name="category_id" id="" class="custom-select browser-default">
									<?php
									$cat = $conn->query("SELECT * FROM category_list order by name asc ");
									while($row=$cat->fetch_assoc()):
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
								
							</div>
							<div class="form-group">
								<label class="control-label">Precio</label>
								<input type="number" class="form-control text-right" name="price" step="any">
							</div>
							<div class="form-group">
								<label for="" class="control-label">Imagen</label>
								<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
							</div>
							<div class="form-group">
								<img src="<?php echo isset($image_path) ? '../assets/img/'.$cover_img :'' ?>" alt="" id="cimg">
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3">Guardar</button>
								<button class="btn btn-sm btn-default col-sm-4" type="button"  onclick="$('#manage-menu').get(0).reset()"> Cancelar</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Imgen</th>
										<th class="text-center">Descripcion</th>
										<th class="text-center">Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									$cats = $conn->query("SELECT p.*, c.name as cat FROM product_list p inner join category_list c on c.id = p.category_id order by p.id asc");
									while($row=$cats->fetch_assoc()):
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>

									
										<td class="text-center">
											<img src="<?php echo isset($row['img_path']) ? '../assets/img/'.$row['img_path'] :'' ?>" alt="" id="cimg">
										</td>
										<td class="">
											<p>Nombre: <b><?php echo $row['name'] ?></b></p>
											<p>Categoria: <b><?php echo $row['cat'] ?></b></p>
											<p>Descripcion : <b class="truncate"><?php echo $row['description'] ?></b></p>
											<p>Precio : <b><?php echo "$".number_format($row['price'],2) ?></b></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_menu" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-status="<?php echo $row['status'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?php echo $row['price'] ?>" data-category_id="<?php echo $row['category_id'] ?>"  data-img_path="<?php echo $row['img_path'] ?>">Editar</button>
											<button class="btn btn-sm btn-danger delete_menu" type="button" data-id="<?php echo $row['id'] ?>">Borrar</button>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	img#cimg,.cimg{
		max-height: 10vh;
		max-width: 6vw;
	}
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset !important;
	}
	.custom-switch,.custom-control-input,.custom-control-label{
		cursor:pointer;
	}
	b.truncate {
		 overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 3; 
   -webkit-box-orient: vertical;	
    font-size: small;
    color: #000000cf;
    font-style: italic;
}
</style>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-menu').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_menu',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_menu').click(function(){
		start_load()
		var cat = $('#manage-menu')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		if($(this).attr('data-status') == 1)
			$('#availability').prop('checked',true)
		else
			$('#availability').prop('checked',false)

		cat.find("#cimg").attr('src','../assets/img/'+$(this).attr('data-img_path'))
		end_load()
	})
	$('.delete_menu').click(function(){
		_conf("Are you sure to delete this menu?","delete_menu",[$(this).attr('data-id')])
	})
	function delete_menu($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_menu',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>