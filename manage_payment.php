<?php require_once('./config.php') ?>
<style>
    #uni_modal .modal-footer{
        display:none
    }
</style>
<div class="container-fluid">
    <form action="" id="transaction_form">
        <fieldset id="information">
            <legend class="text-info">Payment Information</legend>
            <div class="form-group">
                <label for="company_id" class="control-label text-info">Company</label>
                <select name="company_id" id="company_id" class="form-control form-control-border select2" required>
                    <option value="" disabled selected></option>
                    <?php
                        $company = $conn->query("SELECT * FROM `company_list` where status = 1 order by `name` asc");
                        while($row = $company->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="account_name" class="control-label text-info">Account Name</label>
                <input name="account_name" id="account_name" class="form-control form-control-border" required/>
            </div>
            <div class="form-group">
                <label for="account_number" class="control-label text-info">Account Number</label>
                <input name="account_number" id="account_number" class="form-control form-control-border" required/>
            </div>
            <div class="form-group">
                <label for="amount_to_pay" class="control-label text-info">Amount to Pay</label>
                <input name="amount_to_pay" pattern="[0-9.]+" id="amount_to_pay" class="form-control form-control-border text-right" required/>
            </div>
        </fieldset>
        <fieldset id="pay-field" class="d-none">
            <h1 class="text-center text-info" id="payable_amount">0.00</h1>
            <hr class="border-light">
            <div class="form-group">
                <dl class="row">
                    <dt class='text-info col-4'>Amount to Pay</dt>
                    <dd class="col-8 text-right" id="pay_amount"></dd>
                    <dt class='text-info col-4'>Service Fee</dt>
                    <dd class="col-8 text-right"id="fee"></dd>
                    <input type="hidden" name="fee" value='0'>
                    <input type="hidden" name="payable_amount" value='0'>
                    <input type="hidden" name="payment_code" value=''>
                </dl>
            </div>
            <div class="form-group text-center">
                <span id="paypal-button" ></span>
            </div>
        </fieldset>
        <div class="form-group">
            <div class="col-12">
                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-primary btn-flat mr-2 d-none" type="button" id="back">Back</button>
                    <button class="btn btn-primary btn-flat mr-2" type="button" id="next">Next</button>
                    <button class="btn btn-light btn-flat" type="button" id="cancel" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    paypal.Button.render({
    env: 'sandbox', // change for production if app is live,
 
        //app's client id's
	  client: {
        // for test only
        sandbox:    'AdDNu0ZwC3bqzdjiiQlmQ4BRJsOarwyMVD_L4YQPrQm4ASuBg4bV5ZoH-uveg8K_l9JLCmipuiKt4fxn',
        // for live only
        //production: 'AaBHKJFEej4V6yaArjzSx9cuf-UYesQYKqynQVCdBlKuZKawDDzFyuQdidPOBSGEhWaNQnnvfzuFB9SM'
    },
 
    commit: true, // Show a 'Pay Now' button
 
    style: {
      layout:  'vertical',
      color:   'blue',
      shape:   'rect',
      label:   'paypal'
    },
 
    payment: function(data, actions) {
        return actions.payment.create({
            payment: {
                transactions: [
                    {
                    	//total purchase
                        amount: { 
                        	total: $('fieldset#pay-field').find('[name="payable_amount"]').val(), 
                        	currency: 'PHP' 
                        }
                    }
                ]
            }
        });
    },
 
    onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function(payment) {
    		// //sweetalert for successful transaction
    		// swal('Thank you!', 'Paypal purchase successful.', 'success');
           var tracking_code = data.paymentID;
           $('fieldset#pay-field').find('[name="payment_code"]').val(tracking_code)
           $('#transaction_form').submit();
        });
    },
    onError: (err) => {
    console.error('error from the onError callback', err);
    alert("Payment Error.")
  }
 
}, '#paypal-button');
$(function(){
    $('#uni_modal .select2').select2({
        placeholder:"Please Select Here",
        dropdownParent: $("#uni_modal")
    })
    $('#next').click(function(){
        var check = new Promise((resolve,reject)=>{
            $('fieldset#information').find('input,select').each(function(){
                if($(this).val() == ''){
                    alert_toast(" All fields are required.","warning")
                    reject();
                }
            })
            resolve()
        })
        check.then(function(){

            $('#next').addClass('d-none')
            $("#back").removeClass('d-none')
            $("fieldset#information").addClass('d-none')
            $("fieldset#pay-field").removeClass('d-none')
        })

    })
    $('#back').click(function(){
        $(this).addClass('d-none')
        $("#next").removeClass('d-none')
        $("fieldset#information").removeClass('d-none')
        $("fieldset#pay-field").addClass('d-none')
    })
    $('#amount_to_pay').on('input',function(){
        var amount = $(this).val() > 0 ? $(this).val() :0;
        $.ajax({
            url:_base_url_+"classes/Master.php?f=get_fee",
            method:'POST',
            data:{amount : amount },
            dataType:'json',
            error:err=>{
                console.log(err)
                start_loader()
                alert("An error occured. Try to refresh the page");
            },
            success:function(resp){
                if(resp.status == 'success'){
                    $('#pay_amount').text(parseFloat(amount).toLocaleString('en-US'))
                    $('#fee').text(parseFloat(resp.fee).toLocaleString('en-US'))
                    $('[name="fee"]').val(parseFloat(resp.fee))
                    $('#payable_amount').text(parseFloat(resp.payable).toLocaleString('en-US'))
                    $('[name="payable_amount"]').val(parseFloat(resp.payable))
                }
            }
        })
    })
    $('#transaction_form').submit(function(e){
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_transaction",
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
                        $("html, body,.modal").animate({ scrollTop: 0 }, "fast");
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