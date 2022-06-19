<div class="container-fluid">
    
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        
        <div class="row">
        
            <div class="col-sm-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">
                <div class="table-responsive-sm pt-2">
                    
                    <div class="deposit d-flex flex-column">
                        <div class="deposit-new card-width d-flex mx-sm-auto flex-column justify-content-between pb-0 pb-sm-3 flex-grow-1">
							<div class="pt-2_5 px-1">
                                    
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
								
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var url = window.location.pathname.split('/');
    var methodName = url[2];
    $('#cName').text(methodName);
    $('#selectedCardImage').attr('alt',methodName);
    $('#selectedCardImage').attr('src','/img/casino/payments/'+methodName+'.png');
    $('#payMethod').val(methodName);



	function resizeIframe() {
        let iframe = parent.document.querySelector("#deposit-iframe");
        console.log(iframe);
        iframe.style.height = iframe.contentWindow.document.documentElement.scrollHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });
</script>