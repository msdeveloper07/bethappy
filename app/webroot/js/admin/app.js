'use strict'
agGrid.initialiseAgGridWithAngular1(angular);
var app = angular.module('CasinoAdminApp', ['ngRoute', 'ngTouch', 'agGrid', 'ui.grid', 'ui.grid.edit', 'ui.grid.pagination', 'ui.grid.resizeColumns', 'ui.grid.selection', 'ui.grid.multiselect.filter', 'ui.grid.moveColumns', 'ui.grid.exporter', 'addressFormatter', 'ui.bootstrap']);
//var ctrls = angular.module('app.controllers', []);
//var drtvs = angular.module('app.directives');
//    app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
//        $routeProvider
////            .when('/dashboard', {templateUrl: '/Dashboard/admin_index', controller: 'DashboardController'})
//            .otherwise({ redirectTo: '/admin' });
//    }]);

angular.module('addressFormatter', []).filter('address', function () {
    return function (input) {
        return input.street + ', ' + input.city + ', ' + input.state + ', ' + input.zip;
    };
});
app.controller('TestController', ['$scope', '$http', '$q', 'uiGridConstants', function ($scope, $http, $q, uiGridConstants) {
//http://ui-grid.info/data/10000_complex.json

        $scope.storeFile = function (gridRow, gridCol, files) {
            // ignore all but the first file, it can only select one anyway
            // set the filename into this column
            gridRow.entity.filename = files[0].name;
            // read the file and set it into a hidden column, which we may do stuff with later
            var setFile = function (fileContent) {
                gridRow.entity.file = fileContent.currentTarget.result;
                // put it on scope so we can display it - you'd probably do something else with it
                $scope.lastFile = fileContent.currentTarget.result;
                $scope.$apply();
            };
            var reader = new FileReader();
            reader.onload = setFile;
            reader.readAsText(files[0]);
        };
        $scope.highlightFilteredHeader = function (row, rowRenderIndex, col, colRenderIndex) {
            if (col.filters[0].term) {
                return 'header-filtered';
            } else {
                return '';
            }
        };
        $scope.gridOptions = {
            enableSorting: true,
            enablePaginationControls: true,
            paginationPageSizes: [50, 100, 250, 500],
            paginationPageSize: 50,
            enableCellEditOnFocus: true,
            enableColumnResizing: true,
            enableFiltering: true,
            enableGridMenu: true,
            fastWatch: true,
            showGridFooter: true, //maybe not needed
            showColumnFooter: true, //maybe not needed
        };
        $scope.gridOptions.columnDefs = [
            {name: 'id', enableCellEdit: false},
            {name: 'name', displayName: 'Name (editable)', width: '20%'},
            {name: 'age', displayName: 'Age', type: 'number', width: '10%'},
//            {name: 'gender', displayName: 'Gender', editableCellTemplate: 'ui-grid/dropdownEditor', width: '20%',
//                cellFilter: 'mapGender', editDropdownValueLabel: 'gender', editDropdownOptionsArray: [
//                    {id: 1, gender: 'male'},
//                    {id: 2, gender: 'female'}
//                ]},
            {field: 'gender', width: '20%',
                filterHeaderTemplate: '<div class="ui-grid-filter-container" ng-repeat="colFilter in col.filters"><div my-custom-dropdown></div></div>',
                filter: {
//                    term: 1,
                    options: [{id: 1, value: 'male'}, {id: 2, value: 'female'}]     // custom attribute that goes with custom directive above 
                },
                cellFilter: 'mapGender'},
            {name: 'registered', displayName: 'Registered', type: 'date', cellFilter: 'date:"yyyy-MM-dd"', width: '20%'},
            {name: 'address', displayName: 'Address', type: 'object', cellFilter: 'address', width: '30%'},
            {name: 'address.city', displayName: 'Address (even rows editable)', width: '40%',
                cellEditableCondition: function (scope) {
                    return scope.rowRenderIndex % 2;
                }
            },
            {name: 'isActive', displayName: 'Active', type: 'boolean', width: '10%'},
//            {name: 'pet', displayName: 'Pet', width: '20%', editableCellTemplate: 'ui-grid/dropdownEditor',
//                editDropdownRowEntityOptionsArrayPath: 'foo.bar[0].options', editDropdownIdLabel: 'value'
//            },
            {field: 'pet', displayName: 'Pet', width: '20%',
                filterHeaderTemplate: '<div class="ui-grid-filter-container" ng-repeat="colFilter in col.filters"><div my-custom-dropdown></div></div>',
                filter: {
//                    type: uiGridConstants.filter.SELECT,
                    options: [{id: '1', value: 'Dog'}, {id: '2', value: 'Cat'}, {id: '3', value: 'Fish'}, {id: '4', value: 'Parrot'}, {id: '5', value: 'None'}]
                }, cellFilter: 'mapPet'},
            {field: 'status', displayName: 'Status', width: '20%',
                filterHeaderTemplate: '<div class="ui-grid-filter-container" ng-repeat="colFilter in col.filters"><div my-custom-dropdown></div></div>',
                filter: {
//                    term: 1,
                    options: [{id: 1, value: 'Bachelor'}, {id: 2, value: 'Nuible'}, {id: 3, value: 'Married'}]     // custom attribute that goes with custom directive above 
                },
                cellFilter: 'mapStatus'
//            
            },
//            {name: 'status', displayName: 'Status', width: '20%', editableCellTemplate: 'ui-grid/dropdownEditor',
//                cellFilter: 'mapStatus',
//                editDropdownOptionsFunction: function (rowEntity, colDef) {
//                    var single;
//                    var married = {id: 3, value: 'Married'};
//                    if (rowEntity.gender === 1) {
//                        single = {id: 1, value: 'Bachelor'};
//                        return [single, married];
//                    } else {
//                        single = {id: 2, value: 'Nubile'};
//                        return $timeout(function () {
//                            return [single, married];
//                        }, 100);
//                    }
//                }
//            },
            {name: 'filename', displayName: 'File', width: '20%', editableCellTemplate: 'ui-grid/fileChooserEditor',
                editFileChooserCallback: $scope.storeFile}
        ];
        var canceler = $q.defer();
        $http.get('http://ui-grid.info/data/500_complex.json', {timeout: canceler.promise})
                .then(function (response) {
                    console.log(response);
                    //$scope.gridOptions.data = response.data;

                    var data = response.data;
                    for (var i = 0; i < data.length; i++) {
                        data[i].registered = new Date(data[i].registered);
                        data[i].gender = data[i].gender === 'male' ? 1 : 2;
//                        if (i % 2) {
//                            data[i].pet = 'fish'
//                            data[i].foo = {bar: [{baz: 2, options: [{value: 'fish'}, {value: 'hamster'}]}]}
//                        } else {
//                            data[i].pet = 'dog'
//                            data[i].foo = {bar: [{baz: 2, options: [{value: 'dog'}, {value: 'cat'}]}]}
//                        }
                    }
                    $scope.gridOptions.data = data;
                });
        $scope.$on('$destroy', function () {
            canceler.resolve(); // Aborts the $http request if it isn't finished.
        });
    }]);
app.controller('HeaderController', ['$rootScope', '$scope', '$http', '$q', 'uiGridConstants', function ($rootScope, $scope, $http, $q, uiGridConstants) {

        $http.get('/casino/admin/alert/alert_informer')
                .then(function (response) {
//                    console.log(response);
                    $rootScope.alerts = response.data.data;
                    $rootScope.alerts_count = response.data.count;
                });
    }]);
app.controller('LanguagesController', ['$scope', '$filter', '$http', '$q', 'uiGridConstants', function ($scope, $filter, $http, $q, uiGridConstants) {
        $scope.highlightFilteredHeader = function (row, rowRenderIndex, col, colRenderIndex) {
            if (col.filters[0].term) {
                return 'header-filtered';
            } else {
                return '';
            }
        };
        $scope.gridOptions = {
            enableSorting: true,
            enablePaginationControls: true,
            paginationPageSizes: [25, 50, 100, 500],
            paginationPageSize: 25,
            enableCellEditOnFocus: true,
            enableFiltering: true,
            enableGridMenu: true,
            fastWatch: true,
        };
        $scope.gridOptions.columnDefs = [
            {name: 'id', enableCellEdit: false},
            {name: 'name', displayName: 'Name'},
            {name: 'ISO6391_code', displayName: 'ISO6391 Code'},
            {name: 'locale_code', displayName: 'Locale Code'},
            {name: 'locale_fallback', displayName: 'Locale Fallback'},
            {name: 'active', displayName: 'Active'},
            {name: 'order', displayName: 'Order'}
        ];
        var canceler = $q.defer();
        $http.get('/casino/languages/getAll', {timeout: canceler.promise})
                .then(function (response) {
                    console.log(response);
                    $scope.gridOptions.data = response.data;
                });
        $scope.$on('$destroy', function () {
            canceler.resolve(); // Aborts the $http request if it isn't finished.
        });
    }]);
app.controller('UsersController', ['$scope', '$filter', '$http', '$q', 'uiGridConstants', '$templateCache', function ($scope, $filter, $http, $q, uiGridConstants, $templateCache) {
        // Set Bootstrap DatePickerPopup config
        $scope.datePicker = {
            options: {
                formatMonth: 'MM',
                startingDay: 1
            },
            format: "yyyy-MM-dd",
        };
        // Set two filters, one for the 'Greater than' filter and other for the 'Less than' filter
        $scope.showDatePopup = [];
        $scope.showDatePopup.push({opened: false});
        $scope.showDatePopup.push({opened: false});
        $templateCache.put('ui-grid/date-cell',
                "<div class='ui-grid-cell-contents'>{{COL_FIELD | date:'yyyy-MM-dd'}}</div>"
                );
        // Custom template using Bootstrap DatePickerPopup
        $templateCache.put('ui-grid/ui-grid-date-filter',
                "<div class=\"ui-grid-filter-container datepicker-filter-container\" ng-repeat=\"colFilter in col.filters\" >" +
                "<div class=\"d-flex\">" +
                "<input type=\"text\" uib-datepicker-popup=\"{{datePicker.format}}\" " +
                "datepicker-options=\"datePicker.options\" " +
                "datepicker-append-to-body=\"true\" show-button-bar=\"true\" " +
                "is-open=\"showDatePopup[$index].opened\" class=\"ui-grid-filter-input ui-grid-filter-input-{{$index}}\" " +
                "ng-model=\"colFilter.term\" ng-attr-placeholder=\"{{colFilter.placeholder || ''}}\" " +
                "aria-label=\"{{colFilter.ariaLabel || aria.defaultFilterLabel}}\" />" +
                "<span class=\"input-group-text\" ng-click=\"showDatePopup[$index].opened = true\">" +
                "<i class=\"icon ion-ios-calendar-outline\"></i></span></div>" +
                "<div role=\"button\" class=\"ui-grid-filter-button\" ng-click=\"removeFilter(colFilter, $index)\" ng-if=\"!colFilter.disableCancelFilterButton\" ng-disabled=\"colFilter.term === undefined || colFilter.term === null || colFilter.term === ''\" ng-show=\"colFilter.term !== undefined && colFilter.term !== null && colFilter.term !== ''\">" +
                "<i class=\"ui-grid-icon-cancel\" ui-grid-one-bind-aria-label=\"aria.removeFilter\">&nbsp;</i></div></div><div ng-if=\"colFilter.type === 'select'\"><select class=\"ui-grid-filter-select ui-grid-filter-input-{{$index}}\" ng-model=\"colFilter.term\" ng-attr-placeholder=\"{{colFilter.placeholder || aria.defaultFilterLabel}}\" aria-label=\"{{colFilter.ariaLabel || ''}}\" ng-options=\"option.value as option.label for option in colFilter.selectOptions\"><option value=\"\"></option></select><div role=\"button\" class=\"ui-grid-filter-button-select\" ng-click=\"removeFilter(colFilter, $index)\" ng-if=\"!colFilter.disableCancelFilterButton\" ng-disabled=\"colFilter.term === undefined || colFilter.term === null || colFilter.term === ''\" ng-show=\"colFilter.term !== undefined && colFilter.term != null\"><i class=\"ui-grid-icon-cancel\" ui-grid-one-bind-aria-label=\"aria.removeFilter\">&nbsp;</i></div></div>"
                );
        $scope.highlightFilteredHeader = function (row, rowRenderIndex, col, colRenderIndex) {
            if (col.filters[0].term) {
                return 'header-filtered';
            } else {
                return '';
            }
        };
        $scope.gridOptions = {
            enableSorting: true,
            enablePaginationControls: true,
            paginationPageSizes: [25, 50, 100, 500],
            paginationPageSize: 25,
            enableCellEditOnFocus: true,
            enableFiltering: true,
            enableGridMenu: true,
            fastWatch: true,
        };
        $scope.gridOptions.columnDefs = [
            {name: 'id', enableCellEdit: false, minWidth: 100},
            {name: 'username', displayName: 'Username', minWidth: 150},
            {name: 'email', displayName: 'E-mail', minWidth: 200},
            {name: 'mobile_number', displayName: 'Mobile Number', minWidth: 200},
            {name: 'real_balance', displayName: 'Balance', minWidth: 100,
                cellTemplate: '<div><span class="small">{{row.entity.currency_name}}</span>{{row.entity.balance}}</div>',
                filters: [
                    {
                        condition: uiGridConstants.filter.GREATER_THAN,
                        placeholder: 'greater than'
                    },
                    {
                        condition: uiGridConstants.filter.LESS_THAN,
                        placeholder: 'less than'
                    }], headerCellClass: $scope.highlightFilteredHeader
            },
            {name: 'first_name', displayName: 'First Name', minWidth: 150},
            {name: 'last_name', displayName: 'Last Name', minWidth: 150},
            {name: 'date_of_birth', displayName: 'Date of Birth', minWidth: 190,
                cellFilter: 'date:\'yyyy-MM-dd\'',
                cellTemplate: 'ui-grid/date-cell',
                filterHeaderTemplate: 'ui-grid/ui-grid-date-filter',
                filters: [
                    {
                        condition: function (term, value, row, column) {
                            if (!term)
                                return true;
                            var valueDate = new Date(value);
                            return valueDate >= term;
                        },
                        placeholder: 'Greater than or equal'
                    },
                    {
                        condition: function (term, value, row, column) {
                            if (!term)
                                return true;
                            var valueDate = new Date(value);
                            return valueDate <= term;
                        },
                        placeholder: 'Less than or equal'
                    }
                ],
                headerCellClass: $scope.highlightFilteredHeader},
            {name: 'gender', displayName: 'Gender', minWidth: 100,
                filter: {
                    type: uiGridConstants.filter.SELECT, // <- move this to here
                    condition: uiGridConstants.filter.EXACT,
                    selectOptions: $scope.genderType
//                            [
//                                {value: 'male', label: 'male'},
//                                {value: 'female', label: 'female'}
//                            ]
                }, cellFilter: 'mapGender',
            },
            {name: 'address1', displayName: 'Address', minWidth: 200},
            {name: 'zip_code', displayName: 'Zip Code', minWidth: 100},
            {name: 'city', displayName: 'City', minWidth: 100,
                filterHeaderTemplate: '<div multi-select-filter on-filter="grid.appScope.filtered($terms)"></div>'
            },
            {name: 'country', displayName: 'Country', minWidth: 100},
            {name: 'account_status', displayName: 'Account Status', minWidth: 150,
                cellTemplate: '<div class="w-100 px-4 py-2 badge" ng-class="{\'badge-success\':row.entity.account_status==\'Active\', \'badge-warning\':row.entity.account_status==\'Unconfirmed\', \'badge-danger\':row.entity.account_status==\'Locked Out\' || row.entity.account_status==\'Banned\' || row.entity.account_status==\'Self Excluded\' || row.entity.account_status==\'Self Deleted\'}">{{row.entity.account_status}}</div>'

            },
            {name: 'KYC_status', displayName: 'KYC Status', minWidth: 150,
                cellTemplate: '<div class="w-100 px-4 py-2 badge" ng-class="{\'badge-warning\':row.entity.KYC_status==\'Pending\', \'badge-danger\':row.entity.KYC_status==\'Rejected\', \'badge-success\':row.entity.KYC_status==\'Approved\'}">{{row.entity.KYC_status}}</div>'
            },
            {name: 'login_status', displayName: 'Login Status', minWidth: 150,
                cellTemplate: '<div class="w-100 px-4 py-2 badge" ng-class="{\'badge-success\':row.entity.login_status==\'Logged In\', \'badge-danger\':row.entity.login_status==\'Logged Out\'}">{{row.entity.login_status}}</div>'
            },
            {name: 'affiliate_name', displayName: 'Affiliate', minWidth: 150,
                cellTemplate: '<a href="casino/admin/affiliates/viewbyid/{{row.entity.affiliate_id}}">{{row.entity.affiliate_name}}</a>'
            },
            {name: 'registration_date', displayName: 'Registration Date', minWidth: 150,
                cellFilter: 'date:\'yyyy-MM-dd\'',
                cellTemplate: 'ui-grid/date-cell',
                filterHeaderTemplate: 'ui-grid/ui-grid-date-filter',
                filters: [
                    {
                        condition: function (term, value, row, column) {
                            if (!term)
                                return true;
                            var valueDate = new Date(value);
                            return valueDate >= term;
                        },
                        placeholder: 'Greater than or equal'
                    },
                    {
                        condition: function (term, value, row, column) {
                            if (!term)
                                return true;
                            var valueDate = new Date(value);
                            return valueDate <= term;
                        },
                        placeholder: 'Less than or equal'
                    }
                ],
                headerCellClass: $scope.highlightFilteredHeader
            },
            {name: 'ip', displayName: 'Registration IP', minWidth: 150},
            {name: 'last_visit_ip', displayName: 'Last Visit IP', minWidth: 150},
        ];
        var canceler = $q.defer();
        $scope.genderType = [];
        $http.get('/casino/users/getAll', {timeout: canceler.promise})
                .then(function (response) {
                    $scope.gridOptions.data = response.data;
//                    $scope.gridOptionsAG.rowData = response.data;

//                    $scope.genderType = [{value: '1', label: 'male'}, {value: '2', label: 'female'}];

                    response.data.forEach(function addValue(row, key) {
                        if ($scope.genderType.some(function (el) {
                            return el.value === row.gender
                        }) == false)
                            $scope.genderType.push({value: row.gender, label: row.gender});
                    });
//                    console.log($scope.genderType);
                });
        $scope.$on('$destroy', function () {
            canceler.resolve(); // Aborts the $http request if it isn't finished.
        });
        var COUNTRY_CODES = {
            Australia: "au",
            Russia: "ru",
            Ireland: "ie",
            Luxembourg: "lu",
            Belgium: "be",
            Spain: "es",
            "United Kingdom": "gb",
            France: "fr",
            Germany: "de",
            Sweden: "se",
            Italy: "it",
            Greece: "gr",
            Iceland: "is",
            Portugal: "pt",
            Malta: "mt",
            Norway: "no",
            Brazil: "br",
            Argentina: "ar",
            Colombia: "co",
            Peru: "pe",
            Venezuela: "ve",
            "United States": "us",
            Uruguay: "uy"
        };
        $scope.gridOptionsAG = {
            debug: true,
            pagination: true,
            floatingFilter: true,
            rowSelection: 'multiple',
            rowMultiSelectWithClick: true,
            rowGroupPanelShow: 'always',
            pivotPanelShow: 'always',
            paginationAutoPageSize: true,
//            rowDragManaged: true,
            animateRows: true,
//            rowModelType: "infinite",
//            cacheOverflowSize: 2,
//            maxConcurrentDatasourceRequests: 2,
//            infiniteInitialRowCount: 1,
//            maxBlocksInCache: 2,

            components: {
                countryCellRenderer: countryCellRenderer,
                countryFloatingFilterComponent: CountryFloatingFilterComponent, },
            defaultColDef: {
                sortable: true,
                editable: true,
                filter: true,
                resizable: true,
                enableRowGroup: true,
                enablePivot: true,
                enableValue: true,
            },
            columnDefs: [
                {headerName: 'Participant',
                    children: [
                        {
                            headerName: "Athlete",
                            field: "athlete",
//                    sort: "asc",
                            rowDrag: true,
                            headerCheckboxSelection: true,
                            headerCheckboxSelectionFilteredOnly: true,
                            checkboxSelection: true
                        },
                        {
                            headerName: "Age",
                            field: "age",
                            type: "numberColumn",
                            valueParser: numberParser,
                            cellClassRules: {
                                "rag-red": "x < 20",
                                "rag-amber": "x >= 20 && x < 25",
                                "rag-green": "x >= 25"
                            }
                        }]},
                {
                    headerName: "Sport",
                    field: "sport",
                    cellClass: function (params) {//check
                        return params.value === "Swimming" ? "bg-info" : "bg-warning";
                    }
                },
                {
                    headerName: "Country",
                    field: "country",
                    filter: "agSetColumnFilter",
                    cellRenderer: 'countryCellRenderer',
                    floatingFilterComponent: 'countryFloatingFilterComponent',
                    cellEditor: 'agSelectCellEditor',
                    cellEditorParams: {
                        values: ["Argentina", "Brazil", "Colombia", "France", "Germany", "Greece", "Iceland", "Ireland",
                            "Italy", "Malta", "Portugal", "Norway", "Peru", "Spain", "Sweden", "United Kingdom",
                            "Uruguay", "Venezuela", "Belgium", "Luxembourg"]
                    },
                },
                {
                    headerName: "Year",
                    field: "year",
                    type: "numberColumn",
                    cellRenderer: function (params) {
                        return '<span class="badge badge-secondary">' + params.value + "</span>";
                    }
                },
                {
                    headerName: "Date",
                    field: "date",
                    type: ["dateColumn", "nonEditableColumn"],
                },
                {
                    headerName: "Gold",
                    field: "gold",
                    type: "numberColumn",
                },
                {
                    headerName: "Silver",
                    field: "silver",
                    type: "numberColumn",
                },
                {
                    headerName: "Bronze",
                    field: "bronze",
                    type: "numberColumn",
                },
                {
                    headerName: "Total",
                    field: "total",
                    cellRenderer: function (params) {
                        console.log(params.value);
                        var result = '';
                        for (var i = 0; i < params.value; i++) {
                            console.log(i);
                            if (params.value > i) {
                                result += '<img src="https://www.ag-grid.com/images/star.svg" class="star" width=12 height=12 />';
                            }
                        }
                        if (false && params.value === 0) {
                            result += '(no stars)';
                        }
                        result += '';
                        return result;
                    }
                }
            ],
            columnTypes: {
                numberColumn: {
                    filter: "agNumberColumnFilter"
                },
                medalColumn: {
                    columnGroupShow: "open",
                    filter: false
                },
                nonEditableColumn: {editable: false},
                dateColumn: {
                    filter: "agDateColumnFilter",
                    filterParams: {
                        comparator: function (filterLocalDateAtMidnight, cellValue) {
                            var dateParts = cellValue.split("/");
                            var day = Number(dateParts[0]);
                            var month = Number(dateParts[1]) - 1;
                            var year = Number(dateParts[2]);
                            var cellDate = new Date(year, month, day);
                            if (cellDate < filterLocalDateAtMidnight) {
                                return -1;
                            } else if (cellDate > filterLocalDateAtMidnight) {
                                return 1;
                            } else {
                                return 0;
                            }
                        }
                    }
                }
            }
        };
        $http.get('https://raw.githubusercontent.com/ag-grid/ag-grid/master/packages/ag-grid-docs/src/olympicWinnersSmall.json', {timeout: canceler.promise})
                .then(function (response) {
                    console.log(response);
                    $scope.gridOptionsAG.api.setRowData(response.data);
                });
        console.log($scope.gridOptionsAG);
        $scope.playersGridOptions = {
            debug: true,
            pagination: true,
            floatingFilter: true,
            rowSelection: 'multiple',
            rowMultiSelectWithClick: true,
            rowGroupPanelShow: 'always',
            pivotPanelShow: 'always',
            paginationAutoPageSize: true,
            animateRows: true,
            cacheOverflowSize: 2,
            maxConcurrentDatasourceRequests: 2,
            infiniteInitialRowCount: 1,
            maxBlocksInCache: 2,
            groupHeaders: true,
            components: {
                countryCellRenderer: countryCellRenderer,
                countryFloatingFilterComponent: CountryFloatingFilterComponent, },
            defaultColDef: {
                sortable: true,
                editable: true,
                filter: true,
                resizable: true,
                enableRowGroup: true,
                enablePivot: true,
                enableValue: true,
            },
            columnDefs: [
                {
                    headerName: "",
                    width: 80,
                    rowDrag: true,
                    headerCheckboxSelection: true,
                    checkboxSelection: true,
                    suppressSorting: true,
                    suppressFilter: true,
                    suppressMenu: true,
                },
                {headerName: 'Account Info',
                    children: [
                        {
                            headerName: "ID",
                            field: "id",
                            filter: "agNumberColumnFilter",
                            sort: "asc",
                        },
                        {
                            headerName: "Username",
                            field: "username",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "E-mail",
                            field: "email",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Mobile number",
                            field: "mobile_number",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Balance",
                            field: "real_balance",
                            filter: "agNumberColumnFilter",
                            cellRenderer: balanceCellRenderer
                        },
                        {
                            headerName: "Status",
                            field: "account_status",
                            filter: "agTextColumnFilter",
                            cellRenderer: statusCellRenderer
                        },
                        {
                            headerName: "KYC status",
                            field: "KYC_status",
                            filter: "agTextColumnFilter",
                            cellRenderer: statusCellRenderer
                        },
                        {
                            headerName: "Login status",
                            field: "login_status",
                            filter: "agTextColumnFilter",
                            cellRenderer: statusCellRenderer
                        },
                    ]
                },
                {headerName: 'Personal Info',
                    children: [
                        {
                            headerName: "First name",
                            field: "first_name",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Last name",
                            field: "last_name",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Gender",
                            field: "gender",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Date of birth",
                            field: "date_of_birth",
                            filter: "agDateColumnFilter"
                        },
                    ]
                },
                {headerName: 'Address Info',
                    children: [
                        {
                            headerName: "Address",
                            field: "address_1",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Zip code",
                            field: "zip_code",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "City",
                            field: "city",
                            filter: "agTextColumnFilter",
                        },
                        {
                            headerName: "Country",
                            field: "country",
                            filter: "agTextColumnFilter",
                            cellRenderer: 'countryCellRenderer',
                        },
                    ]
                },
                {headerName: 'Misc',
                    children: [
//                        {
//                            headerName: "Group",
//                            field: "group_name",
//                            filter: "agTextColumnFilter"
//                        },
                        {
                            headerName: "Category",
                            field: "category_name",
                            filter: "agTextColumnFilter",
                            cellRenderer: categoryCellRenderer
                        },
                        {
                            headerName: "Affiliate",
                            field: "affiliate_name",
                            filter: "agTextColumnFilter",
                            cellRenderer: affiliateCellRenderer
                        },
                        {
                            headerName: "Registration date",
                            field: "registration_date",
                            filter: "agDateColumnFilter"
                        },
                        {
                            headerName: "Registration IP",
                            field: "ip",
                            filter: "agTextColumnFilter"
                        },
                        {
                            headerName: "Last visit IP",
                            field: "last_visit_ip",
                            filter: "agTextColumnFilter"
                        },
                    ]
                },
            ],
            columnTypes: {
                numberColumn: {
                    filter: "agNumberColumnFilter"
                },
                medalColumn: {
                    columnGroupShow: "open",
                    filter: false
                },
                nonEditableColumn: {editable: false},
                dateColumn: {
                    filter: "agDateColumnFilter",
                    filterParams: {
                        comparator: function (filterLocalDateAtMidnight, cellValue) {
                            var dateParts = cellValue.split("/");
                            var day = Number(dateParts[0]);
                            var month = Number(dateParts[1]) - 1;
                            var year = Number(dateParts[2]);
                            var cellDate = new Date(year, month, day);
                            if (cellDate < filterLocalDateAtMidnight) {
                                return -1;
                            } else if (cellDate > filterLocalDateAtMidnight) {
                                return 1;
                            } else {
                                return 0;
                            }
                        }
                    }
                }
            }
        };
        $http.get('/casino/users/getAll', {timeout: canceler.promise})
                .then(function (response) {
                    console.log(response);
                    $scope.playersGridOptions.api.setRowData(response.data);
                });
        function numberToColor(val) {
            if (val === 0) {
                return "#ffaaaa";
            } else if (val == 1) {
                return "#aaaaff";
            } else {
                return "#aaffaa";
            }
        }
        function numberParser(params) {
            var newValue = params.newValue;
            var valueAsNumber;
            if (newValue === null || newValue === undefined || newValue === "") {
                valueAsNumber = null;
            } else {
                valueAsNumber = parseFloat(params.newValue);
            }
            return valueAsNumber;
        }


        //mine
        function  categoryCellRenderer(params) {
            if (params.value === "" || params.value === undefined || params.value === null) {
                return '';
            } else {
                return '<span class="badge px-3 py-1" style="background-color:' + params.data.category_color + '">' + params.value + "</span>";
            }
        }
        //mine
        function  affiliateCellRenderer(params) {
            if (params.value === "" || params.value === undefined || params.value === null) {
                return '';
            } else {
                return  '<a href="/casino/admin/affiliates/viewbyid/' + params.data.affiliate_id + '">' + params.value + '</a>';
            }
        }
        //mine
        function  statusCellRenderer(params) {
            var statusRender = '';
            switch (params.value) {
                case 'Active'://account status
                case 'Logged In'://login status
                case 'Approved'://KYC status
                    statusRender = '<span class="badge badge-success px-3 py-1">' + params.value + "</span>";
                    break;
                case 'Unconfirmed'://account status
                case 'Pending':
                    statusRender = '<span class="badge badge-warning px-3 py-1">' + params.value + "</span>";
                    break;
                case 'Locked Out'://account status
                case 'Self Excluded'://account status
                case 'Self Deleted'://account status
                case 'Banned'://account status
                case 'Logged Out'://login status
                    statusRender = '<span class="badge badge-danger px-3 py-1">' + params.value + "</span>";
                    break;
            }
            return statusRender;
        }
        //mine
        function balanceCellRenderer(params) {
            if (params.value === "" || params.value === undefined || params.value === null || params.value === 0) {
                return  '<small>' + params.data.currency_code + '</small>' + 0.00;
            } else {
                return  '<small>' + params.data.currency_code + '</small>' + params.value;
            }
        }
        //mine
        function countryCellRenderer(params) {
            //get flags from here: http://www.freeflagicons.com/
            if (params.value === "" || params.value === undefined || params.value === null) {
                return '';
            } else {
                var flag = '<img class="flag" border="0" width="15" height="10" src="https://flags.fmcdn.net/data/flags/mini/' + $filter('lowercase')(params.value) + '.png">';
                return flag + ' ' + params.value;
            }
        }




        function CountryFloatingFilterComponent() {
        }

        CountryFloatingFilterComponent.prototype.init = function (params) {
            this.params = params;
            this.eGui = document.createElement('div');
            // this.eGui.style.borderBottom = '1px solid lightgrey';
        };
        CountryFloatingFilterComponent.prototype.getGui = function () {
            return this.eGui;
        };
        CountryFloatingFilterComponent.prototype.onParentModelChanged = function (dataModel) {
            // add in child, one for each flat
            if (dataModel) {

                var model = dataModel.values;
                var flagsHtml = [];
                var printDotDotDot = false;
                if (model.length > 4) {
                    var toPrint = model.slice(0, 4);
                    printDotDotDot = true;
                } else {
                    var toPrint = model;
                }
                toPrint.forEach(function (country) {
                    flagsHtml.push('<img class="flag" style="border: 0px; width: 15px; height: 10px; margin-left: 2px" ' +
                            'src="https://flags.fmcdn.net/data/flags/mini/'
                            + COUNTRY_CODES[country] + '.png">');
                });
                this.eGui.innerHTML = '(' + model.length + ') ' + flagsHtml.join('');
                if (printDotDotDot) {
                    this.eGui.innerHTML = this.eGui.innerHTML + '...';
                }
            } else {
                this.eGui.innerHTML = '';
            }
        };
    }]);
app.directive('uib-multiselect', function () {
    return {
        template: '<select class="form-control" ng-model="colFilter.term" ng-options="option.id as option.value for option in colFilter.options"></select>'
    };
});
app.filter('mapGender', function () {
    var genderHash = {
        male: 'male',
        female: 'female'
    };
    return function (input) {
        if (!input) {
            return '';
        } else {
            return genderHash[input];
        }
    };
});
app.filter('mapPet', function () {
    var petHash = {
        1: 'Dog',
        2: 'Cat',
        3: 'Fish',
        4: 'Parrot',
        5: 'None'
    };
    return function (input) {
        if (!input) {
            return '';
        } else {
            return petHash[input];
        }
    };
});
app.filter('mapStatus', function () {
    var statusHash = {
        1: 'Bachelor',
        2: 'Nubile',
        3: 'Married'
    };
    return function (input) {
        if (!input) {
            return '';
        } else {
            return statusHash[input];
        }
    };
});
app.directive('myCustomDropdown', function () {
    return {
        template: '<select class="form-control" ng-model="colFilter.term" ng-options="option.id as option.value for option in colFilter.options"></select>'
    };
});