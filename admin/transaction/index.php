<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Transaction</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Transaction Code</th>
                            <th>Client</th>
                            <th>Information</th>
                            <th>Payable Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT t.*,c.name as company,concat(lastname,', ', firstname,' ',middlename) as user from `transaction_list` t inner join company_list c on t.company_id = c.id inner join users u on t.user_id = u.id order by unix_timestamp(t.`date_created`) desc ");
                        while($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['tracking_code'] ?></td>
                                <td><?php echo $row['user'] ?></td>
                                <td>
                                    <dl class="lh-1">
                                        <dt class="my-0 py-0 text-info">Account Name:</dt>
                                        <dd class="my-0 py-0 pl-3"><?php echo $row['account_name'] ?></dd>
                                        <dt class="my-0 py-0 text-info">Account #:</dt>
                                        <dd class="my-0 py-0 pl-3"><?php echo $row['account_number'] ?></dd>
                                    </dl>
                                </td>
                                <td class="text-right"><?php echo number_format($row['payable_amount'],2) ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-light"></span> View</a>
                                    </div>
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
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this transaction permanently?","delete_transaction",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Payment Details","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_transaction($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_transaction",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>