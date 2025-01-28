<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `fee_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="fee-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="amount_from" class="control-label">Amount From</label>
			<input type="number" step="any" id="amount_from" name="amount_from" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($amount_from) ? $amount_from :0 ?>" required>
		</div>
		<div class="form-group">
			<label for="amount_to" class="control-label">Amount To</label>
			<input type="number" step="any" id="amount_to" name="amount_to" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($amount_to) ? $amount_to :0 ?>" required>
		</div>
		<div class="form-group">
			<label for="fee" class="control-label">Fee</label>
			<input type="number" step="any" id="fee" name="fee" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($fee) ? $fee :0 ?>" required>
		</div>
	</form>
</div>
<script>
  
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Please Select here",width:"relative"})
		$('#fee-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_fee",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload();
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
	})
</script>