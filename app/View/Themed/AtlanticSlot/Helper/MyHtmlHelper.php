<?php

App::uses('HtmlHelper', 'View/Helper');

/**
 * Class MyHtmlHelper
 */
class MyHtmlHelper extends HtmlHelper {
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'MyHtml';

    /**
     * ACOS config
     *
     * @var array
     */
    public $acos = array();

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array(
        0   =>  'Html',
        1   =>  'Session'
    );

    /**
     * Builds custom link
     *
     * @param $name
     * @param $url
     * @return mixed
     */
    function customLink($name, $url) {
        if (preg_match('/^http:/', $url)) {
            return $this->Html->link($name, $url);
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->Html->link($name, array('controller' => $parts[0], 'action' => $parts[1], $parts[2]));
        } else {
            return $this->Html->link($name, array('controller' => 'pages', 'action' => $url));
        }
    }

    /**
     * Builds custom url
     *
     * @param $url
     * @return mixed
     */
    function customUrl($url) {
        if (preg_match('/^http:/', $url)) {
            return $url;
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->Html->url(array('controller' => $parts[0], 'action' => $parts[1], $parts[2]), true);
        } else {
            return $this->Html->url(array('controller' => 'pages', 'action' => $url), true);
        }
    }

    /**
     * Creates link
     *
     * @param string $title
     * @param null $url
     * @param array $options
     * @param bool $confirmMessage
     * @return string
     */
    function link($title, $url = null, $options = array(), $confirmMessage = false) {
      
        $acos = $this->checkAcl($url);

        if ($acos) {
            $link = parent::link($title, $url, $options, $confirmMessage);
        } else if (isset($options['returnText'])) {
            $link = $title;
        } else {
            $link = "";
        }

        return $link;
    }

    /**
     * @param $title
     * @param null $url
     * @param array $options
     * @param bool $confirmMessage
     * @return string
     */
    function spanLink($title, $url = null, $options = array(), $confirmMessage = false) {
        $options['escape'] = false;
        return parent::link('<span>' . $title . '</span>', $url, $options, $confirmMessage);
    }

    /**
     * Checks ACL by Url
     *
     * @param null $url
     * @return bool
     */
    function checkAcl($url = NULL) {
        $permissions = $this->Session->read('permissions');
        
        if (isset($permissions['controllers'])) {
            return true;
        }

        $aco = 'controllers/' . strtolower($url['controller']);
        if (isset($permissions[$aco])) {
            return true;
        }

        if (isset($url['action'])) {
            if(isset($this->request->params['prefix'])) {
                $aco .= '/' . $this->request->params['prefix'] . '_' . strtolower($url['action']);
            }else{
                return true;
            }

            if (isset($permissions[$aco])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks ACL by multiple url
     *
     * @param array $urls
     * @return bool
     */
    function checkAcls($urls = array()) {
        foreach ($urls as $url) {
            $u = array('controller' => $url[0], 'action' => $url[1]);
            if ($this->checkAcl($u)) {
                return true;
            }
        }
        return false;
    }
}