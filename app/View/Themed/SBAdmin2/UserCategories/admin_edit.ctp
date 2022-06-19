<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item" aria-current="page"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <?= $this->Html->link(__('Categories'), ['plugin' => false, 'controller' => 'UserCategories', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Categories')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Categories'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">
                <?= $this->element('edit'); ?>
            </div>
        </div>
    </div>
</div>

<style>
.input.text {
   display: flex;
   justify-content: space-between;
   align-items: center;
}
.input.text input{
    max-width: 90%;
}
#color-display {
    margin-left: 10px;
    /*position: relative;*/
    border-radius: 4px;
    width: 28px;
    height: 28px;
    bottom: 37px;
    left: 100%;
    right: 0;
    background: url('/css/color-picker/images/select2.png') repeat scroll center center transparent;
}    
</style>
<link rel="stylesheet" media="screen" type="text/css" href="/css/color-picker/color-picker.css" />
<script type="text/javascript" src="/js/color-picker/color-picker.js"></script>


<script type="text/javascript">
$(document).ready(function() {
    $('#UserCategoryColor').parent().append('<div id="color-display" style="background-color:' + ($('#UserCategoryColor').val() || '#fff') + '"></div>');
    $('#UserCategoryColor, #color-display').ColorPicker({
	color: '#0000ff',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(200);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(200);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#UserCategoryColor').val('#' + hex);
		$('#color-display').css('backgroundColor', '#' + hex);
	}
});
});
</script>
