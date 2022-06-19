<?php

App::uses('AppModel', 'Model');

class PlatipusAppModel extends AppModel {

    function __construct() {

        parent::__construct($id, $table, $ds);
        $this->plugin = 'Platipus';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Platipus = ClassRegistry::init('Platipus.Platipus');
        $this->PlatipusLogs = ClassRegistry::init('Platipus.PlatipusLogs');
        $this->PlatipusGames = ClassRegistry::init('Platipus.PlatipusGames');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrands = ClassRegistry::init('IntGames.IntBrands');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');

        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');

        $this->ProviderGames = '[
    {
    "TITLE": "Chinese Tigers",
    "GAME_ID": 530,
    "LAUNCH_ID": "chinesetigers",
    "BET_LINES": 30,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
    {
    "TITLE": "Rhino Mania",
    "GAME_ID": 528,
    "LAUNCH_ID": "rhinomania",
    "BET_LINES": 4096,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  }, 
  {
    "TITLE": "Webby Heroes",
    "GAME_ID": 526,
    "LAUNCH_ID": "webbyheroes",
    "BET_LINES": 30,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },   
{
    "TITLE": "Lucky Cat",
    "GAME_ID": 527,
    "LAUNCH_ID": "luckycat",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
    {
    "TITLE": "Pharaohs Empire",
    "GAME_ID": 492,
    "LAUNCH_ID": "pharaohsempire",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
    {
    "TITLE": "Bison Trail",
    "GAME_ID": 491,
    "LAUNCH_ID": "bisontrail",
    "BET_LINES": 1024,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
    {
    "TITLE": "Jade Valley",
    "GAME_ID": 485,
    "LAUNCH_ID": "jadevalley",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Neon Classic",
    "GAME_ID": 488,
    "LAUNCH_ID": "neonclassic",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Power of Poseidon",
    "GAME_ID": 452,
    "LAUNCH_ID": "powerofposeidon",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Great Ocean",
    "GAME_ID": 486,
    "LAUNCH_ID": "greatocean",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Mega Drago",
    "GAME_ID": 450,
    "LAUNCH_ID": "megadrago",
    "BET_LINES": 30,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Legend of Atlantis",
    "GAME_ID": 442,
    "LAUNCH_ID": "legendofatlantis",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Aztec Temple",
    "GAME_ID": 448,
    "LAUNCH_ID": "aztectemple",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Triple Dragon",
    "GAME_ID": 444,
    "LAUNCH_ID": "tripledragon",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Book of Egypt",
    "GAME_ID": 465,
    "LAUNCH_ID": "bookofegypt",
    "BET_LINES": 10,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Monkey\'s Journey",
    "GAME_ID": 480,
    "LAUNCH_ID": "monkeysjourney",
    "BET_LINES": 40,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Sakura Wind",
    "GAME_ID": 469,
    "LAUNCH_ID": "sakuragarden",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Lucky Money",
    "GAME_ID": 475,
    "LAUNCH_ID": "luckymoney",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Love is",
    "GAME_ID": 476,
    "LAUNCH_ID": "loveis",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Cinderella",
    "GAME_ID": 477,
    "LAUNCH_ID": "cinderella",
    "BET_LINES": 40,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Crystal Sevens",
    "GAME_ID": 425,
    "LAUNCH_ID": "crystalsevens",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Magical Mirror",
    "GAME_ID": 446,
    "LAUNCH_ID": "magicalmirror",
    "BET_LINES": 15,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Power of Gods",
    "GAME_ID": 483,
    "LAUNCH_ID": "powerofgods",
    "BET_LINES": 40,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Crazy Jelly",
    "GAME_ID": 443,
    "LAUNCH_ID": "crazyjelly",
    "BET_LINES": 10,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Lucky Dolphin",
    "GAME_ID": 427,
    "LAUNCH_ID": "luckydolphin",
    "BET_LINES": 15,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Richy Witchy",
    "GAME_ID": 429,
    "LAUNCH_ID": "richywitchy",
    "BET_LINES": 40,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Magical Wolf",
    "GAME_ID": 394,
    "LAUNCH_ID": "magicalwolf",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Mistress of Amazon",
    "GAME_ID": 393,
    "LAUNCH_ID": "amazonqueen",
    "BET_LINES": 40,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Arabian Tales",
    "GAME_ID": 417,
    "LAUNCH_ID": "arabiantales",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Jungle Spin",
    "GAME_ID": 423,
    "LAUNCH_ID": "junglespin",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Fruity Sevens",
    "GAME_ID": 424,
    "LAUNCH_ID": "fruitysevens",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Juicy Spins",
    "GAME_ID": 428,
    "LAUNCH_ID": "juicyspins",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Crocoman",
    "GAME_ID": 409,
    "LAUNCH_ID": "crocoman",
    "BET_LINES": 10,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Safari Adventures",
    "GAME_ID": 426,
    "LAUNCH_ID": "safariadventures",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Fairy Forest",
    "GAME_ID": 400,
    "LAUNCH_ID": "fairyforest",
    "BET_LINES": 50,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Princess of Birds",
    "GAME_ID": 401,
    "LAUNCH_ID": "princessofbirds",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Jewel Bang",
    "GAME_ID": 395,
    "LAUNCH_ID": "jewelblast",
    "BET_LINES": 10,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Fiery Planet",
    "GAME_ID": 392,
    "LAUNCH_ID": "spacequest",
    "BET_LINES": 25,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Cleo\'s Gold",
    "GAME_ID": 386,
    "LAUNCH_ID": "egyptiangold",
    "BET_LINES": 20,
    "TYPE": "HTML5",
    "CATEGORY": "Video Slot"
  },
  {
    "TITLE": "Baccarat VIP",
    "GAME_ID": 490,
    "LAUNCH_ID": "baccaratvip",
    "BET_LINES": "",
    "TYPE": "",
    "CATEGORY": "Table Game"
  },
  {
    "TITLE": "Baccarat Mini",
    "GAME_ID": 489,
    "LAUNCH_ID": "baccaratmini",
    "BET_LINES": "",
    "TYPE": "",
    "CATEGORY": "Table Game"
  },
  {
    "TITLE": "Blackjack",
    "GAME_ID": 93,
    "LAUNCH_ID": "blackjack",
    "BET_LINES": "",
    "TYPE": "",
    "CATEGORY": "Table Game"
  },
  {
    "TITLE": "Blackjack Vip",
    "GAME_ID": 487,
    "LAUNCH_ID": "blackjackvip",
    "BET_LINES": "",
    "TYPE": "",
    "CATEGORY": "Table Game"
  }
]';
    }

}
