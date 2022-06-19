<main class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12"><h1 class="title mb-5 ng-binding"><?= __('Bonuses'); ?></h1></div>

            <div class="col-sm-12 col-md-3 offset-md-9 col-lg-3 offset-lg-9 mb-3">
                <div clas="form-group">
                    <label class="small"><?= __('Filter by status'); ?></label>
                    <select class="custom-select mr-1" name="filter" id="filter" ng-model="filterByStatus" required>
                        <option value="" ng-selected="true"><?= __('All'); ?></option>
                        <option ng-repeat="(bonus_status_value, bonus_status_desc) in bonus_statuses" value="{{bonus_status_value}}">{{bonus_status_desc}}</option>

                    </select>
                </div>
            </div>

            <div class="card bonus-card col-md-12 mb-3" ng-repeat="bonus in bonuses| filter: {Bonus: {status: filterByStatus}}  | orderBy:'-id'">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="w-25">
                            <img src="/Layout/Artofslots/images/promotions/present.png" alt="{{bonus.BonusType.name}}"height="50"/>
                            <h5 class="card-title mt-2">{{bonus.BonusType.name}}</h5>
                        </div>
                        <div>
                            <p class="card-text small mb-0"><?= __('Payoff amount'); ?>: {{bonus.Bonus.payoff_amount}}</p>
                            <p class="card-text small mb-0"><?= __('Initial amount'); ?>: {{bonus.Bonus.initial_amount}}</p>
                            <p class="card-text small mb-0"><?= __('Turnover amount'); ?>: {{bonus.Bonus.turnover_amount}}</p>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <p class="card-text small mb-0"><?= __('Activated'); ?>:</p>
                                <p class="card-text small mb-0">{{bonus.Bonus.activated}}</p>                                
                            </div>
                            <div class="d-flex flex-column">
                                <p class="card-text small mb-0"><?= __('Released'); ?>:</p>
                                <p class="card-text small mb-0">{{bonus.Bonus.released}}</p>
                            </div>
                        </div>
                        <div ng-switch on="bonus.Bonus.status">
                            <div ng-switch-when="-2">
                                <span class="my-2 px-4 py-2 badge badge-danger"><?= __('Cancelled'); ?></span>
                            </div>
                            <span ng-switch-when="-1">
                                <span class="my-2 px-4 py-2 badge badge-success"><?= __('Completed'); ?></span>

                            </span>
                            <span ng-switch-when="0">
                                <span class="my-2 px-4 py-2 badge badge-info"><?= __('Available'); ?></span>

                            </span>
                            <span ng-switch-when="1">
                                <span class="my-2 px-4 py-2 badge badge-warning"><?= __('Active'); ?></span>

                            </span>
                            <span ng-switch-default></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div ng-if="bonuses.length <= 0">
            <p> <?= __('No bonuses to show.'); ?></p>
        </div>
    </div>

</main>

<!--        <div ng-if="bonuses.length > 0">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th><?= __('ID'); ?></th>
                        <th><?= __('Type ID'); ?></th>
                        <th><?= __('Initial Amount'); ?></th>
                        <th><?= __('Payoff Amount'); ?></th>
                        <th><?= __('Turnover Amount'); ?></th>
                        <th><?= __('Balance'); ?></th>
                        <th><?= __('Created'); ?></th>
                        <th><?= __('Activated'); ?></th>
                        <th><?= __('Released'); ?></th>
                        <th><?= __('Status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="bonus in bonuses| orderBy:'-id'">
                        <td>{{bonus.Bonus.id}}</td>
                        <td>{{bonus.Bonus.type_id}}</td>
                        <td>{{bonus.Bonus.initial_amount}}</td>
                        <td>{{bonus.Bonus.payoff_amount}}</td>
                        <td>{{bonus.Bonus.turnover_amount}}</td>   
                        <td>{{bonus.Bonus.balance}}</td>
                        <td>{{bonus.Bonus.created}}</td>
                        <td>{{bonus.Bonus.activated}}</td>
                        <td>{{bonus.Bonus.released}}</td>    
                        <td>{{bonus.Bonus.status}}</td>   
                    </tr>
                </tbody>
            </table>


            <ul uib-pagination total-items="paginate_bo.totalrecords" ng-model="paginate_bo.currentpage" ng-change="pageChanged(paginate_bo.currentpage)" items-per-page="paginate_bo.itemsperpage" max-size="paginate_bo.maxSize" class="pagination-sm justify-content-end"></ul>
        </div>

-->



