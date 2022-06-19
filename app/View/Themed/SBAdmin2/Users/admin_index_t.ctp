<style>.ui-grid-menu-mid::-webkit-scrollbar{
        width: 5px;
        height: 5px;
    }
    .rag-red {
        background-color: lightcoral;
    }
    .rag-green {
        background-color: lightgreen;
    }
    .rag-amber {
        background-color: lightsalmon;
    }

    .rag-red-outer .rag-element {
        background-color: lightcoral;
    }

    .rag-green-outer .rag-element {
        background-color: lightgreen;
    }

    .rag-amber-outer .rag-element {
        background-color: lightsalmon;
    }



</style>
<div ng-controller="UsersController">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">Players</li>
        </ol>
    </nav>
    <br>
    <div class="d-flex justify-content-between">
        <h1 class="h3 mb-1 text-gray-800"><i class="fas fa fa-users"></i> <?= __('Players'); ?></h1>
        <?= $this->Html->link('<i class="fas fa fa-plus"></i> ' . __('Add Player'), ['plugin' => false, 'controller' => 'users', 'action' => 'add', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Add Player'), 'class' => 'btn btn-success']); ?>
    </div>

    <br>
    <!--<div id="grid1" ui-grid="gridOptions" ui-grid-edit ui-grid-pagination ui-grid-resize-columns ui-grid-selection ui-grid-move-columns ui-grid-exporter class="grid"></div>-->


    <!--<div ag-grid="gridOptionsAG" class="ag-theme-dark" style="height: 800px;"></div>-->
    <ag-grid-angular ag-grid="playersGridOptions" class="ag-theme-balham" style="width: 100%; height: 500px"></ag-grid-angular>


    <ag-grid-angular ag-grid="gridOptionsAG" class="ag-theme-balham" style="width: 100%; height: 500px"></ag-grid-angular>
</div>


<script type="text/ng-template" id="custom-date-filter.html">

    <div class="modal-content col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0">

    <div class="modal-header">
    <p class="modal-title well custom-date-filter-header">
    <span class="custom-date-filter-title-text">
    {{ custom.title }}
    </span>
    </p>
    </div>

    <div class="row modal-body custom-date-filter-container-row">

    <form name="custom.customDateFilterForm"
    ng-submit="custom.setFilterDate(custom.filterDate)"
    no-validation>

    <div class="row custom-filter-date-input-row">

    <div class="well col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 custom-date-filter-input">

    <uib-datepicker ng-model="custom.filterDate" 
    min-date="custom.minDate" 
    max-date="custom.maxDate"
    ng-change="custom.filterDateChanged()"
    class="well well-sm">
    </uib-datepicker>

    </div>

    </div>

    <div class="row modal-footer custom-date-filter-submit-buttons-row">

    <div class="custom-date-filter-submit-buttons-div col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">

    <button class="btn btn-success btn-lg custom-date-filter-submit-button"
    type="submit">
    Apply
    </button>

    <button type="button"
    class="btn btn-warning btn-lg custom-date-filter-cancel-button"
    ng-click="custom.cancelDateFilter()">
    Cancel
    </button>

    </div>

    </div>

    </form>

    </div>

    </div>

</script>

<script type="text/ng-template" id="uiSelect">
    <ui-select-wrap>
    <ui-select multiple ng-model="MODEL_COL_FIELD" theme="select2" ng-disabled="disabled" append-to-body="true">
    <ui-select-match placeholder="Choose...">{{ $item }}</ui-select-match>
    <ui-select-choices repeat="item in col.colDef.editDropdownOptionsArray | filter: $select.search">
    <span>{{ item }}</span>
    </ui-select-choices>
    </ui-select>
    </ui-select-wrap>
</script>

<script type="text/ng-template" id="multiCell">
    <div class="ui-grid-cell-contents">
    {{ COL_FIELD.join(', ') }}
    </div>
</script>