<div class="mga-dialog-content-inner">
    <div class="ngdialog-message">
        <div class="text text-center"><b>{{data.text}}</b></div>
        <ul class="text-center">
            <li>{{data.deposits.text}} : {{data.deposits.amount}} {{currency}}</li>
            <li>{{data.wagers.text}} : {{data.wagers.amount}} {{currency}}</li>
            <li>{{data.losses.text}} : {{data.losses.amount}} {{currency}}</li>
            <li>{{data.wins.text}} : {{data.wins.amount}} {{currency}}</li>
        </ul>
        
    </div>
    <div class="text text-center"><?=__('Please select continue if you want to stay on the website. Otherwise you can logout.');?></div>
    <br>
    <div class="text-center">
        <a href="/users/logout" class="btn btn-danger ngdialog-button ngdialog-button-primary"><?=__('Log out');?></a>
        <button class="btn btn-success ngdialog-button ngdialog-button-primary ngdialog-close" ng-click="continue()"><?=__('Continue');?></button>
    </div>
</div>