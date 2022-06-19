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
</style>

<form id="uqualify-form" method="post" onsubmit="requestDeposit(event);">

    <div class="container-fluid">

        <div class="row">
			<div class="col-12">
				<div class="d-flex align-items-center justify-content-center">
					<div class="d-flex align-items-center">
						<img alt="" id="selectedCardImage" width="250" src="" class="payment-logo">

						<div class="ml-3">
							<span class="deposit-method-name mb-0_25" id="cName">UQualify</span>
							<a href="/payments/deposits/index">
								<button type="button" class="d-none d-sm-block text-left ml-0_5 p-0 btn btn-link-secondary-before" id="changeMethod"><span><?=__('Change')?></span></button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>


        <div id="form">
			<div class="row mt-4">
				<div class="col-12 col-lg-6 offset-lg-3">
					<div class="p-2 p-lg-4 d-flex align-items-center justify-content-center flex-column">
						<div class="form-group w-100">
							<label class="control-label"><?=__('Deposit Sum')?> (<?=$user['Currency']['code']?>)</label>
							<input type="number" id="input-amount" value="10" min="1" class="form-control" required />
						</div>

						<div class="amount-list">
							<div id="10" 
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2 active" 
								tabindex="0" role="button"><?=$user['Currency']['code']?>10</div>
							<div id="25" 
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2" 
								tabindex="0" role="button"><?=$user['Currency']['code']?>25</div>
							<div id="50"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>50</div>
							<div id="100"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>100</div>
							<div id="200"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>200</div>
							<div id="300"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>300</div>
							<div id="400"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>400</div>
							<div id="500"
								class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
								tabindex="0" role="button"><?=$user['Currency']['code']?>500</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mb-5 mt-4">
				<div class="col-12 d-flex justify-content-center">
					<button id="btn-pay" type="submit" class="btn-default btn-submit"><?=('Pay')?></button>
				</div>
			</div>
		</div>

        <div class="text-center mt-5" id="deposit-succeed">
			<p style="color:#a7ff00;"><i class="fa fa-check-circle fa-5x"></i></p>
			<h2>
				<p><?= __('You have just deposited money, %s thanks you and wishes you good luck.', Configure::read('Settings.websiteName')); ?></p>
				<p><?= __('You can start playing.'); ?></p>
			</h2>

			<div class="text-center mt-4">
				<button class="btn btn-default rounded-pill px-4" onclick="window.top.location.href = '/';"><?= __('Go to games'); ?></button>
			</div>
		</div>

		<div class="text-center mt-5" id="deposit-processing">
			<p style="color: #ff005c;"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
			<h2><p><?= __('Your deposit is in processing.'); ?></p></h2>
			<h4><p><?= __('We will notify to your email once payment is approved.'); ?></p></h4>
			<div class="text-center mt-4">
				<a class="btn btn-default rounded-pill px-4" href="mailto:<?= Configure::read('Settings.websiteEmail'); ?>"><?= __('Contact Support'); ?></a>
			</div>
		</div>

		<div class="text-center mt-5" id="deposit-failed">
			<p style="color: #ff005c;"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
			<h2><p><?= __('Your deposit failed.'); ?></p></h2>
			<h5>Error - Invalid Card Number</h5>

			<div class="text-center mt-4">
				<a class="btn btn-default rounded-pill px-4" href="mailto:<?= Configure::read('Settings.websiteEmail'); ?>"><?= __('Contact Support'); ?></a>
			</div>
		</div>
    </div>
</form>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" integrity="sha512-gOQQLjHRpD3/SEOtalVq50iDn4opLVup2TF8c4QPI3/NmUPNZOk2FG0ihi8oCU/qYEsw4P6nuEZT2lAG0UNYaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js" integrity="sha512-7VTiy9AhpazBeKQAlhaLRUk+kAMAb8oczljuyJHPsVPWox/QIXDFOnT9DUk1UC8EbnHKRdQowT7sOBe7LAjajQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('#selectedCardImage').attr('alt', '<?=$method['PaymentMethod']['name']?>');
    $('#selectedCardImage').attr('src', '<?=$method['PaymentMethod']['image']?>');

	$('.deposit-value-cell').on('click',function(){
		var amountId = $(this).attr('id');
		$('#input-amount').val(amountId);
		$('.deposit-value-cell').removeClass('active');
		$(this).addClass('active');
		
	});

	$("#uqualify-form #deposit-succeed").hide();
	$("#uqualify-form #deposit-processing").hide();
	$("#uqualify-form #deposit-failed").hide();

	function requestDeposit(event) {
		event.preventDefault();

		let amount = $("#input-amount").val();

		$("#btn-pay").prop('disabled', true);
		$("#btn-pay").text('<?= __('Processing...'); ?>');

		$.ajax({        
			url: `/payments/uqualify/deposit/${amount}`,
			type : 'POST',
			data : {
				post: 1
			},
			dataType:'json',
			success : function(data) {   

				$("#btn-pay").prop('disabled', false);
				$("#btn-pay").text('<?= __('Pay'); ?>');
				
				if (data.success) {
					window.open(data.redirect_url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
				} else {
					swal("<?=__("Error")?>", data.message, "error");
				}
				// if (data.status === 'PENDING') {
				// 	window.open(data.url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');

				// } else {

				// 	if (data.status === 'APPROVED') {
				// 		$("#uqualify-form #deposit-succeed").show();
                //         $("#uqualify-form #form").hide();

				// 	} else {
				// 		$("#uqualify-form #deposit-input").hide();
				// 		$("#uqualify-form #deposit-failed h5").text(`Error - ${data.description}`);
				// 		$("#uqualify-form #deposit-failed").show();

                //         $("#uqualify-form #form").hide();
				// 	}
				// }

				resizeIframe();
			},
			error : function(request, error) {
				$("#uqualify-form #submit").prop('disabled', false);
				$("#uqualify-form #submit").text('<?= __('Pay'); ?>');

				alert(request.responseText);

				resizeIframe();
			}
		});
	}

	function resizeIframe() {
        let iframe = parent.document.querySelector("#deposit-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });
</script>