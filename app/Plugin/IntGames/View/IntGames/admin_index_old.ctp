<!--<script type="text/javascript" src="/Layout/Admin/js/intgames.js" ></script>-->
<div class="container-fluid">
    <!--<div class="row-fluid"><?//= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <?php if (!empty($data)) { ?>
            <div class="row-fluid">
                <div id="showdata_bulk" class="dataBulk_length">
                    <label for="set_bulk"><?=__('Bulk Actions');?>
                        <select id="bulk_actions" size="1" name="bulk_actions" aria-controls="bulk_actions">
                            <?php foreach (IntGames::$bulkActions as $bulk => $action): ?>
                            <option value="<?=$bulk;?>"><?=__($action);?></option>
                            <?php endforeach; ?>
                        </select>

                    </label>

                    <label for="set_category"><?=__('Set Category');?>
                        <select size="1" name="categories" aria-controls="categories">
                            <?php foreach ($categories as $c => $intcategory): ?>
                            <option value="<?=$c;?>"><?=__($intcategory);?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label for="set_brand"><?=__('Set Brand');?>
                        <select size="1" name="brands" aria-controls="brands">
                            <?php foreach ($brands as $br => $brand): ?>
                            <option value="<?=$br;?>"><?=__($brand);?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <input id="setbulk" type="submit" class="btn btn-inverse" />
                    
<!--                    <select name="force_gamelists" id="force_gamelists">
                        <option disabled selected><?//=__('Force Games to be updated');?></option>
                        <option value="all"><?//=__('Request All Games');?></option>
                        <?php //foreach ($aggregators as $agg): ?>
                            <option value="<?//= $agg['model'];?>"><?=$agg['name'] . ' ' . __('Games');?></option>
                        <?php //endforeach; ?>
                    </select>-->
                </div>
            </div>

            <div class="row">
                <div id="groupcats" class="col-md-2">
                    <ul>
                        <?php foreach ($data as $gamecat): ?>
                            <li class="namecap" id="<?=$gamecat['IntCategory']['id'];?>"><?=$gamecat['IntCategory']['name'];?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div id="loadertab">
                    <div class="ui-loader ui-corner-all ui-body-b ui-loader-verbose">
                        <i class="fas fa-spinner fa-spin"></i>
                        <h4 class="text-center"><?=__('Loading games');?></h4>
                    </div>
                </div>
                <div id="groupnogames" class="col-md-10"><?=__('Select a category to load games...');?></div>
                <div id="grouptable" class="col-md-10"></div>
                
                <form class="uploadimage" name="uploadimage" method="post" enctype="multipart/form-data">
                    <input type="file" name="intimage">
                    <input type="submit" value="Upload Image" name="submit">
                </form>
                
                <form class="updateorder" name="updateorder" method="post" enctype="multipart/form-data">
                    <input type="text" name="intorder" class="form-control" id="intorder"/>
                    <input type="submit" value="Save" name="submit" class="btn btn-success">
                </form>
            </div>
        <?php } else { ?>
            <div class="cleardiv"></div>
            <br><br>
            <h5 class="span12"><?=__('No games found.');?></h5>
        <?php } ?>
    </div>
</div>