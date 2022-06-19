<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
    background: #19648e;
}

.payment-logo {
    width: 210px;
    background-color: #dee2e6 !important;
    border-radius: 18px;
    padding: 10px;
}

.deposit-method-name {
    font-size: 1.4375rem;
    line-height: 1.2609;
    font-weight: 700;
    text-transform: capitalize;
}

#crypto-send-details {
    background: #ddffdd;
    padding: 20px;
    color: black;
    border-radius: 8px;
}

#crypto-send-details ul {
    list-style: none;
    padding: 0;
    margin: 0
}

#crypto-send-details ul li {
    display: flex;
    justify-content: space-between;
    line-height: 22px;
}

#crypto-send-details img {
    width: 300px;
    height: 300px;
}
</style>


<div class="container-fluid vh-100 d-flex align-items-center justify-content-center flex-column">

    <div class="row w-100">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center">
                    <img alt="" id="selectedCardImage" width="250" src="" class="payment-logo">

                    <div class="ml-3">
                        <span class="deposit-method-name mb-0_25" id="cName">ForumPay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row w-100">
        <div class="col-12 col-lg-6 offset-lg-3" style="max-width: 600px; margin:auto;">
            <div id="crypto-send-details" class="mt-4 w-100">
                <ul>
                    <li>
                        <label><?=__('Address')?>:</label>
                        <value><strong class="crypto-address"><?=$transaction['ForumPay']['address']?></strong></value>                                        
                    </li>
                    <li>
                        <label><?=__('Order Amount')?>:</label>
                        <value><strong class="order-amount"><?=$result['invoice_amount']?> <?=$result['invoice_currency']?></strong></value>                                        
                    </li>
                    <li>
                        <label class="crypto-currency">1 BTC:</label>
                        <value><strong class="currency"><?=$transaction['ForumPay']['rate']?></strong></value>
                    </li>
                    <li>
                        <label><?=__('Amount')?>:</label>
                        <value><strong class="total-exchange-amount"><?=$result['amount']?></strong></value>
                    </li>
                    <li>
                        <label><?=__('Minumum Confirmations')?>:</label>
                        <value><strong class="min-confirm"><?=$result['min_confirmations']?></strong></value>
                    </li>
                    <li>
                        <label><?=__('Received Confirmations')?>:</label>
                        <value><strong class="confirms"><?=$result['confirmations']?></strong></value>
                    </li>
                    <li>
                        <label><?=__('Expected time to wait')?>:</label>
                        <value><strong class="wait-time"><?=$result['wait_time']?></strong></value>
                    </li>
                </ul>
                
                <div class="mt-4 text-center">
                    <img src="<?=$transaction['ForumPay']['qrcode_url']?>" width="300" height="300" onload="resizeIframe();" />
                </div>

                <h2 class="text-center mt-4 status"><?=$result['status']?></h2>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" integrity="sha512-gOQQLjHRpD3/SEOtalVq50iDn4opLVup2TF8c4QPI3/NmUPNZOk2FG0ihi8oCU/qYEsw4P6nuEZT2lAG0UNYaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js" integrity="sha512-7VTiy9AhpazBeKQAlhaLRUk+kAMAb8oczljuyJHPsVPWox/QIXDFOnT9DUk1UC8EbnHKRdQowT7sOBe7LAjajQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

    $('#selectedCardImage').attr('alt', '<?=$method['PaymentMethod']['name']?>');
    $('#selectedCardImage').attr('src', '<?=$method['PaymentMethod']['image']?>');

    function checkPayment() {

        let orderNumber = "<?=$transaction['ForumPay']['order_number']?>";
        
        if (!orderNumber) return;

        $.ajax({
            url: `/payments/ForumPay/checkPayment/${orderNumber}`,
            dataType:'json',
            success: function(result) {
                if (result.err) return;

                $("#crypto-send-details .confirms").text(result.confirmations);
                $("#crypto-send-details .wait-time").text(result.wait_time);
                $("#crypto-send-details .status").text(result.status);
            }
        });
    }

    setInterval(() => {
        checkPayment();
    }, 5000);

//    $( "#startPayment" ).click(function() {


// 	   $('#startPayment').hide();
// 	   $('#waitPayment').show();



//      var cryptocurr =      $( "#cryptoSel option:selected" ).text();

//       var paymentID =      $( "#paymentID" ).val();

// 	  var url =  '/payments/ForumPay/startPayment/'+cryptocurr+'/'+curr+'/'+amount+'/'+paymentID+'/'+referenceNo;
    
    
// 	 $.ajax({url: url, success: function(result){
//           $('#qrcode').html(result);
// 		  $( "#qrcode" ).scroll();
// 		    $('#startPayment').hide();
// 	        $('#waitPayment').hide();
// 		   $('#checkPayment').show();
//      }});

//      });


// 	  $( "#checkPayment" ).click(function() {

//            $('#startPayment').hide();
// 	        $('#waitPayment').show();
// 		   $('#checkPayment').hide();
    

//       var cryptocurr =      $( "#cryptoSel option:selected" ).text();

//       var paymentID =      $( "#paymentID" ).val();

// 	  var address =  $( "#address" ).val();
                
// 	  var url =  '/payments/ForumPay/callback?pos_id=widget&currency='+cryptocurr+'&payment_id='+paymentID+'&address='+address;
    
    
// 	 $.ajax({url: url, success: function(result){
            
// 			if(result == 'confirmed'){
// 				window.location.href = "/payments/ForumPay/success?type=Deposit&provider=Forumpay&transaction_id="+paymentID;
// 			}else{
// 			    window.location.href = "/payments/ForumPay/failed?type=Deposit&provider=Forumpay&transaction_id="+paymentID;
// 			}
            
//      }});

//      });



//   setTimeout(function(){ 
    
// 	 var cryptocurr =      $( "#cryptoSel option:selected" ).text();

//       var paymentID =      $( "#paymentID" ).val();

// 	  var address =  $( "#address" ).val();
                
// 	  var url =  '/payments/ForumPay/callback?pos_id=widget&currency='+cryptocurr+'&payment_id='+paymentID+'&address='+address;
    
    
// 	 $.ajax({url: url, success: function(result){
            
// 			if(result == 'confirmed'){
// 				window.location.href = "/payments/ForumPay/success?type=Deposit&provider=Forumpay&transaction_id="+paymentID;
// 			}
            
//      }});
    
//    }, 300000);


    function resizeIframe() {
        let iframe = parent.document.querySelector("#deposit-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });
</script>