<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tenant">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Last Name</label>
     				<input type="text" class="form-control" name="lastname" pattern="^[A-Za-z]+$" title="Last name must contain only letters" value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname" pattern="^[A-Za-z]+$" title="First name must contain only letters" value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename" pattern="^[A-Za-z]+$" title="Middle name must contain only letters" value="<?php echo isset($middlename) ? $middlename :'' ?>">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Email</label>
				<input type="email" class="form-control" name="email" pattern="^[A-Za-z0-9]+@gmail\.com$" title="Email must be alphanumeric and end with @gmail.com" value="<?php echo isset($email) ? $email :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Contact #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
			</div>
			
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">House</label>
				<select name="house_id" id="" class="custom-select select2">
					<option value=""></option>
					<?php 
					$house = $conn->query("SELECT * FROM houses where id not in (SELECT house_id from tenants where status = 1) ".(isset($house_id)? " or id = $house_id": "" )." ");
					while($row= $house->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Registration Date</label>
				<input type="date" class="form-control" name="date_in"  value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
			</div>
		</div>
	</form>
</div>
<script>
	
	$('#manage-tenant').submit(function(e){
		// JS validation for better UX
		var namePattern = /^[A-Za-z]+$/;
		var emailPattern = /^[A-Za-z0-9]+@gmail\.com$/;

		var firstname = $('[name="firstname"]').val();
		var middlename = $('[name="middlename"]').val();
		var lastname = $('[name="lastname"]').val();
		var email = $('[name="email"]').val();

		if (!namePattern.test(firstname) || !namePattern.test(lastname) || (middlename && !namePattern.test(middlename))) {
			alert('First, Middle, and Last names must contain only letters.');
			e.preventDefault();
			return false;
		}
		if (!emailPattern.test(email)) {
			alert('Email must be alphanumeric and end with @gmail.com');
			e.preventDefault();
			return false;
		}

		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
						setTimeout(function(){
							location.reload()
						},1000)
				}
			}
		})
	})
</script><footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
   <p class="text-muted mb-1 mb-md-0">Copyright © 2025 <a href="brandonemmanuel3172@gmail.com" target="_blank">Tenant Management System Software</a> - Design By BazuTech</p>
   
</footer>