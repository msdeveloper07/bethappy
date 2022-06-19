<style>
    .select2-container{
        width:100%!important;
    }
    .badge-custom{
        padding: 0.5rem 0.75rem;
        border-radius: 2rem;
        font-weight: normal;
        font-size: 1em;
        width:50px;
        display: flex;
        justify-content: center;
        align-items:center;
        text-shadow:none;
    }
</style>


<div class="small-table popbox" id="pop1" data-popbox="pop1"></div>
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
                                        <br><br>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped compact stripe" id="payments">
                                                <thead>
                                                    <tr>
                                                        <th><?= __('ID'); ?></th>
                                                        <th><?= __('User ID'); ?></th>
                                                        <th><?= __('Type'); ?></th>
                                                        <th><?= __('Provider'); ?></th>
                                                        <th><?= __('Method'); ?></th>
                                                        <th><?= __('Target'); ?></th>
                                                        <th><?= __('Parent ID'); ?></th>
                                                        <th><?= __('Amount'); ?></th>
                                                        <th><?= __('Currency'); ?></th>
                                                        <th><?= __('Status'); ?></th>
                                                        <th><?= __('Date'); ?></th>
                                                    </tr>
                                                </thead>  
                                                <tfoot style="display:table-header-group">
                                                    <tr>
                                                        <th><?= __('ID'); ?></th>
                                                        <th><?= __('User ID'); ?></th>
                                                        <th><?= __('Type'); ?></th>
                                                        <th><?= __('Provider'); ?></th>
                                                        <th><?= __('Method'); ?></th>
                                                        <th><?= __('Target'); ?></th>
                                                        <th><?= __('Parent ID'); ?></th>
                                                        <th><?= __('Amount'); ?></th>
                                                        <th><?= __('Currency'); ?></th>
                                                        <th><?= __('Status'); ?></th>
                                                        <th><?= __('Date'); ?></th>

                                                    </tr>

                                                </tfoot> 
                                                <tbody>

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


<?php // foreach ($data as $payment): ?>


<!--                                                        
<tr data-id="<?= $payment['Payment']['id']; ?>">
<td><?= $payment['Payment']['id']; ?></td>
<td><?= $payment['Payment']['type']; ?></td>
<td><?= $payment['Payment']['provider']; ?></td>
<td><?= $payment['Payment']['method']; ?></td>
<td>
<?php
//$target = json_decode($payment['Payment']['transaction_target']);
//if ($target !== NULL):
?>
<?= __('Bank Client') . ': ' . $target->btFirstName . ' ' . $target->btLastName; ?><br>
<?= __('Bank Name') . ': ' . $target->btBankName; ?><br>
<?= __('Bank BIC/SWIFT code') . ': ' . $target->btBICSFIFT; ?><br>
<?= __('Bank IBAN') . ': ' . $target->btIBAN; ?>
<?php // else: ?>
<?= $payment['Payment']['transaction_target']; ?>
<?php // endif; ?>

</td>
<td><?= $payment['Payment']['parent_id']; ?></td>
<td><?= $payment['Payment']['amount']; ?></td>
<td><?= $payment['Payment']['currency']; ?></td>
<td><?= $payment['Payment']['status']; ?></td>
<td><?= $payment['Payment']['created']; ?></td>
</tr>-->
<?php //endforeach; ?>

<script>
    $(document).ready(function () {
        $('#payments').dataTable({
            "searching": true,
            "processing": true,
            "serverSide": true,
            "order": [[0, 'desc']],
            columnDefs: [//for back
                {
                    targets: 0, //id
                    "name": "id",
                    className: 'dt-center',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            data = row.id;
                        }
                        if (type === 'filter') {
                            data = row.id;
                        }
                        return data;
                    }
                },
                {
                    targets: 1,
                    "name": "user_id",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = '<a href="/admin/Users/view/' + row.user_id + '" class="popper" style="color: #' + row.user_id + '" data-id="' + row.user_id + '" data-popbox="pop1">' + row.user_id + '</a>';
                        }
                        if (type === 'filter') {
                            data = row.user_id;
                        }
                        return data;
                    }
                },
                {
                    targets: 2,
                    "name": "type",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.type;
                        }
                        if (type === 'filter') {
                            data = row.type;
                        }
                        return data;
                    }
                },
                {
                    targets: 3,
                    "name": "provider",
                    className: 'dt-center',
                    "data": null,
                    render: function (data, type, row) {//data and row are the same

                        if (type === 'display') {
                            data = row.provider;
                        }
                        if (type === 'filter') {
                            data = row.provider;
                        }
                        return data;
                    }
                },
                {
                    targets: 4,
                    "name": "method",
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.method;
                        }
                        if (type === 'filter') {
                            data = row.method;
                        }
                        return data;
                    }
                },
                {
                    targets: 5,
                    "name": "target",
                    className: 'dt-center',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            data = '';
                            var dataArray = row.transaction_target.split(';');
                            for (var i = 0; i < dataArray.length; i++) {
                                console.log(dataArray[i]);
                                data += dataArray[i] + "<br/>";
                            }

                        }
                        if (type === 'filter') {
                            data = row.transaction_target;
                        }
                        return data;
                    }
                },
                {
                    targets: 6,
                    "name": "parent_id",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.parent_id;
                        }
                        if (type === 'filter') {
                            data = row.parent_id;
                        }
                        return data;
                    }
                },
                {
                    targets: 7,
                    "name": "amount",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.amount;
                        }
                        if (type === 'filter') {
                            data = row.amount;
                        }
                        return data;
                    }
                },
                {
                    targets: 8,
                    "name": "currency",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.currency;
                        }
                        if (type === 'filter') {
                            data = row.currency;
                        }
                        return data;
                    }
                },
                {
                    targets: 9, //status column
                    "name": "status",
                    className: 'dt-center',
                    render: function (data, type, row) { //data is undefined
                        var status;
                        switch (row.status) {
                            case "Completed":
                                status = "success";
                                break;
                            case "Pending":
                                status = "warning";
                                break;
                            case "Cancelled":
                            case "Failed":
                            case "Declined":
                                status = "important";
                                break;
                            default:
                                status = "";
                        }
                        if (type === 'display') {
                            data = '<span class="badge badge-custom badge-' + status + '">' + row.status + '</span>';
                        }
                        if (type === 'filter') {
                            data = row.status;
                        }
                        return data;
                    }
                },
                {
                    targets: 10,
                    "name": "date",
                    className: 'dt-center',
                    render: function (data, type, row) {//data is undefined
                        if (type === 'display') {
                            data = row.created;
                        }
                        if (type === 'filter') {
                            data = row.created;
                        }
                        return data;
                    }
                }
            ],
            ajax: {
                url: 'http://82.214.112.218/payment/get_payments',
                type: 'POST',
                error: function (data) {
                    console.log(data);
                }

            },

//            initComplete: function () {
//                this.api().columns().every(function (index) {
//                    const column = this.column(index);
//                    const title = $(column.header()).text();
//                    console.log(title);
////                    if (index === 2) {
//                    var select = $('<select class="form-control data-table-select2"></select>')
//                            .appendTo($(column.footer()).empty())
//                            .on('change', function () {
//                                  var vals = $('option:selected', this).map(function (index, element) {
//                                    console.log(element.val);
//                                    return $.fn.dataTable.util.escapeRegex($(element).val());
//                                }).toArray().join('|');
//                                column.search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false).draw();
//                            });
//
//                    column.data().unique().sort().each(function (d, j) {
//                        select.append('<option value="' + d + '">' + d + '</option>');
//                    });
////                    } else {
////                        var input = $('<input class="form-control" type="text" placeholder="Search ${title}" />')
////                                .appendTo($(column.footer()).empty())
////                                .on('keyup change', function () {
////                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
////                                    column
////                                            .search(val)
////                                            .draw();
////                                });
////                    }
//                    $(".data-table-select2").select2({});
//                });
//            }


            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this.column(index);
                    var title = $(column.header()).text();
                    console.log(column.data());
                    var select = $('<select class="data-table-select2" multiple="multiple"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $('option:selected', this).map(function (index, element) {
                                    console.log($(element).val());
                                    return $.fn.dataTable.util.escapeRegex($(element).val());
                                }).toArray().join('|');
                                column.search(val.length > 0 ? '^(' + val + ')$' : '', true, false).draw();
                            });

                    column.data().unique().sort().each(function (d, j) {
                        console.log(d);
                        console.log(j);
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                $(".data-table-select2").select2({});
            }
        });

    });

</script>


