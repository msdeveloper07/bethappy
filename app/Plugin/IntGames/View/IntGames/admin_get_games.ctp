<table id="show<?= $category; ?>" class="showdata table table-hover table-bordered table-striped tablesorter table-condensed">
    <thead>
        <tr>
            <th>
                <input type="checkbox" name="checkall" cat-id="<?= $category; ?>" value="all" />
                &nbsp;<?= __('Select'); ?> <i class="fa fa-sort th-icon"></i>
            </th>
            <th><?= __('ID'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th><?= __('name'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Image'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th><?= __('Brand'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Source'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Source ID'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Order'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('New'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Active'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Mobile'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Desktop'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-center"><?= __('Fun Play'); ?> <i class="fa fa-sort th-icon"></i></th>
            <th class="text-right"><?= __('Visited'); ?> <i class="fa fa-sort th-icon"></i></th>
        </tr>
    </thead>
    <tbody id="bid-<?= $category; ?>">
        <?php if (!empty($data)): ?>

            <?php foreach ($data as $game): ?>
                <tr id="<?= $game['IntGames']['id']; ?>" data-source="<?= $game['IntGames']['source']; ?>">
                    <td><input type="checkbox" name="checkgame" value="<?= $game['IntGames']['id']; ?>" /></td>
                    <td><?= $game['IntGames']['id']; ?></td>
                    <td><?= $game['IntGames']['name']; ?></td>
                    <td class="showimage" id="<?= $game['IntGames']['id']; ?>">
                        <span class="icon-edit" id="<?= $game['IntGames']['id']; ?>" style="position: relative; float: right;"></span>
                        <img src="<?= $game['IntGames']['image']; ?>" style="width:100%;height:auto;"/>
                    </td>
                    <td><?= $brands[$game['IntGames']['brand_id']]; ?></td>
                    <td class="<?= $allints[$game['IntGames']['source']]; ?>"><?= $game['IntGames']['source']; ?></td>
                    <td><?= $game['IntGames']['source_id']; ?></td>
                    <td data-type="order" id="<?= $game['IntGames']['id']; ?>" class="order-col">
                        <div class="showorder">
                            <span class="icon-edit" id="<?= $game['IntGames']['id']; ?>" style="position: relative; float: right;"></span>
                            <div class="game-id" data-gameid="<?= $game['IntGames']['id']; ?>" style="display: none"><?= $game['IntGames']['id']; ?></div>
                            <div id="order" data-order="<?= $game['IntGames']['order']; ?>"><?= $game['IntGames']['order']; ?></div>
                        </div>
                    </td>

                    <td data-type="new">
                        <div class="switch-wrapper">
                            <input type="checkbox" class="checkswitch" data-action="<?= $configactions['new']; ?>" value="<?= $game['IntGames']['new']; ?>" <?= (($game['IntGames']['new']) ? 'checked' : ''); ?> style="display: none;">
                            <div class="switch-button-background <?= (($game['IntGames']['new']) ? 'checked' : ''); ?>" <?= (($game['IntGames']['new']) ? 'checked' : ''); ?>>
                                <div class="switch-button-button"></div>
                            </div>
                        </div>
                    </td>

                    <td data-type="enable">
                        <div class="switch-wrapper">
                            <input type="checkbox" class="checkswitch" data-action="<?= $configactions['enable']; ?>" value="<?= $game['IntGames']['active']; ?>" <?= (($game['IntGames']['active']) ? 'checked' : ''); ?> style="display: none;">
                            <div class="switch-button-background <?= (($game['IntGames']['active']) ? 'checked' : ''); ?>" <?= (($game['IntGames']['active']) ? 'checked' : ''); ?>>
                                <div class="switch-button-button"></div>
                            </div>
                        </div>
                    </td>

                    <td data-type="mobile">
                        <div class="switch-wrapper">
                            <input type="checkbox" class="checkswitch" data-action="<?= $configactions['mobile']; ?>" value="<?= $game['IntGames']['mobile']; ?>" <?= (($game['IntGames']['mobile']) ? 'checked' : ''); ?> style="display: none;">
                            <div class="switch-button-background <?= (($game['IntGames']['mobile']) ? 'checked' : ''); ?>" <?= (($game['IntGames']['mobile']) ? 'checked' : ''); ?>>
                                <div class="switch-button-button"></div>
                            </div>
                        </div>
                    </td>

                    <td data-type="desktop">
                        <div class="switch-wrapper">
                            <input type="checkbox" class="checkswitch" data-action="<?= $configactions['desktop']; ?>" value="<?= $game['IntGames']['desktop']; ?>" <?= (($game['IntGames']['desktop']) ? 'checked' : ''); ?> style="display: none;">
                            <div class="switch-button-background <?= (($game['IntGames']['desktop']) ? 'checked' : ''); ?>" <?= (($game['IntGames']['desktop']) ? 'checked' : ''); ?>>
                                <div class="switch-button-button"></div>
                            </div>
                        </div>
                    </td>

                    <td data-type="funplay">
                        <div class="switch-wrapper">
                            <input type="checkbox" class="checkswitch" data-action="<?= $configactions['fun_play']; ?>" value="<?= $game['IntGames']['fun_play']; ?>" <?= (($game['IntGames']['fun_play']) ? 'checked' : ''); ?> style="display: none;">
                            <div class="switch-button-background <?= (($game['IntGames']['fun_play']) ? 'checked' : ''); ?>" <?= (($game['IntGames']['fun_play']) ? 'checked' : ''); ?>>
                                <div class="switch-button-button"></div>
                            </div>
                        </div>
                    </td>
                    <td><?= $game['IntGames']['open_stats'] . ' ' . __('times'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>


<script type="text/javascript" src="/js/data-tables-1.10.8/jquery.data-tables.js"></script>
<script type="text/javascript" src="/js/admin/tablegames.js"></script>