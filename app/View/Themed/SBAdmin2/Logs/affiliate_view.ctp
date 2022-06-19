
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Audit Log'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Audit Log'); ?></h1>

            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12 pt-2">
                <p><?= __("Please choose your timeframe, otherwise the report will show data for the current month."); ?></p>							
                <?php
                echo $this->element('reports_form');
                $strreplace_array = array("Array", "(", ")");
                ?>
                <br/>

                <div class="table-responsive-sm">
                    <table class="table table-bordered table-striped">

                        <tbody>
                            <?php if (!empty($data)): ?>
                                <?php foreach ($data as $record) { ?>
                                    <tr>
                                        <td><?php echo $record['Log']['id']; ?></td>
                                        <td><?= $this->Html->link($record['User']['username'], array('controller' => 'users', 'action' => 'view', $record['Log']['user_id'])); ?></td>
                                        <td>
                                            <?php
                                            $messagearray = explode("@", $record['Log']['message']);
                                            echo "<b>" . $messagearray[0] . "</b><br/><hr><br/>";
                                            echo "Request Data:<br/>";
                                            echo str_replace($strreplace_array, " ", print_r(unserialize($messagearray[1]), TRUE));
                                            echo "<br/><br/> Data Submission:<br>";
                                            echo str_replace($strreplace_array, " ", print_r(unserialize($messagearray[2]), TRUE));
                                            ?>
                                        </td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($record['Log']['created'])); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4"><?= __("No data to display."); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </div>
</div>