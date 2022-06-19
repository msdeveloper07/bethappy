<?php

App::uses('AppModel', 'Model');

class SpinomenalAppModel extends AppModel {

    function __construct() {

        parent::__construct($id, $table, $ds);
        $this->plugin = 'Spinomenal';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Spinomenal = ClassRegistry::init('Spinomenal.Spinomenal');
        $this->SpinomenalLogs = ClassRegistry::init('Spinomenal.SpinomenalLogs');
        $this->SpinomenalGames = ClassRegistry::init('Spinomenal.SpinomenalGames');


        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');

        $this->ProviderGames = '[{
    "GameName": "Divine Forest",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_DivineForest",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 1
  },
  {
    "GameName": "Demi Gods II",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_DemiGods2",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 1
  },
  {
    "GameName": "Lilith\'s Passion",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_LilithPassion",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 1
  },
  {
    "GameName": "Hunting Treasures",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_HuntingTreasures",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 1
  },
  {
    "GameName": "Book Of Guardians",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_BookOfGuardians",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "Tiki Rainbow",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_TikiRainbow",
    "Lines": 20,
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "Hunting Treasures Deluxe",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_HuntingTreasuresDeluxe",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "Wild Heist",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_WildHeist",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "8 Lucky Charms Xtreme",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_8LuckyCharmsXtreme",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "Snowing Luck",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SnowingLuck",
    "Lines": 30,
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "European Roulette",
    "GameType": "Table Games",
    "GameCode": "Table_EuropeanRoulette",
    "Lines": 1,
    "FreeSpins": "No",
    "Order": 2
  },
  {
    "GameName": "Reviving Love",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_RevivingLove",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 2
  },
  {
    "GameName": "Lotus Kingdom",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_LotusKingdom",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "SlotNRoll",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SlotNRoll",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Chest Of Fortunes",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ChestOfFortunes",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Abundance Spell",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_AbundanceSpell",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Nights Of Fortune",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_NightsOfFortune",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Golden Dynasty",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_GoldenDynasty",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Very Big Goat",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_VeryBigGoats",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "88 Lucky Charms",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_88LuckyCharms",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Greedy servants",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_GreedyServants",
    "Lines": 30,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Fortune Keepers",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_FortuneKeepers",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "8 Lucky Charms",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_8LuckyCharms",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "Egyptian Rebirth",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_EgyptianRebirth",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 3
  },
  {
    "GameName": "4 Winning Directions",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_4WinningDirections",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Wild wild spin",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_WildWildSpin",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Terracota Wilds",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_TerracotaWilds",
    "Lines": 100,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Samurai\'s Path",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SamuraiPath",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Surprising 7",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_Surprising7",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Exploding Pirates",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ExplodingPirates",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Atlantic Treasures",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_AtlanticTreasures",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Demi Gods",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_DemiGods",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 4
  },
  {
    "GameName": "Monsters\' Scratch",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_MonsterScratch",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "May Dance Festival",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_MayDanceFestival",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Shogun bots",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ShogunBots",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Forbidden slot",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ForbiddenSlot",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Reel circus",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ReelCircus",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Steaming reels",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SteamingReels",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Strip to win",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_StripToWin",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Precious treasures",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_PreciousTreasures",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Reel Fighters",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ReelFighters",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Code Name: Jackpot",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_CodeNameJackpot",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Farm of fun",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_FarmOfFun",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Santa Wild Helpers",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SantaWildHelpers",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Irish Charm",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_IrishCharms",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 5
  },
  {
    "GameName": "Scratchy Bit",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_ScratchyBit",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Signs Of Fortune",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_SignsOfFortune",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Super Mask",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_SuperMask",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Fluffy Slot",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_FluffySlot",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Tasty Win",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_TastyWin",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Donut Rush",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_DonutsRush",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Empires Warlords",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_EmpiresWarlords",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Peony Ladies",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_PeonyLadies",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Wealth of monkeys",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_WealthOfTheMonkey",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Loot a fruit",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_LootAFruit",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Pond of Koi",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_PondOfKoi",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Royal win",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_RoyalWin",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Egyptian adventure",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_EgyptianAdventure",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Slot bound",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SlotBound",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Gods Of Slots",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_GodsOfSlots",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Jade Connection",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_JadeConnection",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Slotsaurus",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_Slotosaurus",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Year of the monkey",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_YearOfTheMonkey",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Zombie slot mania",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ZombieSlotMania",
    "Lines": 50,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Bikers Gang",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_BikersGang",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Undying Passion",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_UndyingPassion",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 6
  },
  {
    "GameName": "Secret Cupcakes",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_SecretCupcakes",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Wish list",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_WishList",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Diner of fortune",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_DinerOfFortune",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Live slot",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_LiveSlot",
    "Lines": 15,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Scattered to hell",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ScatteredToHell",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Wacky monsters",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_WackyMonsters",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Blazing Tires",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_BlazingTires",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Cats gone wild",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_CatsGoneWild",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Master Panda",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_MasterPanda",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Soccer babes",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SoccerBabes",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Gangster\'s slot",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_GangsterSlots",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Viking\'s Glory",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_VikingsGlory",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "9 Figures Club",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_9FiguresClub",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Candy slot twins",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_CandySlotTwins",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Year of luck",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_YearOfLuck",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Hawaii Vacation",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_HawaiiVacation",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Lucky Miners",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_LuckyMiners",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Safari Samba",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SafariSamba",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Power Pup Heroes",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_PowerPupHeroes",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Amigos Fiesta",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_AmigosFiesta",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Secret Potion",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_SecretPotion",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Nuts Commander",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_NutsCommander",
    "Lines": 100,
    "FreeSpins": "Yes",
    "Order": 7
  },
  {
    "GameName": "Bugs Tale",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_BugsTale",
    "Lines": "243(50Lines)",
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Red Square Games",
    "GameType": "Scratch Card",
    "GameCode": "Lottery_RedSquareGames",
    "Lines": "pick (1)",
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Scattered skies",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ScatteredSkies",
    "Lines": "scatter (20)",
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Stinky socks",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_StinkySocks",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Eat them all",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_EatThemAll",
    "Lines": 9,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Tennis Champions",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_TennisChampion",
    "Lines": 5,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Toys of Joy",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ToysOfJoy",
    "Lines": 25,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Iron Assassins",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_IronAssassins",
    "Lines": 30,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Fire&Ice",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_FireIce",
    "Lines": 40,
    "FreeSpins": "Yes",
    "Order": 8
  },
  {
    "GameName": "Forest Harmony",
    "GameType": "Video Slot",
    "GameCode": "SlotMachine_ForestHarmony",
    "Lines": 40,
    "FreeSpins": "Yes",
    "Order": 8
  }]
';
    }

    public function curlPOST($url, $params) {
        $curl = curl_init();
        $params = json_encode($params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "cache-control: no-cache",
                "Content-Length: " . strlen($params)
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}
