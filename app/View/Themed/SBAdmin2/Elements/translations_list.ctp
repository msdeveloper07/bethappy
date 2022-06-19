<?php if (!empty($data)) {
    $i = 1;
    foreach ($data as $field) {
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' alt';
        } 
        $k = 0;
        if (isset($translate) AND $translate == true) {
             $languages = $this->requestAction('/Languages/getLanguages');       
            foreach ($languages as $lang){
                if (!isset( $newlang[$lang['name']])){
                    $newlang[$lang['name']] = $lang['language'];
                }
            }
            echo "<td class=\"actions {$class}\">"; ?>
            <select id="navigation">
               <?php foreach ($field['translations'] as $translation) {
                    if ($translation['locale'] != Configure::read('Admin.defaultLanguage')) { ?>
                        <option value="" onclick="location.href='<?php echo $this->Html->url(array('action' => 'translate', $field[$model]['id'], $translation['locale'])); ?>' ">
                            <?php echo $newlang[$translation['locale']]; ?>
                        </option>
                <?php  }
                } ?>
            </select>
         <?php   echo "</td>";
        }
    }
}
?>