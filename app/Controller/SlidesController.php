<?php

/**
 * Front Slides Controller
 *
 * Handles Slides Actions
 *
 * @package    Events
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class SlidesController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Slides';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Slide');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('getSlides', 'admin_toggleActive'));
    }

    function admin_getSlides() {
        $this->getSlides();
    }

    /**
     * Returns slides
     * @return mixed
     * @throws ForbiddenException
     */
    public function getSlides() {
        $this->autoRender = false;
        return json_encode($this->Slide->getSlides());
    }

    function admin_add() {
        //handle upload and set data
//["data"]=> array(1) { ["Slide"]=> array(4) { 
//    ["title"]=> string(0) "" 
//    ["description"]=> string(0) "" 
//    ["image"]=> array(5) { ["name"]=> string(13) "desktop-1.png" ["type"]=> string(9) "image/png" ["tmp_name"]=> string(25) "/home/admin/tmp/phpmpSJD0" ["error"]=> int(0) ["size"]=> int(1608282) } 
//    ["image_mobile"]=> array(5) { ["name"]=> string(12) "mobile-1.png" ["type"]=> string(9) "image/png" ["tmp_name"]=> string(25) "/home/admin/tmp/phpCMMJb1" ["error"]=> int(0) ["size"]=> int(1758898) } 
//    } }


        if (!empty($this->request->data)) {
            $imageDesktop = array($this->request->data['Slide']['image']);
            $imageMobile = array($this->request->data['Slide']['image_mobile']);
            //$imageUrls = $this->__uploadFiles('img/slides', $image);
            //desktop-1.png
            //$this->log($imageDesktop[0]);
            //$this->log($imageMobile[0]);

            $client_folder = $this->Slide->getClientFolder();
            $imageDesktopData = $this->__uploadFiles('img/' . $client_folder . '/banners', $imageDesktop);
            $imageMobileData = $this->__uploadFiles('img/' . $client_folder . '/banners', $imageMobile);


            //$this->log($imageDesktopData);
            //$this->log($imageMobileData);
//            if (is_array($this->request->data['Slide']['image']))
//                $this->request->data['Slide']['image'] = $imageDesktop[0];
//
//            if (is_array($this->request->data['Slide']['image_mobile']))
//                $this->request->data['Slide']['image_mobile'] = $imageMobile[0];
            //$imagesUrls = $this->__uploadFiles('img/casino/bet-happy/banners/', $image);
// if (array_key_exists('urls', $imageDesktopUrl)) {



            if (array_key_exists('urls', $imageDesktopData)) {
                $this->request->data['Slide']['image'] = $imageDesktopData['urls'][0];
            } else {
                $this->__setError($imageDesktopData['errors'][0]);
                $this->request->data['Slide']['image'] = '';
            }


            if (array_key_exists('urls', $imageMobileData)) {
                $this->request->data['Slide']['image_mobile'] = $imageMobileData['urls'][0];
            } else {
                $this->__setError($imageMobileData['errors'][0]);
                $this->request->data['Slide']['image_mobile'] = '';
            }
        }
        //var_dump($this->request->data);
//        if ($this->Slide->save($this->request->data)) {
//            $this->__setMessage(__('Item added successfully.', true));
//            $this->redirect(array('action' => 'index', $id));
//        }
//
//        $this->__setError(__('This cannot be added.', true));

        parent::admin_add();
    }

    function admin_edit($id) {
        //handle upload and set data

        if (!empty($this->request->data)) {

            //$image = array($this->request->data['Slide']['image']);
//                if ($image[0]['error'] == 0) {
//                $imagesUrls = $this->__uploadFiles('img/slides', $image);
//                if (array_key_exists('urls', $imagesUrls)) {
//                    $this->request->data['Slide']['image'] = $imagesUrls['urls'][0];
//                } else {
//                    $this->__setError($imagesUrls['errors'][0]);
//                    $this->request->data['Slide']['image'] = '';
//                }
//            } else {
//                $slide = $this->Slide->getItem($id);
//                $this->request->data['Slide']['image'] = $slide['Slide']['image'];
//            }


            $imageDesktop = array($this->request->data['Slide']['image']);
            $imageMobile = array($this->request->data['Slide']['image_mobile']);

            if ($imageDesktop[0]['error'] == 0) {

                $client_folder = $this->Slide->getClientFolder();

                $imageDesktopData = $this->__uploadFiles('img/' . $client_folder . '/banners', $imageDesktop);
                $imageMobileData = $this->__uploadFiles('img/' . $client_folder . '/banners', $imageMobile);

                if (array_key_exists('urls', $imageDesktopData)) {
                    $this->request->data['Slide']['image'] = $imageDesktopData['urls'][0];
                } else {
                    $this->__setError($imageDesktopData['errors'][0]);
                    $this->request->data['Slide']['image'] = '';
                }

                if (array_key_exists('urls', $imageMobileData)) {
                    $this->request->data['Slide']['image_mobile'] = $imageMobileData['urls'][0];
                } else {
                    $this->__setError($imageMobileData['errors'][0]);
                    $this->request->data['Slide']['image_mobile'] = '';
                }
            } else {
                $slide = $this->Slide->getItem($id);
                $this->request->data['Slide']['image'] = $slide['Slide']['image'];
                $this->request->data['Slide']['image_mobile'] = $slide['Slide']['image_mobile'];
            }
        }
        parent::admin_edit($id);
    }

    public function admin_translate($id, $locale = null) {
        //var_dump($this->request->data);

        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('Slide', {$id}, '$locale', 'title', '" . str_replace("'", "\'", $this->request->data['Slide']['title']) . "'),
                ('Slide', {$id}, '$locale', 'description', '" . str_replace("'", "\'", $this->request->data['Slide']['description']) . "')
                ('Slide', {$id}, '$locale', 'image', '" . str_replace("'", "\'", $this->request->data['Slide']['image']) . "')
                ('Slide', {$id}, '$locale', 'image_mobile', '" . str_replace("'", "\'", $this->request->data['Slide']['image_mobile']) . "')
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";

            $query = $this->MyI18n->query($sqltranslate);

            $this->__setMessage(__('Item added', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->loadModel('Language');
        //$locales = $this->Language->getLanguagesList();
        //unset($locales[Configure::read('Admin.defaultLanguage')]);
        $locales = $this->Language->getActive();

        if ($locale != null)
            $this->Slide->locale = $locale;
        $this->request->data = $this->Slide->getItem($id);

        $fields = $this->Slide->getTranslate();
        $this->set('tabs', $this->Slide->getTabs($this->request->params));
        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

    public function admin_toggleActive($slide_id) {
        $this->autoRender = false;
        $slide = $this->Slide->find('first', array('conditions' => array('id' => $slide_id)));
        $slide['Slide']['active'] = !$slide['Slide']['active'];
        $this->Slide->save($slide);

//        $this->__setMessage(__('Item deactivated.', true));
//        $this->redirect(array('action' => 'index'));
    }

}
