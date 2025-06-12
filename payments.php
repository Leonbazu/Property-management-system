<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Payments</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_payment">
					<i class="fa fa-plus"></i> New Entry
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Tenant</th>
									<th class="">House #</th>
									<th class="">Outstanding Balance</th>
									<th class="">Last Payment</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$tenants =$conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.status = 1 order by h.house_no desc ");
								while($row=$tenants->fetch_assoc()):
    $start = new DateTime($row['date_in']);
    $end = new DateTime(); // today
    $diff = $start->diff($end);
    $months = ($diff->y * 12) + $diff->m + 1; // +1 to include current month
    $payable = $row['price'] * $months;

    $paid_q = $conn->query("SELECT COALESCE(SUM(amount),0) as paid FROM payments where tenant_id =".$row['id']);
    $paid = $paid_q->fetch_assoc()['paid'];

    $last_payment_q = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
    $last_payment = $last_payment_q->num_rows > 0 ? date("M d, Y",strtotime($last_payment_q->fetch_assoc()['date_created'])) : 'N/A';
    $outstanding = $payable - $paid;
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['house_no'] ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo number_format($outstanding,2) ?></b></p>
									</td>
									<td class="">
										 <p><b><?php echo  $last_payment ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_payment').click(function(){
		uni_modal("New payment","manage_payment.php","mid-large")
		
	})
	$('.edit_payment').click(function(){
		uni_modal("Manage payment Details","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.view_payment').click(function(){
		uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_payment').click(function(){
		_conf("Are you sure to delete this payment?","delete_payment",[$(this).attr('data-id')])
	})
	
	function delete_payment($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payment',
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
</script><footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
   <p class="text-muted mb-1 mb-md-0">Copyright © 2025 <a href="brandonemmanuel3172@gmail.com" target="_blank">Property Management System Software</a> - Design By BazuTech</p>
   
</footer>