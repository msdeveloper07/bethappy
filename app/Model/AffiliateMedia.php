<?php
/**
 * Affiliate Media Model
 * Handles Affiliate Media Data Source Actions
 */

class AffiliateMedia extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'AffiliateMedia';
	       
    public $useTable = 'affiliatemedia';   
    
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var array
     */
    public $belongsTo = array('Affiliate');
        
    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'affiliate_id'  => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'file_path'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'impressions'       => array(
            'type'      => 'int',
            'length'    => 20,
            'null'      => false
        ),
        'clicks'  => array(
            'type'      => 'int',
            'length'    => 20,
            'null'      => false
        ), 
        'registrations'  => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ), 
        'deposits'  => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ), 
        'created'       => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'modified'       => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * Returns admin index fields
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'AffiliateMedia.id',
            'AffiliateMedia.affiliate_id',
            'AffiliateMedia.file_path',
            'AffiliateMedia.impressions',
            'AffiliateMedia.clicks',
            'AffiliateMedia.registrations',
            'AffiliateMedia.created'
        );
        return $options;
    }
    
    /**
     * Returns search fields
     * @return array|mixed
     */
    public function getSearch() {
        $fields = array(
            'AffiliateMedia.id'             => $this->getFieldHtmlConfig('number', array('label' => __('AffiliateMedia Id'))),
            'AffiliateMedia.affiliate_id'   => array('type' => 'text'),
            'AffiliateMedia.created'        => $this->getFieldHtmlConfig('date', array('label' => __('Creation Date'))),
        );
        return $fields;
    }
    
    /**
     * Adds media to affiliate
     * @param {string} $file
     * @param {int} $aff_id
     * @return {id} media id
     */
    public function addMediatoAffiliate($file, $aff_id) {
        $this->create();
        
        // current date
        $now = $this->getSqlDate();
        
        $data = array(
            'AffiliateMedia' =>  array(
                'affiliate_id'  =>  $aff_id,
                'file_path'     =>  $file,
                'created'       =>  $now,                
                'modified'      =>  $now
            )
        );
                
        // store in db
        $this->save($data);

        // return id
        return $this->id;
    }
    
    /**
     * Get affiliate's selected media
     * @param {int} $aff_id
     * @return {array} of affiliate media objs
     */
    public function getAffiliateMedia($aff_id) {
        return $this->find('all', array('recursive' => '-1', 'conditions' => array('AffiliateMedia.affiliate_id' => $aff_id)));
    }
    
    /**
     * Generates script for the selected media
     * @param {int} $media_id
     * @return {string} code
     */
    public function generateMediaScript($media) {
        if(empty($media)) return false;
        
        $size = getimagesize(Router::url('/', true) . 'img/banners/' .$media['AffiliateMedia']['file_path']);
        
        return "<div class=\"affiliate-banner\" id='media-{$media['AffiliateMedia']['id']}'><script type='text/javascript'>" .
                "(function(){document.getElementById('media-{$media['AffiliateMedia']['id']}').innerHTML = ".
                "'<iframe scrolling=\"no\" {$size[3]} frameborder=\"0\" src=\"" . Router::url('/', true) . 
                "AffiliateMedia/create/{$media['AffiliateMedia']['id']}\"></iframe>';})();</script></div>";
    }
    
    /**
     * Retrieves media item from db
     * @param {int} $id
     * @return {array} media item
     */
    public function getItem($id) {
        return $this->find('first', array(
            'recursive' => '-1',
            'conditions' => array(
                'AffiliateMedia.id' => $id,
            )
        ));
    }
}