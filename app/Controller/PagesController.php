<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $name = 'Pages';
    public $uses = array('Page');

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('admin_index', 'admin_toggleActive', 'display', 'main', 'printpage', 'index', 'getPageJson', 'contactUs'));
    }

    /**
     * Displays a view
     *
     * @return CakeResponse|null
     * @throws ForbiddenException When a directory traversal attempt.
     * @throws NotFoundException When the view file could not be found
     *   or MissingViewException in debug mode.
     */
//	public function display() {
//		$path = func_get_args();
//
//		$count = count($path);
//		if (!$count) {
//			return $this->redirect('/');
//		}
//		if (in_array('..', $path, true) || in_array('.', $path, true)) {
//			throw new ForbiddenException();
//		}
//		$page = $subpage = $title_for_layout = null;
//
//		if (!empty($path[0])) {
//			$page = $path[0];
//		}
//		if (!empty($path[1])) {
//			$subpage = $path[1];
//		}
//		if (!empty($path[$count - 1])) {
//			$title_for_layout = Inflector::humanize($path[$count - 1]);
//		}
//		$this->set(compact('page', 'subpage', 'title_for_layout'));
//
//		try {
//			$this->render(implode('/', $path));
//		} catch (MissingViewException $e) {
//			if (Configure::read('debug')) {
//				throw $e;
//			}
//			throw new NotFoundException();
//		}
//	}
//    public function admin_index() {
//        $this->set('data', $this->paginate());
//        $this->set('actions', $this->Page->getActions());
//    }
//    public function admin_translate($id, $locale = null) {
//        //
//        if ($locale != null)
//            $this->Page->locale = $locale;
//
//        var_dump($locale);
//        if (!empty($this->request->data)) {
//            $this->loadModel('MyI18n');
//            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
//                ('Page', {$id}, '{$locale}', 'title', '" . str_replace("'", "\'", $this->request->data['Page']['title']) . "'),
//                ('Page', {$id}, '{$locale}', 'description', '" . str_replace("'", "\'", $this->request->data['Page']['description']) . "'),
//                ('Page', {$id}, '{$locale}', 'content', '" . str_replace("'", "\'", $this->request->data['Page']['content']) . "'),
//                ('Page', {$id}, '{$locale}', 'keywords', '" . str_replace("'", "\'", $this->request->data['Page']['keywords']) . "')
//                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";
//
//            var_dump($sqltranslate);
//            //$query = $this->MyI18n->query($sqltranslate);
//            //$this->__setMessage(__('Item added', true));
//            //$this->redirect(array('action' => 'index'));
//        }
//
//        $this->loadModel('Language');
//        //$locales = $this->Language->getLanguagesList();
//        //unset($locales[Configure::read('Admin.defaultLanguage')]);
//
//        $locales = $this->Language->getActive();
//
//        
//        $this->request->data = $this->Page->getItem($id);
//        //var_dump($this->request->data);
//        $fields = $this->Page->getTranslate();
//
//        $this->set('tabs', $this->Page->getTabs($this->request->params));
//        $this->set('currentid', $id);
//        $this->set('currentlocale', $locale);
//        $this->set('model', $model);
//        $this->set('locales', $locales);
//        $this->set('fields', $fields);
//    }

    public function main() {
        $this->layout = 'new';

        $this->set('news', $this->News->getNews());
        $this->set('slides', $this->requestAction(array('plugin' => null, 'controller' => 'slides', 'action' => 'getSlides')));

        //$this->redirect(array('controller' => 'pages', 'action' => 'index'));
    }

    public function index() {
        $this->layout = 'index';
        $this->set('news', $this->News->getNews());
        $this->set('slides', $this->requestAction(array('plugin' => null, 'controller' => 'slides', 'action' => 'getSlides')));
    }

    /**
     * Displays page content
     * @param string $url
     * @return void
     */
    public function display($url = 'main') {
        $show_slider = 0;
        $showLastMinuteBets = 0;
        $showNews = 0;
        if ($url == 'main') {
            $show_slider = 1;
            $showLastMinuteBets = 1;
            $showNews = 1;
        }

        $this->Page->locale = Configure::read('Config.language');               //Translation for Model

        $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));

        if (empty($page)) {
            $this->Page->locale = 'en_us';
            $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
        }

        //fallback to main page
        if (empty($page)) {
            $show_slider = 1;
            $url = 'main';
            $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
            if (empty($page)) {
                $this->Page->locale = 'en_us';
                $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
            }
        }

        $title = $page['Page']['title'];
        $title_for_layout = $title;
        $content = $page['Page']['content'];
        $keywords = $page['Page']['keywords'];
        $description = $page['Page']['description'];

        $this->set(compact('showNews', 'description', 'keywords', 'title', 'content', 'title_for_layout', 'show_slider', 'showLastMinuteBets'));
    }

    //public function getpage($url = 'main') {
    public function getPageJson($url = 'main') {
        $this->autoRender = false;
        //var_dump($url);
        $this->loadModel('MyI18n');

        $this->Page->locale = (!empty(Configure::read('Config.language')) ? Configure::read('Config.language') : 'en_us');
        $page = $this->Page->find('first', array('conditions' => array('Page.url LIKE' => '%'.$url . '%', 'Page.active' => 1)));

        //$locale = (!empty(Configure::read('Config.language')) ? Configure::read('Config.language') : 'en_us');
        //$translated_page = $this->MyI18n->find('first', array('conditions' => array('model' => 'Page', 'locale' => $locale, 'foreign_key' => $page['Page']['id'])));
        //var_dump($translated_page);
        //var_dump($page);
        $title = $page['Page']['title'];
        $content = $page['Page']['content'];
        $keywords = $page['Page']['keywords'];
        $description = $page['Page']['description'];

        return json_encode(array('status' => 'success', 'data' => array('title' => $title, 'content' => $content)));
    }

    public function printpage($url) {
        $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
        if (empty($page)) {
            $this->Page->locale = 'en_us';
            $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
        }
        $title = $page['Page']['title'];
        $content = $page['Page']['content'];
        //in case of error try strip_tags

        $pdfFile = TMP . 'pages' . DS . 'pdf' . DS . $title . '.pdf';
        $pdf = new File($pdfFile);

        App::import('Vendor', 'dompdf', array('file' => 'dompdf/dompdf_config.inc.php'));

        $domPdf = new DOMPDF();
        $domPdf->set_paper("a3");
        $domPdf->load_html($content);
        $domPdf->render();
        $pdf->write($domPdf->output());
        header('Content-type: application/pdf');
        @readfile($pdfFile);
        exit;
    }

    public function admin_toggleActive($page_id) {
        $this->autoRender = false;
        $page = $this->Page->find('first', array('conditions' => array('Page.id' => $page_id)));
        $page['Page']['active'] = !$page['Page']['active'];
        $this->Page->save($page);
    }

}
