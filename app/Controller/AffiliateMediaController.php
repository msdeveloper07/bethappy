<?php
/**
 * @file AffiliatesController.php 
 */

class AffiliateMediaController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'AffiliateMedia';
    
    /**
     * Models
     * @var array
     */
    public $uses = array('AffiliateMedia', 'Affiliate', 'AffiliateMediaACL', 'User');

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array(0 => 'Paginator');
            
    function beforeFilter() {
        parent::beforeFilter();
        
        // methods exposed
       /*
        *  $this->Auth->allow(array(
            'link',
            'index',
            'create',
            'handler',
            'admin_index',
            'admin_access',
            'admin_acl_remove',
            'admin_acl_add',
            'admin_acl_global'
        ));
        * 
        */
    }
    
    public function index(){                    
        $this->layout = 'affiliate';
    }
    
    public function admin_index($id) {                   
        // set fields
        $this->paginate = $this->AffiliateMedia->getIndex();
        
        // set conditions
        $this->paginate['conditions'] = array();
        
        if(!empty($id)) $this->paginate['conditions']['AffiliateMedia.affiliate_id'] = $id;
        
        if (!empty($this->request->data)) {            
            if(!empty($this->request->data['AffiliateMedia'])) {
                foreach($this->request->data['AffiliateMedia'] as $key => $value) {
                    if(empty($value)) continue;  
                    
                    if($key == 'affiliate_id') {
                        $affiliate = $this->Affiliate->find('first', array('recursive' => -1, 'conditions'    => array('Affiliate.affiliate_custom_id' => $value)));
                        
                        if(!empty($affiliate)) $this->paginate['conditions']['AffiliateMedia.affiliate_id'] = $affiliate['Affiliate']['id'];
                    } else {
                        $this->paginate['conditions']['AffiliateMedia.' . $key] = $value;                         
                    }                    
                }
            }            
        }
        
        // order
        $this->paginate['order'] = array('AffiliateMedia.created' => 'DESC');  
              
        $data = $this->paginate();
        
        foreach($data as &$media) {
            $affiliate = $this->Affiliate->find('first', array('recursive' => -1, 'conditions'    => array('Affiliate.id' => $media['AffiliateMedia']['affiliate_id'])));
            $media['AffiliateMedia']['affiliate_id'] = $affiliate['Affiliate']['affiliate_custom_id'];
        }
        $this->set('data', $data);
        $this->set('search_fields', $this->AffiliateMedia->getSearch());
    }
    
    public function admin_access($dir, $name) {
        if(empty($dir) || empty($name)) $this->redirect($this->referer());

        $acl = $this->AffiliateMediaACL->get_by_path($dir . '/' . $name);
        $is_global = false;
        $affiliates = array();
        
        foreach($acl as $control) {
            if($control['AffiliateMediaACL']['global'] == '1') {
                $is_global = true;
            } else if (!empty($control['Affiliate'])) {
                $user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('status' => 1, 'id' => $control['Affiliate']['user_id'])));
                
                if(empty($user)) continue;
                
                $control['Affiliate']['acl_id'] = $control['AffiliateMediaACL']['id'];
                $control['Affiliate']['username'] = $user['User']['username'];

                array_push($affiliates, $control['Affiliate']);
            }
        }
                
        $this->set('is_global', $is_global);
        $this->set('path', ($dir . '/' . $name));
        $this->set('affiliates', array_filter($affiliates));
    }
    
    public function admin_acl_remove() {
        $this->autoRender = false;
        
        $id = $this->request->query['id'];
        
        if(!empty($id) && $this->AffiliateMediaACL->delete($id)) return json_encode(array('success' => __('Affiliate\'s access to media removed.')));
        return json_encode(array('error' => __('Action failed')));
    }
    
    public function admin_acl_add() {
        $this->autoRender = false;
                            
        $path = $this->request->query['path'];
        $aff_cid = $this->request->query['aff_cid'];
                
        if(!empty($path) && !empty($aff_cid)) {
            $affiliate = $this->Affiliate->find('first', array('recursive' => -1, 'conditions' => array('Affiliate.affiliate_custom_id' => $aff_cid)));
            
            if(!empty($affiliate)) {
                if($this->AffiliateMediaACL->has_access($path, $affiliate['Affiliate']['id'])) {
                    return json_encode(array('error' => __('Affiliate already has access to this media.')));                
                }
            
                $data = array('file_path' => $path, 'affiliate_id' => $affiliate['Affiliate']['id']);
                
                $this->AffiliateMediaACL->create();
                
                if($this->AffiliateMediaACL->save($data)) {
                    $user = $this->User->find('first', array(
                        'recursive' => -1,
                        'conditions' => array(
                            'status' => 1,
                            'id' => $affiliate['Affiliate']['user_id']
                        )
                    ));
                    
                    $affiliate['Affiliate']['acl_id'] = $this->AffiliateMediaACL->id;
                    $affiliate['Affiliate']['username'] = $user['User']['username'];
                
                    return json_encode($affiliate['Affiliate']);
                }                
            }
        }
        return json_encode(array('error' => __('Action failed')));
    }
    
    public function admin_acl_global() {
        $this->autoRender = false;
        
        $path = $this->request->query['path'];
        $is_global = $this->request->query['is_global'] === "true";
                
        if(!empty($path)) {                        
            if($is_global) { 
                $data = array('file_path' => $path, 'global' => 1);
                
                $this->AffiliateMediaACL->create();
                if($this->AffiliateMediaACL->save($data)) return json_encode(array('success' => __('Affiliate media made public.')));
            } else {                
                $acl = $this->AffiliateMediaACL->get_global_control($path);
                
                if(!empty($acl) && $this->AffiliateMediaACL->delete($acl['AffiliateMediaACL']['id'])) {
                    return json_encode(array('success' => __('Affiliate media\'s public access removed.')));
                }
            }
        }
        return json_encode(array('error' => __('Action failed')));
    }
    
    /**
     * Sends media obj to client
     * @param {int} $media_id
     */
    public function create($media_id) {
        $this->layout = 'ajax';
    
        if(empty($media_id)) return false;
        
        
        $media = $this->AffiliateMedia->getItem($media_id);
                        
        if(!$this->Cookie->read('affiliateImpression')) { 
            if(!empty($media)) {            
                $media['AffiliateMedia']['impressions'] = intval($media['AffiliateMedia']['impressions']) + 1;

                // save changes
                $this->AffiliateMedia->save($media); 
                $this->Cookie->write('affiliateImpression', '1', false, 3600);
            }                       
        }
        // tmp
        $this->set('path', Router::url('/', true) . 'img/banners/' . $media['AffiliateMedia']['file_path']);
        $this->set('url', Router::url('/', true) . 'AffiliateMedia/handler/' . $media['AffiliateMedia']['id']);
    }
    
    /**
     * Media click handler
     * @param {int} $media_id
     */
    public function handler($media_id) {        
        $this->autoRender = false;
        
        $media = $this->AffiliateMedia->getItem($media_id);
                                
        if(!$this->Cookie->read('click')) {     
            if(!empty($media)) {
                $media['AffiliateMedia']['clicks'] = intval($media['AffiliateMedia']['clicks']) + 1;
            
                // save changes
                $this->AffiliateMedia->save($media); 
                $this->Cookie->write('click', $media_id, false, 3600);
            }                     
        } 
        
        $this->redirect('/');
    }
    
    public function link() {  
        $this->layout = 'affiliate';
        $affiliate = $this->Affiliate->getItem($this->Session->read('Affiliate_id'), -1);
        
        if(!empty($affiliate)) {
            $this->set('code', '<a target="_blank" href="' . Router::url('/', true) . '?a=' .$affiliate['Affiliate']['affiliate_custom_id'] . '">' . Configure::read('Settings.defaultTitle') . '</a>');
        }
    }
}