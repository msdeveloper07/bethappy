<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.bg-dark-blue {
    background-color: #151338 !important;
}
.bg-blue-light {
    background-color: #c4dd31 !important;
}
.deposit {
    overflow-x: hidden;
    border-radius: 30px;
}
.head-title{
    font-size: 1.125rem;
    line-height: 1.2778;
    font-weight: 700;
    margin-bottom: .75rem;
}
.deposit-options {
    padding: 35px !important;
}

.deposit .card-width {
    width: 558px;
}

.deposit .card-width {
    min-width: 320px;
}
.payment-card.small {
    width: 119px !important;
    height: 80px !important;
    margin: initial;
}
.deposit-method-name {
    font-size: 1.4375rem;
    line-height: 1.2609;
    font-weight: 700;
    text-transform:capitalize;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    font-size: 1rem;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    color: #2ae8de;
    font-size: .875rem;
    position: relative;
    margin-left: 11px;
}
.btn.btn-link-secondary-before::before, .btn.btn-link-secondary-before.btn-lg::before, .btn-group-lg>.btn.btn-link-secondary-before::before{
    content: "";
    margin-right: 0.3rem;
    width: 10px;
    height: 10px;
    position: absolute;
    border-left: 5px solid transparent;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    border-right: 8px solid #2ae8de;
    left: -16px;
    top: 5px;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    font-size: 1rem;
    text-transform: uppercase;
    font-weight: 700;
}
.mb-0_25, .my-0_25 {
    margin-bottom: .125rem !important;
}

.h4 {
	font-size: 1.125rem !important;
	margin-top: 1rem;
}

.deposit-sum h4, .deposit-sum .h4,
.bonus-selection h4, .bonus-selection .h4 {
    font-size: 1.125rem;
    line-height: 1.2778;
    font-weight: 700;
    margin-top: 1rem;
    margin-bottom: 0;
}

.pl-2_5, .px-2_5 {
    padding-left: 1.25rem !important;
}
.pr-2_5, .px-2_5 {
    padding-right: 1.25rem !important;
}

.deposit-sum .deposit-value-cell:not(:first-child) {
    margin-left: 8px;
}
.deposit-sum .deposit-value-cell {
    height: 50px;
    border-radius: 30px;
    padding: 14px 0 !important;
	color: black;
}
.mt-2_5, .my-2_5 {
    margin-top: 1.25rem !important;
}
.text-primary {
    color: #f8b221 !important;
}
.deposit-sum .deposit-value-cell.active {
    background-color: #ffc107 !important;
    padding: 11px 0;
}
.border-secondary {
    border-color: #2ae8de !important;
}
.deposit .btn-lg, .deposit .btn-group-lg>.btn {
    border-radius: 2rem;
}


.payment-logo {
	width: 210px;
    background-color: #dee2e6 !important;
    border-radius: 18px;
    padding: 10px;
}

.control-label {
	font-size: 1rem;
	font-weight: bold;
	margin-left: 20px;
}

.amount-list {
	display: flex;
	width: 100%;
}

.amount-list .deposit-value-cell {
    height: 50px;
    border-radius: 30px;
    padding: 14px 0 !important;
    color: black;
}

.amount-list .deposit-value-cell.active {
	background-color: #ffc107 !important;
}

.amount-list .deposit-value-cell:not(:first-child) {
    margin-left: 8px;
}

.btn-submit {
	border: none;
	width: 40%;
    padding: 12px 0px;
    border-radius: 100px;
    font-size: 1.1rem;
    font-weight: bold;
}

.form-control {
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
    padding-bottom: 0.85rem !important;
    display: block;
    height: 3.25rem;
    padding: .75rem 1.3rem;
    font-size: .875rem;
    font-weight: bold;
    line-height: 1.57;
    color: black;
    background-color: white;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,0);
    border-radius: 2rem;
    -webkit-box-shadow: none;
    box-shadow: none;
}

#payment-details {
    background: #ddffdd;
    padding: 20px;
    color: black;
    border-radius: 8px;
}

#payment-details ul {
    list-style: none;
    padding: 0;
    margin: 0
}

#payment-details ul li {
    display: flex;
    justify-content: space-between;
    line-height: 22px;
}
</style>


<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center mt-4">
                    <img alt="" id="selectedCardImage" width="250" src="" class="payment-logo">

                    <div class="ml-3">
                        <span class="deposit-method-name mb-0_25" id="cName">ForumPay</span>
                        <a href="/payments/ForumPay/withdraw">
                            <button type="button" class="d-none d-sm-block text-left ml-0_5 p-0 btn btn-link-secondary-before" id="changeMethod"><span><?=__('Back')?></span></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="form">
        <div class="row mt-4">
            <div class="col-12 col-lg-6 offset-lg-3">
                <div class="p-2 p-lg-4 d-flex justify-content-center flex-column">

                    <?php if($payoutDetails["status"] == "pending confirmation"): ?>
                        <h3 class="text-center info">
                            <p><?=__('You will withdraw')?> <?=$transaction["ForumPay"]["amount_in_crypto_currency"]?> <?=$transaction["ForumPay"]["crypto_currency"]?>.</p>
                            <p><?=__('Are you sure to withdraw this amount?')?></p>
                        </h3>
                    <?php endif; ?>

                    <?php if($payoutDetails["status"] == "cancelled"): ?>
                        <h3 class="text-center info">
                            <p><?=__('Your withdraw was cancelled.')?></p>
                        </h3>
                    <?php endif; ?>

                    <?php if($payoutDetails["status"] == "scheduled"): ?>
                        <h3 class="text-center info">
                            <p><?=__('Your withdraw was scheduled.')?></p>
                        </h3>
                    <?php endif; ?>
                    
                    <div class="mt-4 d-flex align-items-center justify-content-between">
                        <?php if($payoutDetails["status"] == "pending confirmation"): ?>
                            <button id="btn-confirm" type="submit" class="btn-default btn-submit w-100"><?=('Confirm')?></button>
                            <button id="btn-cancel" type="submit" class="btn-danger btn-submit w-100 ml-4"><?=('Cancel')?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" integrity="sha512-gOQQLjHRpD3/SEOtalVq50iDn4opLVup2TF8c4QPI3/NmUPNZOk2FG0ihi8oCU/qYEsw4P6nuEZT2lAG0UNYaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js" integrity="sha512-7VTiy9AhpazBeKQAlhaLRUk+kAMAb8oczljuyJHPsVPWox/QIXDFOnT9DUk1UC8EbnHKRdQowT7sOBe7LAjajQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

    let interval = null;

    $('#selectedCardImage').attr('alt', '<?=$method['PaymentMethod']['name']?>');
    $('#selectedCardImage').attr('src', '<?=$method['PaymentMethod']['image']?>');

    $("#btn-cancel").click(function(event) {
        event.preventDefault();

        let paymentId = "<?=$transaction["ForumPay"]["remote_id"]?>";
        let url = `/payments/ForumPay/cancelPayOut/${paymentId}`;

        $("#btn-cancel").prop('disabled', true);
	    $("#btn-cancel").text('<?= __('Processing...'); ?>');

        $.ajax({
            url: url,
            dataType:'json',
            success: function(result) {
                if (result.err) {
                    $("#btn-cancel").prop('disabled', false);
				    $("#btn-cancel").text('<?= __('Cancel'); ?>');

                    swal("<?=__("Error")?>", result.err, "error");
                    return;

                } else {
                    if (result.cancelled) {
                        swal({ 
                            title: "<?=__("Cancelled")?>",
                            text: "<?=__("You cancelled a withdraw")?>",
                            type: "success"
                        }, function() {
                            window.location = "/payments/ForumPay/withdraw";
                        });
                    } else {
                        swal("<?=__("Error")?>", "<?=__("Cancelling a withdraw was failed.")?>", "error");
                    }
                }
            }
        });   
    });

    $("#btn-confirm").click(function(event) {
        event.preventDefault();

        let paymentId = "<?=$transaction["ForumPay"]["remote_id"]?>";
        let url = `/payments/ForumPay/confirmPayOut/${paymentId}`;

        $("#btn-confirm").prop('disabled', true);
	    $("#btn-confirm").text('<?= __('Processing...'); ?>');

        $.ajax({
            url: url,
            dataType:'json',
            success: function(result) {
                if (result.err) {
                    $("#btn-confirm").prop('disabled', false);
				    $("#btn-confirm").text('<?= __('Confirm'); ?>');

                    swal("<?=__("Error")?>", result.err, "error");
                    return;

                } else {
                    if (result.confirmed) {
                        swal({ 
                            title: "<?=__("Confirmed")?>",
                            text: "<?=__("Your withdraw was confirmed.")?>",
                            type: "success"
                        });

                        $("#btn-cancel").hide();
                        $("#btn-confirm").hide();
                        $(".info").html(`<p>Your withdraw was scheduled.<br/>Expected time: ${result.wait_time}</p>`);

                        // getPayoutDetails();
                        // interval = setInterval(() => getPayoutDetails(), 5000);

                    } else {
                        swal("<?=__("Error")?>", "<?=__("Confirming a withdraw was failed.")?>", "error");
                    }
                }
            }
        });   
    });
    

    // function getPayoutDetails() {
    //     let paymentId = "<?=$transaction["ForumPay"]["remote_id"]?>";
    //     let url = `/payments/ForumPay/confirmPayOut/${paymentId}`;

    //     $.ajax({
    //         url: url,
    //         dataType:'json',
    //         success: function(result) {
    //             if (result.err) return;
                
    //             if (result.status == "scheduled") {
    //                 $(".info").html(`<p>Your withdraw was scheduled.<br/>Expected time: ${result.wait_time}</p><p>Please don't refresh the page to track your payment</p>`);
    //             } else if (result.status == "")
    //         }
    //     }); 
    // }

    function resizeIframe() {
        let iframe = parent.document.querySelector("#withdraw-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });
</script>