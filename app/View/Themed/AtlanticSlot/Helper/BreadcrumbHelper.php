<?php


App::uses('AppHelper', 'View/Helper');

class BreadcrumbHelper extends AppHelper {

    /**
     * Builds breadcrumb from path params
     * @param array $titles - breadcrumb titles is assigned by array keys
     * @param bool $reverse - return breadcrumb in reverse order
     * @param int $maxIterations - max possible breadcrumb count
     * @return array
     */

    public function buildFromURI($titles = array(), $reverse = true, $maxIterations = 2) {

        $uri = $this->request->here;
        $actions = array_filter(explode('/', $uri));
        
        $breadcrumbs = array();

        $iterator = 0;
        $title = '';

        if($reverse == true) {
            $titles = array_reverse($titles);
        }

        while(!empty($actions)) {
            if(isset($titles[$iterator])) $title = $titles[$iterator];

            /** Drop action values */
            
            /** TODO: calculate and drop diff, not the last */
            if($iterator > 0 && $iterator < $maxIterations) {
                if(count($actions) > $maxIterations) {
                    $lastElement = array_search(end($actions), $actions);
                    if(isset($actions[$lastElement])) {
                        unset($actions[$lastElement]);
                    }
                }
            }

            $breadcrumbs[] = array(
                'title' =>  __($title),
                'url'   =>  '/' . implode('/', $actions)
            );
            

            array_pop($actions);

            if($iterator >= $maxIterations)
                break;
            
            $iterator++;
            $title = '';
        }
        
        if($reverse == true) {
            $breadcrumbs = array_reverse($breadcrumbs);
        }
        foreach ($breadcrumbs as $breadcrumb) {
            $breadcrumb = $this->request->referer();
        }
        return $breadcrumbs;

    }

    
    
    public function getbackurl($refferer){

        $browserhistory = CakeSession::read('Admin.browseHistory');
        $key = array_search($this->curPageURL(), $browserhistory);
       
        if ($key){
            $browserhistory = array_slice($browserhistory, 0,$key);
        }else{
            if (!in_array($this->request->referer(),$browserhistory)){
                $browserhistory[]=$this->request->referer();
            }
        }

        CakeSession::write('Admin.browseHistory', $browserhistory);

        return $browserhistory[count($browserhistory)-1];
    }
    
    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
         $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
         $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
     }
}