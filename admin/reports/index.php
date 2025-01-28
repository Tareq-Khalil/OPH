
<?php 
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d",strtotime(date("Y-m-d"). ' -1 week'));;
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");;

?>
<style>
	.hide-print{
		display:none;
	}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Transaction Reports</h3>
		<div class="card-tools">
			<!-- <button href="?page=history/manage_record" class="btn btn-flat btn-primary"><span class="fas fa-print"></span>  Create New</button> -->
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="col-12">
				<form action="" id="filter">
				<div class="row align-items-end">
					<div class="form-group col-md-4">
						<label for="date_from" class="control-label">Date From</label>
						<input type="date" name="date_from" class="form-control" value="<?php echo $date_from ?>" required>
					</div>
					<div class="form-group col-md-4">
						<label for="date_to" class="control-label">Date To</label>
						<input type="date" name="date_to" class="form-control" value="<?php echo $date_to ?>" required>
					</div>
					<div class="form-group col-md-4">
						<button class="btn btn-flat btn-primary">Filter</button>
						<button class="btn btn-flat btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>
					</div>
				</div>
				</form>
			</div>
			<div class="container-fluid" id="print_out">
				<div class="hide-print">
					<?php if($date_from == $date_to): ?>
						<h3 class="text-center">As of <?php echo date("F d, Y",strtotime($date_from)) ?></h3>
					<?php else: ?>
					<h3 class="text-center"></h3>
						<h3 class="text-center">As of <?php echo date("F d, Y",strtotime($date_from)) ?> - <?php echo date("F d, Y",strtotime($date_to)) ?></h3>
					<?php endif; ?>
				</div>
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="25%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Transaction Code</th>
						<th>Client</th>
						<th>Info</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$uwhere =" where date(t.date_created) BETWEEN '{$date_from}' and '{$date_to}' ";
					
					$sql = "SELECT t.*,c.name as company,concat(lastname,', ', firstname,' ',middlename) as user from `transaction_list` t inner join company_list c on t.company_id = c.id inner join users u on t.user_id = u.id {$uwhere} order by unix_timestamp(t.`date_created`) desc ";
					$qry = $conn->query($sql);
					while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center align-middle py-1 px-2"><?php echo $i++; ?></td>
							<td class="align-middle py-1 px-2"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class="align-middle py-1 px-2"><?php echo $row['tracking_code'] ?></td>
							<td class="align-middle py-1 px-2"><?php echo $row['user'] ?></td>
							<td class="align-middle py-1 px-2">
								<dl class="lh-1">
									<dt class="my-0 py-0 text-info">Account Name:</dt>
									<dd class="my-0 py-0 pl-3"><?php echo $row['account_name'] ?></dd>
									<dt class="my-0 py-0 text-info">Account #:</dt>
									<dd class="my-0 py-0 pl-3"><?php echo $row['account_number'] ?></dd>
								</dl>
							</td>
							<td class="text-right align-middle py-1 px-2"><?php echo number_format($row['payable_amount'],2) ?></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$('#filter').submit(function(e){
			e.preventDefault();
			location.href="./?page=reports&"+$(this).serialize();
		})
		$('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Transaction Details - Print View")
            var p = $('#print_out').clone()
            p.find('hr.border-light').removeClass('.border-light').addClass('border-dark')
            p.find('.btn').remove()
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Transaction Report</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);

        })
	})
	
</script>