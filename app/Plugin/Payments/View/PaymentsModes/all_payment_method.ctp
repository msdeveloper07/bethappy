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
                        <div class="deposit-options d-flex flex-column font-weight-bold p-3 px-sm-4_5 py-sm-4" id="deposit-options">
                            <span class="h4 mb-1_5 text-capitalize head-title">Select method</span>
                            <div class="d-flex flex-column">
                                <div class="d-flex others flex-wrap cards-wrapper">
                                    <a href="/payments/dixonpay/index">
                                        <button type="button" data-name="Dixonpay" 
                                            class="d-flex flex-column align-items-center justify-content-around payment-card p-1 position-relative px-2_5 pt-2_5 pb-2 pb-sm-2_5 px-lg-5 bg-blue-light btn btn-transparent paymentMethod">
                                        
                                                <div
                                                    class="payment-card-icon-container d-flex flex-column justify-content-center align-items-center position-absolute w-100 h-100">
                                                    <img alt="Dixonpay" class="mw-100 mh-100"
                                                        src="">
                                                    
                                                </div>
                                            
                                        </button>
                                    </a>
                                    <a href="/payments/wonderlandpay/index">
                                        <button type="button" data-name="WonderlandPay"
                                            class="d-flex flex-column align-items-center justify-content-around payment-card p-1 position-relative px-2_5 pt-2_5 pb-2 pb-sm-2_5 px-lg-5 bg-blue-light btn btn-transparent paymentMethod">
                                            
                                            <div
                                                class="payment-card-icon-container d-flex flex-column justify-content-center align-items-center position-absolute w-100 h-100">
                                                <img alt="WonderlandPay" class="mw-100 mh-100"
                                                    src="/img/casino/payments/wonderlandpay.png">
                                                
                                            </div>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END PAGE CONTENT-->
</div>
