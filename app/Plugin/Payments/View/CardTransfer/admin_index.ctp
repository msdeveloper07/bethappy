

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Payments'); ?></li>
                    <li class="breadcrumb-item"><?= __('Withdraws'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Card Transfer'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Card Transfer'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="widget">
                    <?php if ($type === 'deposits'):
                        ?>
                        <div class="widget-body">
                            <?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $depositsChartsData)); ?>
                        </div>
                    <?php endif;
                    ?>
                    <?php if ($type === 'withdraws'):
                        ?>
                        <div class="widget-body">
                            <?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $withdrawsChartsData)); ?>
                        </div>
                    <?php endif;
                    ?>
                </div>
                <br/>


                <?php
                echo $this->Form->create(false, array('url' => "/admin/payments/CardTransfer", 'type' => 'file', 'id' => 'search-form', 'action' => 'index'));

                foreach ($search_fields AS $i => &$field) {

                    $kk = str_replace("CardTransfer.", "", $i);
                    $field['value'] = $search_values['CardTransfer'][$kk];

                    if (!is_array($field)) {
                        $search_fields[$i] = array($field);
                    }
//                    var_dump($search_fields[$i]);
                    //$class = isset($field['class']) ? $field['class'] : null;

                    $search_fields[$i]['div'] = array('class' => 'form-group mr-2');
                    $search_fields[$i]['required'] = false;
                }
                ?>
                <div class="form-row align-items-center justify-content-flex-start flex-wrap">
                    <?php echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false,));
                    ?>
                </div>
                <?php
                echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn btn-primary'));
                echo $this->Form->end();
                ?>		

                <br/>

                <div class="table-responsive">

                    <?php if (!empty($data)): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= $this->Paginator->sort('id'); ?></th>
                                    <th><?= $this->Paginator->sort('username', 'Player'); ?></th>
                                    <th><?= $this->Paginator->sort('amount'); ?></th>
                                    <th><?= __('Currency'); ?></th>
                                    <th><?= $this->Paginator->sort('amount_in_usd', 'Amount in USD'); ?></th>
                                    <th><?= __('Afiiliate'); ?> <?= __('ID'); ?></th>
                                    <th><?= __('Remote ID'); ?></th>
                                    <th><?= __('IP Address'); ?></th>
                                    <th><?= __('Date'); ?></th>
                                    <th><?= __('Error message'); ?></th>
                                    <th><?= __('Status'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><a class="paymentinfo" data-id="<?= $row['CardTransfer']['id']; ?>" data-popbox="pop1" data-content="<?= nl2br($row['CardTransfer']['logs']); ?>"><?= $row['CardTransfer']['id']; ?></a></td>
                                        <td><?= $this->Html->link($row['User']['username'], array('plugin' => false, 'controller' => 'Users', 'action' => 'view', $row['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['CardTransfer']['amount'] . $row['User']['Currency']['code']; ?></td>
                                        <td><?= $row['CardTransfer']['currency']; ?></td>
                                        <td><?= $row['CardTransfer']['amount_in_usd']; ?></td>
                                        <td><?= $this->Html->link($row['User']['affiliate_id'], array('plugin' => false, 'controller' => 'Affiliate', 'action' => 'viewbyid', $row['User']['affiliate_id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['CardTransfer']['remote_id']; ?></td>
                                        <td><?= $row['CardTransfer']['ip']; ?></td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($row['CardTransfer']['date'])); ?></td>
                                        <td><?= $row['CardTransfer']['error_mesage']; ?></td>
                                        <td>
                                            <?= $this->element('status_button', array('status' => __(array_search($row['CardTransfer']['status'], CardTransfer::$humanizeStatuses)))); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {

        $('#card-data-table').DataTable({

            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var title = $(this).text();
                    var select = $('<select class="data-table-select2" multiple="multiple"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var vals = $('option:selected', this).map(function (index, element) {
                                    return $.fn.dataTable.util.escapeRegex($(element).val());
                                }).toArray().join('|');
                                column.search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
                                        .draw();
                            });
                    column.data().unique().sort().each(function (d, j) {

                        if (d !== '') {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        } else {
                            select.append('<option value="">Unknown</option>')
                        }
                    });
                });
                $(".data-table-select2").select2({

                });
            },
            dom: "lBfrtip",
            buttons: [{
                    extend: "copy",
                    className: "btn"
                }
                , {
                    extend: "csv",
                    className: "btn"
                }
                , {
                    extend: "excel",
                    className: "btn"
                }
                , {
                    extend: "pdf",
                    className: "btn"
                }
                , {
                    extend: "print",
                    className: "btn",
                    exportOptions: {
                        stripHtml: false,
                        format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length >= 0)
                                    return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                    if (item.nodeName == '#text')
                                        result = result + item.textContent;
                                    else if (item.nodeName == 'SUP')
                                        result = result + item.outerHTML;
                                    else if (item.nodeName == 'STRONG')
                                        result = result + item.outerHTML;
                                    else if (item.nodeName == 'IMG')
                                        result = result + item.outerHTML;
                                    else
                                        result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    }
                }
            ],
            fixedColumns: true,
            columnDefs: [
                {
                    "width": "70px",
                    "targets": [1, 2]
                },

                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {

                    targets: 4, //username is 4th column in the row
                    render: function (data, type, row) {

                        if (type === 'display') {
                            data = '<a href="/admin/Users/view/' + row[3] + '" class="popper" style="color: ' + row[0] + '" data-id="' + row[3] + '" data-popbox="pop1">' + data + '</a>';
                        }
                        return data;
                    }
                },
                {
                    targets: 10, //affiliate_id
                    render: function (data, type, row) {

                        if (type === 'display') {
                            if (data !== 'Not Set') {
                                data = '<a href="/admin/affiliates/viewbyid/' + data + '">' + data + '</a>';
                            }
                        }
                        return data;

                    }
                },
                {

                    targets: 7, //status column
                    render: function (data, type, row) {
                        var status;
                        switch (data) {
                            case "Completed":
                                status = "success";
                                break;
                            case "Pending":
                                status = "warning";
                                break;
                            case "Cancelled":
                            case "Failed":
                                status = "danger";
                                break;
                            default:
                                status = "default";
                        }
                        if (type === 'display') {
                            data = '<span class="btn btn-' + status + '">' + data + '</span>';
                        }
                        return data;
                    }
                }

            ],
            "order": [[1, 'desc'], [5, 'asc']],
            "lengthMenu": [[10, 50, 100, 500], [10, 50, 100, 500]],

        }
        );

    }
    );

</script>