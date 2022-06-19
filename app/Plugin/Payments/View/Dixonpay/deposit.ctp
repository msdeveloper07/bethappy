<style>
.bg-dark-blue {
    background-color: #151338 !important;
}
.payment-card:not(.payment-method-card) {
    height: 164px;
}
.payment-card:not(.payment-method-card) {
    height: 137px;
    -ms-flex-preferred-size: calc(25% - 12px);
    flex-basis: calc(25% - 12px);
    max-width: 221px;
    border-radius:21px;
}
.bg-blue-light {
    background-color: #24224e !important;
}
.cards-wrapper .payment-card {
    margin: 5px;
    border: 0;
    color:#fff;
}
.form-process-deposit .payment-card {
    
    color:#fff;
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
light:hover, a.bg-blue-light:focus, button.bg-blue-light:hover, button.bg-blue-light:focus {
    background-color: #14132a !important;
}
.deposit .card-width {
    width: 558px;
}
.deposit:not(.deposit-modal) {
    min-height: 699px;
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
.deposit-sum h4, .deposit-sum .h4,
.bonus-selection h4, .bonus-selection .h4 {
    font-size: 1.125rem;
    line-height: 1.2778;
    font-weight: 700;
    margin-top: 1rem;
    margin-bottom: 0;
}
.deposit-sum .form-control {
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
    padding-bottom: 0.85rem !important;
    display: block;
    height: 3.25rem;
    padding: .75rem .75rem;
    font-size: .875rem;
    font-weight: bold;
    line-height: 1.57;
    color: #f8b221;
    background-color: #1c0028;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,0);
    border-radius: 2rem;
    -webkit-box-shadow: none;
    box-shadow: none;
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
}
.mt-2_5, .my-2_5 {
    margin-top: 1.25rem !important;
}
.text-primary {
    color: #f8b221 !important;
}
.deposit-sum .deposit-value-cell.active {
    background-color: #1c0028 !important;
    padding: 11px 0;
}
.border-secondary {
    border-color: #2ae8de !important;
}
.deposit .btn-lg, .deposit .btn-group-lg>.btn {
    border-radius: 2rem;
}

</style>

<div class="container-fluid">
    
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        
        <div class="row">
        
            <div class="col-sm-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">
                <div class="table-responsive-sm pt-2">
                    
                    <div class="deposit d-flex flex-column bg-dark-blue">
                        <div class="deposit-new card-width d-flex mx-sm-auto flex-column justify-content-between pb-0 pb-sm-3 flex-grow-1">
                            <form class="form-process-deposit pb-3 pt-md-2" method="post" action="/payments/dixonpay/deposit" id="ccTest">
                                <div class="pt-2_5 px-1">
                                    
                                    <input type="hidden" name="user_IP" value="127.0.0.1">
                                    <input type="hidden" name="payMethod" value="" id='payMethod'>
                                    <div class="deposit-method d-flex mb-3 px-1 px-sm-0"><button type="button" data-name=""
                                            class="d-flex flex-column align-items-center justify-content-around payment-card p-1 position-relative small without-action bg-blue-light px-md-2 btn btn-transparent" id="selectedCardName">
                                            <div
                                                class="payment-card-icon-container d-flex flex-column justify-content-center align-items-center position-absolute w-100 h-100">
                                                <img alt="" id="selectedCardImage" class="mw-100 mh-100"
                                                    src="">
                                            </div>
                                        </button>
                                        <div class="pl-2 pl-sm-3 mt-0_5 pt-0_25 d-flex flex-column"><span
                                                class="deposit-method-name mb-0_25" id="cName"></span><a href="/payments/paymentsModes/methods"><button
                                                type="button"
                                                class="d-none d-sm-block text-left ml-0_5 p-0 btn btn-link-secondary-before" id="changeMethod"><span>Change</span></button></a>
                                        </div>
                                    </div>
                                    <div class="mt-3"></div>
                                    <div class="deposit-sum mt-1">
                                        <div class="d-flex flex-nowrap w-100 text-center mb-1 mb-md-1_5 overflow-hidden">
                                            <div class="d-flex justify-content-between w-100 align-items-baseline"><span
                                                    class="h4 d-block ml-2 ml-sm-0 pl-0_25 pl-sm-0_5">Deposit sum</span>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex flex-column flex-md-row justify-content-md-between align-items-baseline position-relative form-group-amount mx-0_25 mb-1_5 mb-md-2 mb-1 form-group">
                                            <div class="w-100">
                                                <div class="text-right d-flex align-items-center input-text-container position-relative"><input
                                                        id="input-amount" name="amount" placeholder="Amount" type="text"
                                                        class="position-relative w-100 input-inner px-2_5 form-control" value="10"></div>
                                                <div class="d-flex font-weight-bold line-height-1 text-danger form-element-error pt-0_5 pl-2_5">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mx-0_25 pl-sm-0_5">
                                            <div id="10"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2 active text-primary border border-secondary"
                                                tabindex="0" role="button">€10</div>
                                            <div id="25"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€25</div>
                                            <div id="50"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€50</div>
                                            <div id="100"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€100</div>
                                            <div id="200"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€200</div>
                                            <div id="300"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€300</div>
                                            <div id="400"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€400</div>
                                            <div id="500"
                                                class="deposit-value-cell d-inline-block font-weight-bold flex-grow-1 position-relative bg-blue-light text-center py-sm-2"
                                                tabindex="0" role="button">€500</div>
                                        </div>
                                    </div>
                                    <div class="bonus-selection mt-2_5 mb-0_25">
                                        <div
                                            class="d-flex bonuses-available-text flex-column flex-sm-row align-items-baseline justify-content-between mb-1 mb-sm-1_5 ml-2 ml-sm-0 pl-0_25 flex-wrap">
                                            <span class="bonus-headline h4 mb-0_5 d-block">Card Details</span></div>
                                       
                                                <div class="card-body">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="cardNo">Card No</label>
                                                                <input class="form-control account-input-outline cardNo" minlength="16" maxlength="19" name="cardNo" type="text" id="cardNo" placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-end">
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="cardExpireMonth">Card Expire Month</label>
                                                                <input class="form-control account-input-outline cardExpireMonth" minlength="2" maxlength="2" name="cardExpireMonth" type="text" id="cardExpireMonth" placeholder="MM" onkeyup="monthCheck(this);if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="cardExpireYear">Card Expire Year</label>
                                                                <input class="form-control account-input-outline cardExpireYear" minlength="4" maxlength="4" name="cardExpireYear" type="text" id="cardExpireYear" placeholder="YYYY" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="cardSecurityCode">Card Security Code</label>
                                                                <input class="form-control account-input-outline cardSecurityCode" minlength="3" maxlength="3" name="cardSecurityCode" type="text" id="cardSecurityCode" pattern="\d*" placeholder="&#149;&#149;&#149;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                            </div>

                                    </div>
                                    <div class="mt-3 mx-2 mx-sm-0 mb-0_25 mb-sm-0 mx-lg-0_25 px-0_25 px-sm-0"><button type="submit"
                                            class="position-relative d-block mt-3 mx-auto w-100 d-block btn btn-primary btn-lg">
                                            <div class="btn-content">Pay</div>
                                        </button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
    function monthCheck(input) {
        if( input.classList.contains("cardExpireMonth") ){
                if (input.value < -1) input.value = '';
                if (input.value > 12) input.value = '';
            }
        }
        
    $(document).ready(function(){

        $('#cardNo').on('keypress change', function () {
  $(this).val(function (index, value) {
	  return value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ');
  });
});


$('#ccTest').on("change", function(){
    $('#ccTest').validate({
    rules: {
        cardNo: {
            required: true,
            creditcard: true,
            minlength: 16,
            maxlength: 19
        },
        cardExpireMonth: {
            required: true,
            minlength: 2,
            maxlength: 2 
        },
        cardExpireYear: {
            required: true,
            minlength: 4,
            maxlength: 4,
            CCExp: { 
                formMonth: "#cardExpireMonth",
                formYear: "#cardExpireYear"
            }
        },
        cardSecurityCode:{
            required: true,
            minlength: 3,
            maxlength: 3 
        }
    }
    });


    $.validator.addMethod("CCExp", function(value, element, params) {
            var minMonth = new Date().getMonth() + 1;
            var minYear = new Date().getFullYear();

            var formMonth = $("#cardExpireMonth").val();
            var formYear = $("#cardExpireYear").val();

        console.log('formMonth: ', formMonth);

            var month = parseInt(formMonth);
            var year = parseInt(formYear);

            if ((year > minYear) || ((year === minYear) && (month >= minMonth))) {
                return true;
            } else {
                return false;
            }
    }, "Invalid Expiration Date!");
});
});
</script>
<script>
    var url = window.location.pathname.split('/');
    var methodName = url[2];
    $('#cName').text(methodName);
    $('#selectedCardImage').attr('alt',methodName);
    $('#selectedCardImage').attr('src','/img/casino/payments/'+methodName+'.png');
    $('#payMethod').val(methodName);
    
    
    console.log('secondLevelLocation>>>>>>', methodName);

$('.deposit-value-cell').on('click',function(){
    var amountId = $(this).attr('id');
    $('#input-amount').val(amountId);
    $('.deposit-value-cell').removeClass('active text-primary border border-secondary');
    $(this).addClass('active text-primary border border-secondary');
    
});

</script>