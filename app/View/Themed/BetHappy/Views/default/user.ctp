<?php $error_message = $this->Session->flash();
    if (strlen($error_message)>5): ?>
        <script>
            var n = noty({text: '<?= $error_message;?>', type: 'error'});
        </script>
<?php endif; ?>

<div class="container">
    <div class="row">
        <?= $this->element('layout-slots/header-user'); ?>
    </div>
    <div class="main-content">
    <div class="row">
        <div class="col-md-12" ng-controller="HeaderController"><ng-include src="'/Views/view/header'"></ng-include></div>
    </div>
    <div class="row ">

        <div class="col-md-2 remove-right-padding"><ng-include src="'/Views/view/user-left'"></ng-include></div>

        <div class="col-md-7 minimal"><ng-include src="'/Views/view/user-content'"></ng-include></div>

        <div class="col-md-3 remove-left-padding" ng-controller="RightController"><ng-include src="'/Views/view/right'"></ng-include></div>

    </div>
    <div class="row">
        <div class="col-md-12" id="footer"><?= $this->element('layout-slots/footer-slot'); ?></div>
    </div>
        </div>
</div>
