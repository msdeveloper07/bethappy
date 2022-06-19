<?php
/**
 * Risk Model
 *
 * Handles Risk Data Source Actions
 *
 * @package    Risks.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Risk extends AppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'Risk';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var $useTable string
     */
    public $useTable = false;
    
    public function getLeaguesOrdered($sport_id = null) {
        $lmodel = ClassRegistry::init('League');
        $cmodel = ClassRegistry::init('Country');
        
        $leagues = $lmodel->query("select 
                translate('Country', 'name', c.id, '" . Configure::read('Config.language') . "') as country_name, 
                translate('Sport', 'name', s.id, '" . Configure::read('Config.language') . "') as sport_name, 
                translate('League', 'name', l.id, '" . Configure::read('Config.language') . "') as league_name, 
                l.*
        from
            leagues as l
                inner join
            countries as c on c.id = l.country_id
                inner join
            sports as s on s.id = l.sport_id
        where l.active = 1 " . (!empty($sport_id) ? " and l.sport_id = " . $sport_id : '') . "
        order by country_name, league_name;");

        $countries = $cmodel->query("
            select
                c.id,
                count(l.id) as league_count,
                translate('Country', 'name', c.id, '" . Configure::read('Config.language') . "') as country_name, 
                translate('Sport', 'name', s.id, '" . Configure::read('Config.language') . "') as sport_name
            from
                leagues as l
                    inner join
                countries as c on c.id = l.country_id
                    inner join
                sports as s on s.id = l.sport_id
            where l.active = 1
            group by c.id
            order by country_name;");

        foreach ($leagues as &$league) {
            $league['League'] = $league['l'];
            $league['League']['sport_name'] = $league['0']['sport_name'];
            $league['League']['name'] = $league['0']['league_name'];

            $data[$league['l']['country_id']]['country_name']  = $league['0']['country_name'];
            $data[$league['l']['country_id']]['sport_name']  = $league['0']['sport_name'];
            $data[$league['l']['country_id']]['Leagues'][]   = $league;
        }
        return array('data' => $data, 'countries' => $countries);
    }
    
    public function updateRisk($type, $fields) {
        $model = ClassRegistry::init($type);
        $fields = unserialize($fields);
        
        $data[$type]['id'] = $fields['id'];
        $data[$type]['min_bet'] = $fields['min_bet'];
        $data[$type]['max_bet'] = $fields['max_bet'];
        $data[$type]['min_multi_bet'] = $fields['min_multi_bet'];
        $data[$type]['max_multi_bet'] = $fields['max_multi_bet'];
        
        return $model->save($data);
    }
}