<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header text-white">
            <h5 class="modal-title" id="exampleModalLabel"><?= __('{{title}}'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div compile="'{{thisCanBeusedInsideNgBindHtml}}'"></div>
            <contactform ng-if="item == 'contact-us'"></contactform>
            <flashdetect ng-if="item == 'flashdetect'"></flashdetect>
        </div>
    </div>
</div>
