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

<form id="astropay-form" method="post" onsubmit="requestDeposit(event);">

    <div class="container-fluid">

        <div class="row">
			<div class="col-12">
				<div class="d-flex align-items-center justify-content-center">
					<div class="d-flex align-items-center mt-4">
						<img alt="" id="selectedCardImage" width="250" src="" class="payment-logo">

						<div class="ml-3">
							<span class="deposit-method-name mb-0_25" id="cName"><?=$method['PaymentMethod']['name']?></span>
							<a href="/payments/deposits/index">
								<button type="button" class="d-none d-sm-block text-left ml-0_5 p-0 btn btn-link-secondary-before" id="changeMethod"><span><?=__('Change')?></span></button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</form>


<script>
    $('#selectedCardImage').attr('alt', '<?=$method['PaymentMethod']['name']?>');
    $('#selectedCardImage').attr('src', '<?=$method['PaymentMethod']['image']?>');


	function resizeIframe() {
        let iframe = parent.document.querySelector("#deposit-iframe");
        iframe.style.height = '200px';
    }
        $(document).ready(function() {

        resizeIframe();
    });
</script>