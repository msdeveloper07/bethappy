<?php
if (!empty($data)):
    //var_dump($model);
    //var_dump($client_folder);
    ?>

    <!-- BEGIN TABLE widget-->
    <?php
    // break url to pass as param
    $split = explode('/', $this->request->here);
    $here = implode('_', $split);
    $here = str_replace(":", "#", $here);
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <?php
                    $model = array_keys($data[0]);
                    $model = $model[0];
                    $titles = $data[0][$model];
                    foreach ($titles as $title => $value):

                        if ($title == 'mt_id')
                            $title = 'Parent Menu';

                        if (($title != 'locale')):
                            ?>
                            <th><?= $this->Paginator->sort(__($title)); ?></th>
                            <?php
                        endif;
                    endforeach;
                    ?>

                    <?php if (isset($actions) AND is_array($actions) AND ! empty($actions)): ?>
                        <th><?= __('Actions'); ?></th>
                    <?php endif; ?>

                    <?php //if (isset($translate) AND $translate == true):   ?>
                                                                                                                                                                    <!--<th><?//= __('Translations'); ?></th>-->
                    <?php //endif;   ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $field):
                    //var_dump($field);
                    $class = null;
                    if ($i++ % 2 == 0)
                        $class = ' alt';
                    ?>
                    <tr>
                        <?php
                        $k = 0;

                        foreach ($field[$model] as $key => $var) {
                            //var_dump($key);
                            //TODO better locale field handling
                            if ($key != 'locale' && !empty($key)) {
                                $t = $this->Text->truncate(strip_tags($var), 100, array('ending' => '...', 'exact' => false));

                                if (isset($mainField) AND $k == $mainField)
                                    $t = $this->Html->link($t, array('action' => 'view', $field[$model]['id']));

                                echo "<td class=\"{$class}\">";
                                if ($key == 'order') {
                                    ?>

                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <button type="button" class="btn btn-secondary"><?= $var ? $var : 0; ?></button>
                                <a href="<?= $this->Html->url(array('action' => 'moveUp', $field[$model]['id'], $here)); ?>" type="button" class="btn btn-secondary"><i class="fas fa-arrow-up"></i></a>
                                <a href="<?= $this->Html->url(array('action' => 'moveDown', $field[$model]['id'], $here)); ?>" type="button" class="btn btn-secondary"><i class="fas fa-arrow-down"></i></a>
                            </div>

                            <?php
                        } else {
                            //specific to menus
                            if ($key == 'mt_id') {
                                $t = $this->Html->link($field['MtMenu']['title'], array('action' => 'view', $field['MtMenu']['id']));
                            }

                            if ($key == 'trigger') {
                                switch ($var) {
                                    case 4:
                                    case '4':
                                        $t = __('Login');
                                        break;
                                    case 3:
                                    case '3':
                                        $t = __('Register');
                                        break;
                                    case 2:
                                    case '2':
                                        $t = __('Loss');
                                        break;
                                    case 1:
                                    case '1':
                                        $t = __('Win');
                                        break;

                                    case 0:
                                    case '0':
                                        $t = __('Deposit');
                                        break;
                                }
                            }


                            if ($key == 'image' || $key == 'image_mobile' || $key == 'thumb') {
                                if ($model == 'IntGame')
                                    $t = $this->Html->image($field['IntGame']['image'], array('alt' => $field['IntGame']['name'], 'width' => 120));

                                if ($model == 'PaymentMethod')
                                    $t = $this->Html->image($field['PaymentMethod']['image'], array('alt' => $field['PaymentMethod']['name'], 'width' => 120));


                                //https://bethappy.com/img/casino/bet-happy/banners/desktop-1.png
                                if ($model == 'Slide')
                                    $t = $this->Html->image($client_folder . '/banners/' . $field['Slide']['image'], array('alt' => $field['Slide']['name'], 'width' => 120));

                                if ($model == 'News')
                                    $t = $this->Html->image($client_folder . '/news/' . $field['News']['thumb'], array('alt' => $field['News']['name'], 'width' => 120));
                            }

                            if ($key == 'country_id') {
                                $t = '<span class="d-flex flex-column">' . $this->Html->image('https://flagcdn.com/' . strtolower($field['Country']['alpha2_code']) . '.svg', array('alt' => $field['Country']['name'], 'width' => 30)) . " <span>" . $field['Country']['name'] . '</span></span>';
                            }

                            if ($key == 'currency_id') {
                                $t = $field['Currency']['name'];
                            }

                            if ($key == 'payment_method_id') {
                                $t = $field['PaymentMethod']['name'];
                            }

                            if ($key == 'amount' OR $key == 'return' OR $key == 'balance') {
                                //find currency
//                                $t =  Configure::read('Settings.currency') . $t ;
                            } else if ($key == 'user_id') {
                                if ($model == 'Limit' && !empty($field['User']['username']))
                                    $t = $this->Html->link($field['User']['username'], array('controller' => 'users', 'action' => 'view', $field['User']['id']));


                                if ($var[$model . '-admin_index'] == 1) {
                                    if (!empty($var[$model . '-admin_index-username'])) {
                                        if (!empty($var[$model . '-admin_index-user_id'])) {
                                            $t = $this->Html->link($var[$model . '-admin_index-username'], array('controller' => 'users', 'action' => 'view', $var[$model . '-admin_index-user_id']));
                                        } else {
                                            $t = $var[$model . '-admin_index-username'];
                                        }
                                    }
                                }
                            } else if ($key == 'date' || $key == 'created' || $key == 'modified' || $key == 'registration_date' ||
                                    $key == 'date_of_birth' || $key == 'confirmation_code_created' || $key == 'logout_time' || $key == 'kyc_valud_until' ||
                                    $key == 'last_visit' || $key == 'last_activity_db' || $key == 'start_date' || $key == 'end_date') {
//                                $t = $this->Beth->convertDate($t);

                                if ($t == '') {
                                    
                                } else {
                                    $t = date("d-m-Y H:i:s", strtotime($t));
                                }
                            } elseif ($key == 'url') {
                                if ($model == 'Slide')
                                    $t = $this->Html->link($field['Slide']['url'], $field['Slide']['url']);
                            }
                            elseif ($key == 'color') {
                                $t = "<span class=\"color-palette\" style=\"background-color:{$var};height: 25px; width: 25px; padding: 0.25rem;\"></span>";
                            } elseif ($key == 'active' || $key == 'new' || $key == 'featured' ||
                                    $key == 'free_spins' || $key == 'fun_play' || $key == 'jackpot' ||
                                    $key == 'desktop' || $key == 'mobile') {
//                                var_dump($var);
//                                var_dump($field);

                                switch ($var) {
                                    case 'Yes':
                                    case 1:
                                    case '1':
                                        $var = 1;
                                        break;
                                    case 'No':
                                    case 0:
                                    case '0':
                                        $var = 0;
                                        break;
                                }


                                if ($model == 'IntGame') {

                                    if ($key == 'active') {
                                        $t = "<div class='custom-control custom-switch'>"
                                                . "<input type='checkbox' id='" . $key . '-' . $field[$model]['id'] . "' class='custom-control-input custom-switch-input' data-active='" . $var . "'  onchange=\"toggleActive('" . $model . "', " . $field[$model]['id'] . ")\"/>"
                                                . "<label class='custom-control-label' for='" . $key . '-' . $field[$model]['id'] . "'></label>"
                                                . "</div>";
                                    } else {
                                        $t = "<div class='custom-control custom-switch'>"
                                                . "<input type='checkbox' id='" . $key . '-' . $field[$model]['id'] . "' class='custom-control-input custom-switch-input' data-active='" . $var . "' disabled/>"
                                                . "<label class='custom-control-label' for='" . $key . '-' . $field[$model]['id'] . "'></label>"
                                                . "</div>";
                                    }
                                } else {
                                    $t = "<div class='custom-control custom-switch'>"
                                            . "<input type='checkbox' id='" . $field[$model]['id'] . "' class='custom-control-input custom-switch-input' data-active='" . $var . "'  onchange=\"toggleActive('" . $model . "', " . $field[$model]['id'] . ")\"/>"
                                            . "<label class='custom-control-label' for='" . $field[$model]['id'] . "'></label>"
                                            . "</div>";
                                }
                                //switch
                                //$var = $var ? 1 : 0;
//                                $t = "<div class='custom-control custom-switch'>"
//                                        . "<input type='checkbox' id='" . $field[$model]['id'] . "' class='custom-control-input custom-switch-input' data-active='" . $var . "'  onchange=\"toggleActive('" . $model . "', " . $field[$model]['id'] . ")\"/>"
//                                        . "<label class='custom-control-label' for='" . $field[$model]['id'] . "'></label>"
//                                        . "</div>";
                            } elseif ($key == 'brand_id' && $model == 'IntGame') {
                                $t = $this->Html->link($field['IntBrand']['name'], array('plugin' => 'int_games', 'controller' => 'int_brands', 'action' => 'view', $field['IntBrand']['id']));
                            } elseif ($key == 'category_id' && $model == 'IntGame') {
                                $t = $this->Html->link($field['IntCategory']['name'], array('plugin' => 'int_games', 'controller' => 'int_categories', 'action' => 'view', $field['IntCategory']['id']));
                            } elseif ($key == 'provider_id' && $model == 'PaymentMethod') {
                                $t = $field['PaymentProvider']['name'];
                                //$t = $this->Html->link($field['PaymentProvider']['name'], array('plugin' => 'payments', 'controller' => 'int_categories', 'action' => 'view', $field['IntCategory']['id']));
                            } elseif ($key == 'int_game_id' && $model == 'IntFreeSpin') {
                                //var_dump($field);
                                $t = $this->Html->link($field['IntGame']['name'], array('plugin' => 'int_games', 'controller' => 'int_games', 'action' => 'view', $field['IntGame']['id']));
                            } elseif ($key == 'int_plugin_id' && $model == 'IntFreeSpin') {
                                $t = $field['IntPlugin']['model'];
                            }

                            echo $t;
                        }
                        echo "</td>";
                    }
                    $k++;
                }

                if (isset($actions) AND is_array($actions) AND ! empty($actions)) {

                    if ($model == 'Deposit') {
                        if ($field[$model]['status'] == Deposit::DEPOSIT_TYPE_COMPLETED)
                            unset($actions[0]);
                        if ($field[$model]['status'] == Deposit::DEPOSIT_TYPE_CANCELLED)
                            unset($actions[1]);
                    }

                    if ($model == 'Withdraw') {
                        if ($field[$model]['status'] == Withdraw::WITHDRAW_TYPE_PENDING)
                            unset($actions[1]);
                        if ($field[$model]['status'] == Withdraw::WITHDRAW_TYPE_COMPLETED)
                            unset($actions[0], $actions[1]);
                        if ($field[$model]['status'] == Withdraw::WITHDRAW_TYPE_CANCELLED)
                            unset($actions[1], $actions[2]);
                    }

                    echo "<td style='min-width: 190px;' class=\"actions {$class}\">\n";
                    foreach ($actions as $action) {

                        if ($model == 'Deposit')
                            $action['controller'] = 'deposits';
                        if ($model == 'Withdraw')
                            $action['controller'] = 'withdraws';

                        if ($action['action'] == 'delete' || $action['action'] == 'cancel' || $action['action'] == 'complete') {
                            $delete = __('Are you sure?');
                        } else {
                            $delete = NULL;
                        }

                        if (isset($action['controller'])) {
                            echo $this->Html->link($action['name'], array('controller' => $action['controller'], 'action' => $action['action'], $field[$model]['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                        } else {
                            echo $this->Html->link($action['name'], array('controller' => $this->params['controller'], 'action' => $action['action'], $field[$model]['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                        }
                        echo ' ';
                    }
//                    var_dump($translate);
//                    if (isset($translate) AND $translate == true) {
//                        
//                        echo $this->Html->link(__('Translate', true), array('action' => 'translate', $field[$model]['id']), array('class' => "btn btn-sm btn-dark"));
//                    }
                    echo "</td>";
                }

                //if (isset($translate) AND $translate == true) {
                //$languages = json_decode($this->requestAction('/Languages/getLanguages'), true);
                //foreach ($languages as $language) {                           
                //if (!isset($newlang[$language['locale_code']]))
                //  $newlang[$language['locale_code']] = $language['name'];
                // }
                //echo "<td class=\"actions {$class}\">";
                ?>
                                                                                                                                                                                                                                                                <!--<select id="translatedList" class="form-control form-control-sm">-->
                <?php
                //foreach ($field['translations'] as $translation) {
                //foreach ($newlang as $lang) {
                ?>
                <?php
                //if ($translation['locale'] != Configure::read('Admin.defaultLanguage'))
                //if ($translation['locale'] != Configure::read('Config.language')) {
                ?>
                                                                                                                                                                                                                                                                            <!--<option value="<?//= $this->Html->url(array('action' => 'translate', $translation['foreign_key'], $translation['locale'])); ?>"><?= $lang; ?></option>-->
                <?php
                //}
                //}
                ?>
                <!--</select>-->
                <?php
                //echo "</td>";
                //}
                //}
                ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('paginator'); ?>

<?php else: ?>
    <p><?= __('No records found.'); ?></p>
<?php endif; ?>


<script type="text/javascript">
    $(document).ready(function () {

        $("#translatedList").change(function () {
            var selected = $(this).find("option:selected");
            location.href = selected.attr("value");
        });
    });
    $(".custom-switch-input").each(function () {
        console.log($(this).data("active"));
        if ($(this).data("active") === 1)
            this.checked = true;
    });


    function parseModel(model) {
        console.log(model);
        var controller = '';
        switch (model) {
            case 'Country':
                controller = 'countries';
                break;
            case 'Currency':
                controller = 'currencies';
                break;
            case 'Language':
                controller = 'languages';
                break;
            case 'Slide':
                controller = 'slides';
                break;
            case 'Page':
                controller = 'pages';
                break;
            case 'MtMenu':
                controller = 'mt_menus';
                break;
            case 'MbMenu':
                controller = 'mb_menus';
                break;
            case 'BonusType':
                controller = 'BonusTypes';
                break;
            case 'PaymentMethod':
                controller = 'payments/PaymentsMethods';
                break;
            case 'IntBrand':
                controller = 'int_games/int_brands';
                break;
            case 'IntBrand':
                controller = 'int_games/int_brands';
                break;
            case 'IntGame':
                controller = 'int_games/int_games';
                break;
        }

        return controller;
    }

    function toggleActive(model, model_id) {
        console.log(model_id);
        console.log(model);

        var controller = parseModel(model);
        $.ajax({
            url: '/admin/' + controller + '/toggleActive/' + model_id,
            method: "GET",
            success: function (data) {
                //console.log(data);

            }
        });

    }

</script>
