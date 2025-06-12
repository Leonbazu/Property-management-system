<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				<!-- You can add header or title here -->
			</div>
		</div>
		<div class="row">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Payments</b>
						<span class="float:right">
							<a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_invoice">
								<i class="fa fa-plus"></i> New Entry
							</a>
						</span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th>Date</th>
									<th>Tenant</th>
									<th>Invoice</th>
									<th>Amount</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$invoices = $conn->query("SELECT p.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) as name FROM payments p INNER JOIN tenants t ON t.id = p.tenant_id WHERE t.status = 1 ORDER BY date(p.date_created) DESC");
								while($row = $invoices->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
									<td><p><?php echo ucwords($row['name']) ?></p></td>
									<td><p><?php echo ucwords($row['invoice']) ?></p></td>
									<td class="text-right"><p><?php echo number_format($row['amount'], 2) ?></p></td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_invoice" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_invoice" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Table Panel -->
		</div>
	</div>	

</div>

<style>
	td {
		vertical-align: middle !important;
	}
	td p {
		margin: unset;
	}
	img {
		max-width: 100px;
		max-height: 150px;
	}
</style>

<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_invoice').click(function(){
		uni_modal("New invoice", "manage_payment.php", "mid-large")
	})
	
	$('.edit_invoice').click(function(){
		uni_modal("Manage invoice Details", "manage_payment.php?id=" + $(this).attr('data-id'), "mid-large")
	})
	
	$('.delete_invoice').click(function(){
		_conf("Are you sure to delete this invoice?", "delete_invoice", [$(this).attr('data-id')])
	})
	
	function delete_invoice($id){
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_payment',
			method: 'POST',
			data: {id: $id},
			success: function(resp){
				if(resp == 1){
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function(){
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>
