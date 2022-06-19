<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Payment Methods'))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">


                                        <?= __('Please set payment gateways below:'); ?>
                                        <br><br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead
                                                    <tr>
                                                        <th width="300px"><?= __('Name'); ?></th>
                                                        <th><?= __('Provider'); ?></th>
                                                        <th><?= __('Slug'); ?></th>
                                                        <th><?= __('Code'); ?></th>
                                                        <th><?= __('Deposit'); ?></th>
                                                        <th><?= __('Withdraw'); ?></th>
                                                        <th><?= __('Allowed Currencies'); ?></th>
                                                        <th><?= __('Restricted Countries'); ?></th>
                                                        <th><?= __('Restricted Currencies'); ?></th>
                                                        <th width="50px;"><?= __('Order'); ?></th>
                                                        <th><?= __('Active'); ?></th>
                                                        <th><?= __('Notes'); ?></th>
                                                        <th><?= __('Actions'); ?></th>
                                                    </tr>

                                                </thead>                                            
                                                <?php foreach ($methods as $method): ?>
                                                    <tbody>

                                                        <tr data-id="<?= $method['pay_methods']['id']; ?>">
                                                            <td>
                                                                <div style="display: flex; flex-direction: column; align-items: center;">
                                                                    <img src="<?= $method['pay_methods']['image'] ?>"/>
                                                                    <?= $method['pay_methods']['name']; ?> 
                                                                </div>
                                                            </td>
                                                            <!--<td><input type="text" class="value-image" value="<?= $method['pay_methods']['image'] ?>" data-image="<?= $method['pay_methods']['image'] ?>" /></td>-->

                                                            <td><?= $method['pay_providers']['name']; ?></td>
                                                            <td><?= $method['pay_methods']['slug']; ?></td>
                                                            <td><?= $method['pay_methods']['code']; ?></td>
                                                            <td><input class="check-deposit" type="checkbox" <?= ($method['pay_methods']['deposit'] == 1) ? 'checked' : ""; ?> /></td>
                                                            <td><input class="check-withdraw" type="checkbox" <?= ($method['pay_methods']['withdraw'] == 1) ? 'checked' : ""; ?> /></td>
                                                            <td><input class="value-currencies" type="text"  value="<?= $method['pay_methods']['allowed_currencies']; ?>"/></td>
                                                            <td><textarea class="value-restricted-countries"><?= $method['pay_methods']['restricted_countries']; ?></textarea></td>
                                                            <td><textarea class="value-restricted-currencies"><?= $method['pay_methods']['restricted_currencies']; ?></textarea></td>
                                                            <td><input class="value-order" type="text"  value="<?= $method['pay_methods']['order']; ?>" style="max-width: 50px;"/></td>
                                                            <td><input class="check-active" type="checkbox" <?= ($method['pay_methods']['active'] == 1) ? 'checked' : ""; ?> width="50px;"/></td>
                                                            <td><textarea class="value-notes"><?= $method['pay_methods']['notes']; ?></textarea></td>

                                                            <td><a class="save-payment btn btn-success"><?= __('Save'); ?></a></td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br />

                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $(document).on('click', '.save-payment', function (e) {
            var $_this = $(this),
                    _pid = $_this.closest('tr').data('id'),
                    //_img = $_this.closest('tr').find('td .image-value').val(),
                    _deposit = $_this.closest('tr').find('td input.check-deposit:checkbox').is(':checked') ? 1 : 0,
                    _withdraw = $_this.closest('tr').find('td input.check-withdraw:checkbox').is(':checked') ? 1 : 0,
                    _restricted_countries = $_this.closest('tr').find('td .value-restricted-countries').val(),
                    _restricted_currencies = $_this.closest('tr').find('td .value-restricted-currencies').val(),
                    _allowed_currencies = $_this.closest('tr').find('td .value-currencies').val(),
                    _active = $_this.closest('tr').find('td input.check-active:checkbox').is(':checked') ? 1 : 0,
                    _notes = $_this.closest('tr').find('td .value-notes').val(),
                    _order = $_this.closest('tr').find('td .value-order').val();

            $.ajax({
                url: '/payment/editMethod?id=' + _pid + '&deposit=' + _deposit + '&withdraw=' + _withdraw + '&restricted_currencies=' + _restricted_currencies +
                        '&restricted_countries=' + _restricted_countries + '&allowed_currencies=' + _allowed_currencies + '&order=' + _order + '&notes=' + _notes +
                        '&active=' + _active,
                success: function (data) {
                    var result = JSON.parse(data);
                    $_this.html('<i class="icon-check" style="margin-right:5px;"></i>' + result.msg);
                    $_this.removeClass('btn-primary');
                    if (result.status === "success") {
                        $_this.addClass('btn-success');
                    } else {
                        $_this.addClass('btn-danger');
                    }
                    setTimeout(function () {
                        $_this.addClass('btn-primary');
                        $_this.removeClass('btn-success');
                        $_this.removeClass('btn-danger');
                        $_this.html('<i class="icon-white icon-upload" style="margin-right:5px;"></i>Save');
                    }, 3000);
                },
                error: function (data) {
                    console.log(result);
                    var result = JSON.parse(data);
                    $_this.html('<i class="icon-white icon-off" style="margin-right:5px;"></i>' + result.msg);
                    $_this.removeClass('btn-primary');
                    $_this.addClass('btn-danger');
                }
            });
        });
    });
</script>


