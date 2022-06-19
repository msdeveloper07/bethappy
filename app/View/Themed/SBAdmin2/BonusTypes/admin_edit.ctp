
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Bonuses'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Bonus Types'), ['plugin' => false, 'controller' => 'BonusTypes', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Bonus Types')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Edit Bonus Type'); ?></h1>
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
<input type="hidden" id="trigger-fields" value='<?= $trigger_fields; ?>'/>

<script type="text/javascript">
    $(document).ready(function () {
        var fields = JSON.parse($('#trigger-fields').val()),
                aclCounter = $("#acls .row-fluid").length - 1,
                gameCounter = $("#games .row-fluid").length - 1,
                conditionCounter = $("#conditions .row-fluid").length - 1;

        $(document.body).on("click", "#icon-remove", function (e) {
            $(this).parent('.row-fluid').remove();
        });

        $(document.body).on("click", "#add-acl", function (e) {
            e.preventDefault();

            var count = ++aclCounter;

            $("#acls").append(
                    '<div class="row-fluid">' +
                    '<div class="input select"><label for="BonusAcl' + count + 'Reverse">Reverse</label><select class="form-control" name="data[BonusAcl][' + count + '][reverse]" id="BonusAcl' + count + 'Reverse"><option value="0">In</option><option value="1">Not in</option></select></div>' +
                    '<div class="input select"><label for="BonusAcl' + count + 'Target">Target</label><select class="form-control"  name="data[BonusAcl][' + count + '][target]" id="BonusAcl' + count + 'Target"><option value="0">All</option><option value="1">Country</option><option value="2">User</option><option value="3">Affiliate</option><option value="4">Landing Page</option></select></div>' +
                    '<div class="input text"><label for="BonusAcl' + count + 'TargetValue">Target ID</label><input name="data[BonusAcl][' + count + '][target_value]" id="BonusAcl' + count + 'TargetValue" type="text" class="form-control" ></div>' +
                    '<div class="control-group"><div class="controls"><label for="BonusAcl' + count + 'StartDate">Start Date</label><input name="data[BonusAcl][' + count + '][start_date]" class="form-control m-ctrl-medium datepicker" data-date-format="yyyy-mm-dd" id="BonusAcl' + count + 'StartDate" type="text"></div></div>' +
                    '<div class="control-group"><div class="controls"><label for="BonusAcl' + count + 'EndDate">End Date</label><input name="data[BonusAcl][' + count + '][end_date]" class="form-control m-ctrl-medium datepicker" data-date-format="yyyy-mm-dd" id="BonusAcl' + count + 'EndDate" type="text"></div></div>' +
                    '<button class="btn btn-danger mt-4" id="icon-remove"><i class="fas fa-times"></i></button>' +
                    '</div>'


                    );
            $('.datepicker').datepicker();
        });


        $(document.body).on("click", "#add-condition", function (e) {
            e.preventDefault();

            var count = ++conditionCounter;

            $("#conditions").append(
                    '<div class="row-fluid">' +
                    '<input name="data[BonusCondition][' + count + '][order]" value="' + count + '" id="BonusCondition' + count + 'Order" type="hidden">' +
                    '<div class="input text"><label for="BonusCondition' + count + 'Field">Field</label><input class="condition-field form-control" name="data[BonusCondition][' + count + '][field]" id="BonusCondition' + count + 'Field" type="text"></div>' +
                    '<div class="input select"><label for="BonusCondition' + count + 'Operator">Operator</label><select class="form-control" name="data[BonusCondition][' + count + '][operator]" id="BonusCondition' + count + 'Operator"><option value="==">Equals to</option><option value="!=">Not Equal to</option><option value=">">Greater than</option><option value="<">Less than</option><option value=">=">Greater than or equal to</option><option value="<=">Less than or equal to</option></select></div>' +
                    '<div class="input text"><label for="BonusCondition' + count + 'Value">Value</label><input name="data[BonusCondition][' + count + '][value]" id="BonusCondition' + count + 'Value" type="text" class="form-control"></div>' +
                    '<div class="input select"><label for="BonusCondition' + count + 'Condition">Condition</label><select class="form-control" name="data[BonusCondition][' + count + '][condition]" id="BonusCondition' + count + 'Condition"><option value="">Select condition</option><option value="||">Or</option><option value="&amp;&amp;">And</option></select></div>' +
                    '<button class="btn btn-danger mt-4" id="icon-remove"><i class="fas fa-times"></i></button>' +
                    '</div>'
                    );

            changeTriggerFields($("#BonusTypeTrigger").val());
        });



        $(document.body).on("click", "#add-game", function (e) {
            e.preventDefault();

            var count = ++gameCounter;

            $("#games").append(
                    '<div class="row-fluid">' +
                    '<div class="input select"><label for="BonusGames' + count + 'BonusGamesGame">Game</label><select class="form-control" name="data[BonusGames][' + count + '][BonusGames][game]" id="BonusGames' + count + 'BonusGamesGame"><option value="0">All</option><option value="1">Sportsbook</option><option value="2">Live Casino</option><option value="3">RGS</option></select></div>' +
                    '<div class="input number"><label for="BonusGames' + count + 'BonusGamesPayoffPercentage">Payoff Percentage in %</label><input name="data[BonusGames][' + count + '][BonusGames][payoff_percentage]" min="0" step="any" id="BonusGames' + count + 'BonusGamesPayoffPercentage" type="number" class="form-control"></div>' +
                    '<button class="btn btn-danger mt-4" id="icon-remove"><i class="fas fa-times"></i></button>' +
                    '</div>'
                    );
        });

        $(document.body).on("change", "#BonusTypeTrigger", function (e) {
            changeTriggerFields($(this).val());
        });

        function changeTriggerFields(type) {
            $('.condition-field').each(function (i, el) {
                var id = $(el).attr("id"),
                        name = $(el).attr("name"),
                        _type = $(el).attr("_type"),
                        selected = $(el).val(),
                        $select = $('<select class="condition-field form-control" _type="' + type + '" name="' + name + '" id="' + id + '"></select>');

                if (_type === type)
                    return;

                for (var key in fields[type]) {
                    $select.append("<option " + (selected === fields[type][key] ? "selected=selected" : "") + " value=" + fields[type][key] + ">" + fields[type][key] + "</option>");
                }

                if ($(el).hasClass("text")) {
                    $(el).parent().removeClass("text");
                    $(el).parent().addClass("select");
                }

                $(el).parent().append($select);
                $(el).remove();
            });
        }

        changeTriggerFields($("#BonusTypeTrigger").val());
    });
</script>


