<?php
/**
 * Affiliate Media ACL Model
 *
 * Handles the access control of the affiliate media
 *     
 */

class AffiliateMediaACL extends AppModel {

     /**
     * Model name
     *
     * @var string
     */
    public $name = 'AffiliateMediaACL';
	       
    public $useTable = 'affiliatemedia_acl';   
    
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array('Affiliate');
        
    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'file_path'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'affiliate_id'       => array(
            'type'      => 'int',
            'length'    => 255,
            'null'      => false
        ),
        'global'  => array(
            'type'      => 'int',
            'length'    => 4,
            'null'      => false
        )
    );    
    
    public function get_by_path($path) {
        $acls = $this->_get_by_path($path);
    
        if(!empty($acls)) {
            return $acls;
        } else {
            $this->save( array(
                'file_path' => $path,
                'global' => 1
            ));
            
            return $this->_get_by_path($path);
        }
    }
    
    public function _get_by_path($path) {
        return $this->find('all', array(
            'recursive' => 1,
            'conditions' => array(
                'AffiliateMediaACL.file_path' => $path
            )
        ));
    }
        
    // Use the folder in a like query statement (<foldername>%) to find the accessible files for this affiliate 
    // and filter the folder contents accordingly
    public function get_accessible_media($dir, $aff_id) {
        return $this->query("SELECT * FROM affiliatemedia_acl where (affiliate_id = {$aff_id} or global = 1) and file_path like '{$dir}%';");
    }
    
    public function has_access($dir, $aff_id) {
        $result = $this->query("SELECT * FROM affiliatemedia_acl where (affiliate_id = {$aff_id} or global = 1) and file_path = '{$dir}';");
                
        return count($result) > 0;
    }
    
    public function get_global_control($path) {
        return $this->find('first', array(
            'recursive' => 1,
            'conditions' => array(
                'AffiliateMediaACL.file_path' => $path,
                'AffiliateMediaACL.global' => 1,
            )
        ));
    }
}