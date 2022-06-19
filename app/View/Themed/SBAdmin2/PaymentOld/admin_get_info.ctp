<style>
    h3{
        color:#FFF;
    }
    .small-table {
        background: -moz-linear-gradient(top,  rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.65) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0.65)), color-stop(100%,rgba(0,0,0,0.65))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a6000000', endColorstr='#a6000000',GradientType=0 ); /* IE6-9 */
        border-radius:7px;
        opacity:-1px;
        padding-top:7px;
        padding-bottom:3px;
    }

    .litable {
        list-style:none;
        color:#fff;
    }
</style>

    <?php foreach($data as $rows): ?>
        <h3><?= __(Inflector::humanize($title)); ?></h3>
        <ul>
        <?php foreach($rows as $row): ?>
            
            <?php foreach($row as $key=>$r): ?>
                <li class="litable"><b><?= __(Inflector::humanize($key)); ?>:</b>
                    &nbsp;&nbsp;&nbsp;
                    <?php   
                        if ($key=="status"){
                            echo $statuses[$r];
                        }else{
                            echo $r;
                        }
                    ?>
                    &nbsp;&nbsp;&nbsp;</li>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>



