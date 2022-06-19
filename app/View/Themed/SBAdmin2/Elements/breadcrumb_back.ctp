<?php
if(isset($data) AND is_array($data)): 

$backurl = $this->Breadcrumb->getbackurl($this->request->referer());
    ?>
    <ul class="breadcrumb" style="width:20px;height:16px;float:left">
        <?php foreach($data AS $breadcrumbIndex => $breadcrumbData):?>
            <?php if($breadcrumbIndex == 0): ?>
                <li>
                    <a href="<?php echo $this->Html->url($backurl); ?>"><i class="icon-backward"></i></a>
                    <span class="divider">&nbsp;</span>
                </li>
            <?php else: ?>
                <li>
                    <?php echo $this->Html->link($breadcrumbData['title'], $breadcrumbData['url']); ?>
                    <span class="divider<?php if((count($data) -1) == $breadcrumbIndex): ?>-last<?php endif; ?>">&nbsp;</span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>