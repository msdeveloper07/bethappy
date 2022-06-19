<style type="text/css">
    form#search-form .search-inputs {
        float: left;
        padding-left: 5px;

    }

    form#search-form #search_button {
        margin: 22px 0 0 23px;
        clear: both;
        background: #2fade7;
        color: #FFFFFF;
        left: 185px;
    }
</style>

<div class="space10 visible-phone"></div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid">
            <div class="span12">
                
                    <?php if(!isset($search_fields) || !is_array($search_fields))
                            return;
                            echo $this->Form->create($model, array('id' => 'search-form'));
                            foreach($search_fields AS $i => $field) {
                                if(!is_array($field)) $search_fields[$i] = array($field);
                                
                                $class = isset($field['class']) ? $field['class'] : null;
                                $search_fields[$i]['div'] = array('class' => 'search-inputs '. $class .'' );
                                $search_fields[$i]['required'] = false;
                            }
                        echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
                        echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn'));
                        echo $this->Form->end(); ?>
                
            </div>
        </div>                                
        <div class="tab-content">
            <?php if (!empty($data)): ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php // break url to pass as param
                            $split = explode('/', $this->request->here);
                            $here = implode('_', $split);
                            $here=str_replace(":","#",$here);
                        ?>
                        <div class="box" style="overflow:auto">
                            <div class="box-header well">
                                <h2> 
                                    <i class="icon-list-alt"></i>
                                        <?php if(isset($title)): ?>
                                            <?= __($title); ?>
                                        <?php else: ?>
                                            <?= __('Data list'); ?>
                                        <?php endif; ?>
                                </h2>
                                <div class="box-icon">
                                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                                </div>
                            </div> 
                            <div class="box-content">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <?php $model = array_keys($data[0]);
                                            $model = $model[0];
                                            $titles = $data[0][$model];
                                            foreach ($titles as $title => $value):
                                                if (($title != 'locale')){ ?>
                                                    <th><?= $this->Paginator->sort($title); ?></th>
                                                <?php }
                                            endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($data as $field) {
                                            $class = null;
                                            if ($i++ % 2 == 0) {
                                                $class = ' alt';
                                            }
                                            echo "<tr>";
                                            $k = 0;
                                            foreach ($field[$model] as $key => $var) {
                                                //TODO better locale field handling
                                                if ($key != 'locale') {
                                                    $t = $this->Text->truncate(strip_tags($var), 100, array('ending' => '...', 'exact' => false)); 
                                                    if (isset($mainField) && $k == $mainField) $t = $this->Html->link($t, array('action' => 'view', $field[$model]['id']));
                                                    
                                                    echo "<td class=\"{$class}\">" . $t . "</td>";
                                                }
                                                $k++;
                                            } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->element('paginator'); ?>
            <?php else: ?>
                <p><?= __('No records found'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>