<?php

class SlotGames extends MrSlottyAppModel {
    
    /**
     * Model name
     * @var type 
     */
    public $name = 'SlotGames';
    
    /**
     * DB talbe name
     * @var type 
     */
    public $useTable = 'mrslotty_games';
    
    public $primaryKey = 'alias';
    
    /**
     * Table fields
     * @var type 
     */
    protected $_schema = array(
        'alias' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'brand' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'icon' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'gameid' => array(
            'type' => 'varchar',
            'null' => false,
            'length' => 255
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'thumbnails' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 255
        ),
    );
    
    public function getGames() {
        $slotModel = ClassRegistry::init('MrSlotty.Slot');
        
        $data = array();
        $slotgames = $slotModel->getGames();
        
        foreach ($slotgames as &$game) {
            $this->addGame(json_encode($game));
        }
        return json_encode(array('success' => true));
    }
    
    public function getGameIds() {
        $slotModel = ClassRegistry::init('MrSlotty.Slot');
        
        $games = array();
        $data = $slotModel->getGames();
        
        foreach ($data as &$game) {
            if ($gameexists = $this->gameExists($game->id)) {
                $games[$gameexists['SlotGames']['gameid']] = $gameexists['SlotGames']['name'];
            } else {
                if ($added = $this->addGame(json_encode($game))) $games[$added['SlotGames']['gameid']] = $added['SlotGames']['name'];
            }
        }
        return $games;
    }
    
    public function getByGameid($code) {
        return $this->find('first', array('conditions' => array('gameid' => $code)));
    }
    
    public function gameExists($id) {
        $game = $this->find('first', array('conditions' => array('gameid' => $id)));
        if ($game) return $game;
        return false;
    }
    
    public function addGame($game) {        
        $game = json_decode($game);

        $data['alias']  = $game->alias;
        $data['brand']  = $game->brand;
        $data['icon']   = $this->saveGameThumbs("http:".$game->media->icon);
        $data['gameid'] = $game->id;
        $data['name']   = $game->name;
        $data['thumbnails']   = serialize($game->media->thumbnails);
        
        $this->create();
        if ($saved = $this->save($data)) return $saved;
        return false;
    }
    
    public function saveGameThumbs($preview) {
        $imgname = end(explode('/', $preview));
        
        $startPath = $this->config['Paths']['images'];
        $finalPath = $startPath."/".$imgname;
        
        $imgContent = file_get_contents($preview);
        
        $fp = fopen($startPath, "w");
        fwrite($fp, $imgContent);
        fclose($fp);
        file_put_contents($finalPath, $imgContent);
        
        return $imgname;
    }
    
    public function getHeaders() {
        return array(
            'id'        => __('ID'), 
            'alias'     => __('Alias'), 
            'brand'     => __('Brand'), 
            'icon'      => __('Icon'), 
            'gameid'    => __('Game ID'), 
            'name'      => __('Name'),
            'active'    => __('Active')
        );
    }
    
    public function getIntGames() {
        $entries = $this->query("select name, gameid as source_id, icon as image from mrslotty_games as SlotGames");
        $data = array();
        foreach ($entries as $row) {
            $row = $row['SlotGames'];
            $row['lines']       = null;
            $row['reels']       = null;
            $row['freespins']   = null;
            $row['mobile']      = 0;
            $row['desktop']     = 0;
            $row['source']      = $this->plugin.'.'.$this->name;
            $data[] = $row;
        }
        return $data;
    }
    
    public function getGameUrl($id, $fun = false) {
        if ($id) {
            $userid = CakeSession::read('Auth.User.id');
            if (!$userid && !$fun) return array('response' => false, 'message' => __('Please login first.'));
            
            $html = "/mr_slotty/slot/game/".$id."/".$fun;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="'.$html.'"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }
}