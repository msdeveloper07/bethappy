<?php if (!empty($fields)) : ?>
    <?php if (isset($noTranslation)): ?>
        <div>No translation for this language, <?php echo $this->Html->link('create', array('action' => 'edit', $this->params['pass'][0])); ?></div>
    <?php else: ?>

        <ul class="list-group">
            <?php
            $i = 1;
//            var_dump($model); 
            ?>
            <?php
            foreach ($fields[$model] as $key => $value):
//                var_dump($key);
//                var_dump($value);
                ?>
                <?php
                $class = '';
                if ($i++ % 2 == 0)
                    $class = 'alt';

                if ($key == 'mobile_number')
                    $key = 'Phone';

                if ($key == 'brand_id')
                    $key = 'Brand';

                if ($key == 'category_id')
                    $key = 'Category';

                if ($key == 'user_id')
                    $key = 'User';

                if ($key == 'game_id')
                    $key = 'Game';

                if ($key == 'name')
                    $alt = $value;

                if ($key == 'country_id')
                    $key = 'Country';

                if ($key == 'currency_id')
                    $key = 'Currency';


                if ($key == 'payment_method_id')
                    $key = 'Payment Method';
                ?>
                <li class="list-group-item form-group">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <?php echo Inflector::humanize($key); ?>
                        </div>
                        <div class="col-md-8">
                            <?php
                            //var_dump($key);
                            switch ($key) {

                                case 'color':
                                    echo '<div class="d-flex">' . $value . ' <span class="color-palette" style="background-color:' . $value . ';height: 25px; width: 25px; padding: 0.25rem; margin-left:10px;"></span></div>';
                                    break;
                                case 'user_id':
                                case 'User':
                                    //var_dump($fields);
                                    if (!empty($fields['User']['username']))
                                        echo $this->Html->link($fields['User']['username'], array('controller' => 'users', 'action' => 'view', $fields['User']['id']));

                                    break;
                                case 'game_id':
                                case 'Game':
                                    //var_dump($fields);
                                    if (!empty($fields['IntGame']['name']))
                                        echo $this->Html->link($fields['IntGame']['name'], array('plugin' => 'int_games', 'controller' => 'int_games', 'action' => 'view', $fields['IntGame']['id']));
                                    break;
                                case 'brand_id':
                                case 'Brand':
                                    if (!empty($fields['IntBrand']['name']))
                                        echo $fields['IntBrand']['name'];
                                    break;
                                case 'category_id':
                                case 'Category':
                                    if (!empty($fields['IntCategory']['name']))
                                        echo $fields['IntCategory']['name'];
                                    break;
                                case 'image':
                                case 'image_mobile':
                                case 'thumb':
                                    if ($model == 'IntGame')
                                        echo $this->Html->image($value, array('alt' => $alt, 'width' => 120));

                                    if ($model == 'Slide')
                                        echo $this->Html->image($client_folder . '/banners/' . $value, array('alt' => $alt, 'width' => '100%'));

                                    if ($model == 'News')
                                        echo $this->Html->image($client_folder . '/news/' . $value, array('alt' => $alt, 'width' => '100%'));

                                    break;
                                case 'country_id':
                                case 'Country':
                                    if (!empty($fields['Country']['alpha2_code']))
                                        echo $this->Html->image('https://flagcdn.com/' . strtolower($fields['Country']['alpha2_code']) . '.svg', array('alt' => $fields['Country']['name'], 'width' => 30)) . " " . $fields['Country']['name'];
                                    break;
                                case 'url':
                                    if ($model == 'Slide')
                                        echo $this->Html->link($value, $value);
                                    break;
                                case 'currency_id':
                                case 'Currency':
                                    if (!empty($fields['Currency']['name']))
                                        echo $fields['Currency']['name'];
                                    break;
                                case 'payment_method_id':
                                case 'Payment Method':
                                    if ($model == 'Limit' && !empty($fields['PaymentMethod']['name']))
                                        echo $fields['PaymentMethod']['name'];
                                    break;
                                case 'created':
                                case 'modified':
                                case 'date':
                                case 'start_date':
                                case 'end_date':
                                    if ($value !== NULL)
                                        echo date("d-m-Y H:i:s", strtotime($value));
                                    break;
                                case 'active':
                                case 'new':
                                case 'featured':
                                case 'free_spins':
                                case 'fun_play':
                                case 'jackpot':
                                case 'desktop':
                                case 'mobile':
                                    switch ($value) {
                                        case 'Yes':
                                        case 1:
                                        case '1':
                                            $value = 'checked';
                                            break;
                                        case 'No':
                                        case 0:
                                        case '0':
                                            $value = '';
                                            break;
                                    }
                                    echo '<div class="custom-control custom-switch">' .
                                    '<input type="checkbox" class="custom-control-input" id="' . $key . '" ' . $value . '/>' .
                                    '<label class="custom-control-label" for="' . $key . '"></label>' .
                                    '</div>';
                                    break;
                                default:
                                    echo $value;
                            }
                            ?>
                        </div>
                    </div>
                </li>     
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
