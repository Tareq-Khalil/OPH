<?php require_once('./config.php') ?>
<?php 
 $qry = $conn->query("SELECT t.*,c.name as company from `transaction_list` t inner join company_list c where  t.id = '{$_GET['id']}' ");
 if($qry->num_rows > 0){
     foreach($qry->fetch_assoc() as $k => $v){
         $$k=$v;
     }
    
 }
?>
    <style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative'>
        <style>
            #transaction-printable-details:before {
                content: 'Paid';
                color: #00000014;
                transform: rotate(-45deg);
                font-size: 10em;
                font-weight: 800;
                position: absolute;
                width: calc(100%);
                left: 0;
                display: flex;
                top: 26%;
                z-index: -1;
                justify-content: center;
                align-items: center;
            }
        </style>
        <div>
            <label for="text-muted">Tracking Code:</label>
            <h3 class="fw-bolder ps-5 text-info"><larger><?php echo isset($tracking_code) ? $tracking_code : '' ?></larger></h3>
        </div>
        <hr class="border-light">
        <div class="row">
            <fieldset>
                <legend class="text-info">Information</legend>
                <div class="col-12">
                    <dl>
                        <dt class="text-info">Account Name:</dt>
                        <dd class="pl-3"><?php echo $account_name ?></dd>
                        <dt class="text-info">Account #:</dt>
                        <dd class="pl-3"><?php echo $account_number ?></dd>
                        <dt class="text-info">Payment Amount:</dt>
                        <dd class="pl-3"><?php echo number_format($amount_to_pay,2) ?></dd>
                        <dt class="text-info">Service Fee:</dt>
                        <dd class="pl-3"><?php echo number_format($fee,2) ?></dd>
                        <dt class="text-info">Online Payment Code:</dt>
                        <dd class="pl-3"><?php echo ($payment_code) ?></dd>
                        <dt class="text-info">Transaction Date:</dt>
                        <dd class="pl-3"><?php echo date("M d, Y",strtotime($date_created)) ?></dd>
                    </dl>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center">
            <button class="btn btn-primary btn-flat mr-2" type="button" id="print">Print</button>
            <button class="btn btn-light btn-flat" type="button" id="cancel" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
    

<script>
    $(function(){
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Payment Details - Print View")
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
                      '<h4 class="text-center">Payment Details</h4>'+
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