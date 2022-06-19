<div class="modal-tab-title mb-3 ng-scope"><h2><?= __('Transaction Log'); ?></h2></div>
<div class="tab-pane" ng-if="transactions.length > 0">


    <div class="modal-box-wrapper">
        <div class="modal-box">
            <?= __('Bets'); ?> <span class="modal-badge">{{totals.Bets ? (totals.Bets | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Wins'); ?> <span class="modal-badge">{{totals.Wins ? (totals.Wins | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Bonus Bets'); ?> <span class="modal-badge">{{totals.BonusBets ? (totals.BonusBets | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Bonus Wins'); ?> <span class="modal-badge">{{totals.BonusWins ? (totals.BonusWins | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Deposits'); ?> <span class="modal-badge">{{totals.Deposits ? (totals.Deposits | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Withdraws'); ?> <span class="modal-badge">{{totals.Withdraws ? (totals.Withdraws | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
        <div class="modal-box">
            <?= __('Refunds'); ?> <span class="modal-badge">{{totals.Refunds ? (totals.Refunds | number:0) : 0.00 | currency: totals.Currency:2}}</span>
        </div>
    </div>
    <style>
        table{
            font-size: 12px;
        }
        .table thead th{
            border-bottom: 1px solid #4E565E;
        }
        .table tr, .table td, .table th{
            border:none;
        }
    </style>

    <table class="table table-condensed">
        <thead>
            <tr>
                <th><?= __('Date'); ?></th>
                <th><?= __('Provider'); ?></th>
                <th><?= __('Transaction Type'); ?></th>
                <th><?= __('Amount'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="transaction in transactions| orderBy:'-id'">
                <td>{{transaction.date}}</td>
                <td>{{transaction.model}}</td>
                <td>{{transaction.transaction_type}}</td>
                <td>{{transaction.amount}}</td>           </tr>
        </tbody>
    </table>


    <ul uib-pagination total-items="paginate_tr.totalrecords" ng-model="paginate_tr.currentpage" ng-change="pageChanged(paginate_tr.currentpage)" items-per-page="paginate_tr.itemsperpage" max-size="paginate_tr.maxSize" class="pagination-sm justify-content-end"></ul>

</div>

<div ng-if="transactions.length <= 0">
    <?= __('No data to show.'); ?>
</div>


