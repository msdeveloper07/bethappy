<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div></div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span2">
                <div id="jstree">
                    <ul>
                        <li data-jstree='{"opened":true}'><a href="/admin/trading/defaultOddskeys"><?=__('Default');?></a></li>
                        <?php foreach ($sports as $sport): ?>
                            <li>
                                <a href="/admin/trading/setOddskeys/Sport/<?=$sport['Sport']['id'];?>"><?=$sport['Sport']['sport_name'];?></a>
                                <ul>
                                    <?php foreach ($sport['Country'] as $country): ?>
                                        <li>
                                            <a href="/admin/trading/setOddskeys/Country/<?=$sport['Sport']['id'];?>/<?=$country['Country']['id'];?>"><?=$country['Country']['country_name'];?></a>
                                            <ul>
                                                <?php foreach($country['League'] as $league): ?>
                                                    <li>
                                                        <a href="/admin/trading/setOddskeys/League/<?=$sport['Sport']['id'];?>/<?=$country['Country']['id'];?>/<?=$league['id'];?>"><?=$league['league_name'];?></a>
                                                        <ul>
                                                            <li data-jstree='{"icon":false}'><a href="/admin/events/events/<?=$sport['Sport']['id'];?>/<?=$country['Country']['id'];?>/<?=$league['id'];?>"><?=__('Select Event');?></a></li>
                                                        </ul>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div id="loadcontent" class="span9"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () { $('#jstree').jstree(); });
    
    $(document).ready(function(){
        $('#jstree').on("changed.jstree", function (e, data) {
            $('#loadcontent').html('<img style="width: 50px; position: fixed; top: 50%; left: 50%;" src="/img/admin/loading.gif" />');
            $('#loadcontent').load(data.node.a_attr.href);
        });
    });
</script>