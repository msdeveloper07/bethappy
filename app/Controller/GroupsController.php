<?php
/**
 * Handles Groups
 *
 * Handles Groups Actions
 *
 * @package    Groups
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class GroupsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Groups';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Permission');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('*');
    }

    public function parentNode() {
        return null;
    }

    function admin_edit($id) {
        $options['conditions'] = array(
            'Aro.foreign_key' => $id
        );
        $options['recursive'] = -1;
        $aro = $this->Acl->Aro->find('first', $options);
        $aroId = $aro['Aro']['id'];
        if (!empty($this->request->data)) {
            foreach ($this->request->data['Group'] as $acoId => $value) {
                $options['conditions'] = array(
                    'Permission.aro_id' => $aroId,
                    'Permission.aco_id' => $acoId
                );
                $permission = $this->Permission->find('first', $options);
                if ($value == 1) {
                    $permission['Permission']['aro_id'] = $aroId;
                    $permission['Permission']['aco_id'] = $acoId;
                    $permission['Permission']['_create'] = 1;
                    $permission['Permission']['_update'] = 1;
                    $permission['Permission']['_read'] = 1;
                    $permission['Permission']['_delete'] = 1;
                    $this->Permission->create();
                    $this->Permission->save($permission);
                } else if (!empty($permission)) { //delete                    
                    $this->Permission->delete($permission['Permission']['id']);
                }
            }
            $this->__setMessage(__('Permissions updated', true));
        }

        $options['conditions'] = array(
            'Aco.parent_id' => 1
        );
        $options['recursive'] = -1;
        $acos = $this->Acl->Aco->find('all', $options);
        foreach ($acos as $key => $value) {
            $options['conditions'] = array(
                'Aco.parent_id' => $value['Aco']['id']
            );
            $acos[$key]['childs'] = $this->Acl->Aco->find('all', $options);
        }
        //get acos-aros
        $options['conditions'] = array(
            'Permission.aro_id' => $aroId
        );
        $options['fields'] = array(
            'Permission.aco_id',
            'Permission._read'
        );
        $acosAros = $this->Permission->find('list', $options);
        $this->request->data['Group'] = $acosAros;

        $this->set('acos', $acos);
        $this->set('id', $id);
    }

    function admin_addAco($controller = NULL, $action = NULL) {
        if ($controller == NULL)
            die;

        $aco = & $this->Acl->Aco;

        $root = $aco->node('controllers');
        $root = $root[0];
        //create controller
        $methodNode = $aco->node('controllers/' . $controller);
        if (!$methodNode) {
            $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $controller));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for ' . $controller;
        } else {
            $root['Aco']['id'] = $methodNode[0]['Aco']['id'];
        }
        if ($action == NULL)
            die;
        //create method
        $methodNode = $aco->node('controllers/' . $controller . '/' . $action);
        if (!$methodNode) {
            $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $action));
            $methodNode = $aco->save();
            $log[] = 'Created Aco node for ' . $action;
        }
        debug($log);
        die;
    }

    function admin_build_acl() {
        $this->build_acl();
    }

    function build_acl() {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }
        $log = array();

        $aco = & $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }

        //App::uses('File', 'Utility');
        $Controllers = App::objects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'build_acl';

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($ctrlName);

            // find / make controller node
            $ctrlName = str_replace('Controller', '', $ctrlName);
            $controllerNode = $aco->node('controllers/' . $ctrlName);
            if (!$controllerNode) {
                $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                $controllerNode = $aco->save();
                $controllerNode['Aco']['id'] = $aco->id;
                $log[] = 'Created Aco node for ' . $ctrlName;
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/' . $ctrlName . '/' . $method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for ' . $method;
                }
            }
        }
        if (count($log) > 0) {
            debug($log);
        }
        exit;
    }

    function _getClassMethods($ctrlName = null) {
        $ctrlclass = $ctrlName;
        debug($ctrlclass);
      
        App::uses($ctrlName, 'Controller');
        $methods = get_class_methods($ctrlclass);
        // Add scaffold defaults if scaffolds are being used

        $properties = get_class_vars($ctrlclass);

        return $methods;
        return array();
    }
}