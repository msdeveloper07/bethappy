<div class="terms-dialog-content-inner">
    <div class="termsdialog-title"><h1 ng-bind-html="data.title"></h1></div>
    <div class="termsdialog-message" ng-bind-html="data.content"></div>
    <div class="text-right"><br><a href="/page/#/{{data.url}}" class="btn btn-grey"><?=__('Read More');?></a> <a target="_blank" href="/page/#/{{data.url}}" class="btn btn-grey"><?=__('Download');?></a></div>
    <br>
    <div class="text-center">
        <button class="btn btn-success ngdialog-button" ng-click="accept_terms()"><?=__('Accept');?></button>
        <a href="/users/logout" class="btn btn-danger ngdialog-button"><?=__('Decline');?></a>
    </div>
</div>