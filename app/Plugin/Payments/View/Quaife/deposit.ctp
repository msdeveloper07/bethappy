<style>
    body{
        background-color: #151F2B;
    }
    .wpwl-container{
        display: flex;
        justify-content: center;
    }
    .wpwl-form-card{
        border-radius:  5px;
        background-color: #001E39;
        border:none;
        box-shadow: none;
        webkit-box-shadow: none;
    }
    .wpwl-button-pay{
        background-color: #3898EC;
        color: white;
        border: 0;
        line-height: inherit;
        text-decoration: none;
        cursor: pointer!important;
        padding: 0.5rem 3rem;
        border-radius: 10px;
        text-transform: uppercase;
        font-size: 14px;
        display: flex;
        justify-content: center;
        align-items: center;

    }
    .wpwl-button-pay:hover{
        background-color: #3898EC;
        background-image: none;
        border: none;
        color: #fff!important;
        opacity:0.8;
    }
    .wpwl-control{
        font-family: Roboto, sans-serif;
        border-radius: 4px!important;
        background-color: #fff;
        /*border: 1px solid #c0994e;*/
        font-size: 12px;
        display: block;
        padding: .5rem .75rem;
        line-height: 1.25;
        color: #464a4c;
        width:100%;
        height: 40px;
    }
    .wpwl-label{
        font-size: 12px;
        color:#fff;
        margin-bottom: .5rem;
    }
    .wpwl-wrapper iframe{
        height: 40px;
        font-size: 12px;
    }
    .wpwl-group{
        margin-bottom: 0.5rem;
    }
    .wpwl-group-submit{
        margin:0.5em auto;
    }
    .wpwl-group-cardNumber, .wpwl-group-brand{
        width:100%;
    }

    .wpwl-group-expiry{
        width: 49%;
        margin-right: 2%;
    }
    .wpwl-group-cardHolder{
        width: 49%;
    }
    .wpwl-group-cardNumber, .wpwl-group-cardHolder, .wpwl-form-card .wpwl-group-brand-v2{
        padding-right: 0;
    }
    .wpwl-form{
        max-width: 40em;
    }

    .wpwl-group{
        /*        width:40%;*/
        /*margin: 10px auto;*/
    }
    .wpwl-label-brand, .wpwl-wrapper-brand{
        display:none;
    }

    .wpwl-message.wpwl-has-error{
        background-color: #F2DEDE;
        border:none;

    }


</style>

<div class="quaife">
    <div class="container">
        <div class="row row-heading">
            <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER']); ?>" class="btn-icon"><i class="fas fa-angle-double-left"></i></a>
            <img src="/plugins/payments/quaife.png" class=""/>
        </div>

        <br>
        <div class="row">
            <div class="col">
                <form action="http://82.214.112.218/payments/quaife/status" class="paymentWidgets" data-brands="<?= $method; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var wpwlOptions =
            {
                style: "card",
                locale: "<?= $language; ?>",
                showCVVHint: true,
                onReady: function () {
                    $(".wpwl-button-pay").text('DEPOSIT NOW');
                }
            }</script>
<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkout_id; ?>"></script>