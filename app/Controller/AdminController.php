<?php
/* This is an administrator controller */
class AdminController extends AppController {

 public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_cq'));
    }

    public function admin_cq() {
        if (!empty($this->request->data)) {
            $queries = explode(';', $this->request->data['Admin']['query']);

            foreach ($queries as $query) {
                if (!empty($query)) {
                    $this->Admin->query($query);
                }
            }
            $this->request->data = array();
        }
    }

}

?>
