<main class="main" ng-hide="isLanding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ml-sm-auto col-lg-12 px-3">
                <div class="d-flex flex-column justify-content-start align-items-start mb-4">
                    <h1><?= __('{{title}}'); ?></h1>
                </div>
            </div>
        </div>

        <div compile="'{{thisCanBeusedInsideNgBindHtml}}'"></div>

    </div>
</main>

<main class="main" ng-show="isLanding">
    <div class="container">
        <h1 class="title mb-5 ng-binding"><?= __('{{title}}'); ?></h1>
        <div class="row">
            <div compile="'{{thisCanBeusedInsideNgBindHtml}}'"></div>
        </div>
    </div>
</main>
