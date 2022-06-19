<style>
.bg-light-yellow{
    background-color: #f7e9b5;
    color:#212529;
    border-radius: 30px;
}
.response-result {
    text-align: center;
}
.red{color:red}
.green{color:green}
.response-page .message{
    font-weight:normal;
}
.response-page .form-group{
    margin-top:25px;
}


</style>
<div class="container-fluid">
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard response-page">
        <div class="row">
            <div class="col-sm-12 col-md-6 offset-md-3 col-lg-6 offset-lg-3 mt-5">

            <div class="table-responsive-sm pt-2">
                    <div class="deposit d-flex flex-column bg-light-yellow">
                        <div class="response-result d-flex flex-column font-weight-bold p-3 px-sm-4_5 py-sm-4" id="deposit-options">
                            <span class="h4 mb-1_5 text-capitalize head-title"><b>Response Result</b></span>
                            <div class="d-flex flex-column">
                            <div class="form-group">
                                <label for="inputLimit">Provider:</label>
                                <span id="provider"> </span>
                            </div>
                            <p class="message" id="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var url_string = window.location.href;
    var url = new URL(url_string);
    var provider = url.searchParams.get("provider");
   
    $('#provider').text(provider);
    if(url.searchParams.get("error")){
        var error = url.searchParams.get("error");
        $('#message').text(error);
        $('#message').addClass('red');
    }else{
        var message = url.searchParams.get("message");
        $('#message').text(message);
        $('#message').addClass('green');
    }

</script>