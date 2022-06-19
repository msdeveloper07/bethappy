<?php if (!empty($fields)) : ?>
    <div class="<?= __($pluralName); ?> view">    
        <h2><?= __($singularName); ?></h2>
        <?php if (isset($noTranslation)): ?>
            <div><?=__("No translation for this language,");?> <?= $this->Html->link('create', array('action' => 'edit', $this->params['pass'][0])); ?></div>
        <?php else: ?>    

            <table class="items" cellpadding="0" cellspacing="0">
                <?php $i = 1; ?>
                <?php foreach ($fields[$model] as $key => $value): ?>
                    <?php
                    $class = '';
                    if ($i++ % 2 == 0) $class = 'alt'; ?>
                    <tr>
                        <th class="specalt"><?= __(Inflector::humanize($key)); ?></th>
                        <td><?= __($value); ?></td>
                    </tr>
                <?php endforeach; ?>    
                <tr>
                    <th class="specalt"><?=__('First Name');?></th>
                    <td><?= $user['first_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt"><?=__('Last Name');?></th>
                    <td><?= $user['last_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt"><?=__('Bank Name');?></th>
                    <td><?= $user['bank_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt"><?=__('Bank code');?></th>
                    <td><?= $user['bank_code'] ?></td>
                </tr>
                <tr>
                    <th class="specalt"><?=__('Account number');?></th>
                    <td><?= $user['account_number'] ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
<?php endif; ?>