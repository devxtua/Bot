<?php
class Strategies {

        public $exchange = '';//биржа

        //public $interval = array('1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1W','1M');
        public $status = ['test','optimization','trade','off'];

        public $directory_user =  'D:\binance\strategies\\';
        public $strategies_all_user =  [];

        public $file_user =  '';
        public $strategies_user =  [];



	  //конструктор КЛАССА
    public function __construct($login = ''){

        if ($login == '') {
            $Users = new Users();
            // Functions::show($Users->user_arrey, "user_arrey");
            //формируем общий масив стратегий всех user
            foreach ($Users->user_arrey as $key => $user) {


            }
            // Functions::show($this->strategies_user, "strategies_user");
        }else{
            $this->file_user = $this->directory_user.$login.'.txt';
            $this->strategies_user = Functions::readFile($this->file_user);
            // Functions::show($this->strategies_user, "strategies_user");
        }





        // Functions::show($this->strategies_user, "Strategies");
    }

    //деструктор КЛАССА
    public function __destruct(){
    }


    //***********************************ДОП методы****************************************************
    //обавление стратегии
    public function strateg_add($POST){

        $array['key'] = $POST['key'];
        $array['title'] = $POST['title'];
        $array['symbol'] = $POST['symbol'];
        $array['interval'] = $POST['interval'];
        $array['trading_limit'] = $POST['trading_limit'];
        $array['BUY_OCO'] = $POST['BUY_OCO'];
        $array['SELL_OCO'] = $POST['SELL_OCO'];
        foreach ($POST['indicator_arrey'] as $key => $value) {
            if (count($value) == 3) {
                $array['indicator_arrey'][] = $value;
            }
        }
        $array['config'] = []; // масив настроек для нахождения оптимальных настроек
        $array['setting'] = 'user'; //'user' 'avto'
        $array['status'] = $POST['status'];;  //'stop' 'off' 'on'

        $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']] = $array;
        Functions::saveFile($this->strategies_user, $this->file_user);
    }

    //обавление стратегии
    public function strateg_change($POST){
        $array['key'] = $POST['key'];
        $array['title'] = $POST['title'];
        $array['symbol'] = $POST['symbol'];
        $array['interval'] = $POST['interval'];
        $array['trading_limit'] = $POST['trading_limit'];
        $array['BUY_OCO'] = $POST['BUY_OCO'];
        $array['SELL_OCO'] = $POST['SELL_OCO'];
        foreach ($POST['indicator_arrey'] as $key => $value) {
            if (count($value) == 3) {
                $array['indicator_arrey'][] = $value;
            }
        }
        $array['config'] = $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']]['config'];
        $array['setting'] = $POST['setting'];
        $array['status'] = $POST['status'];  //'stop' 'off' 'on'

        $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']] = $array;
        Functions::saveFile($this->strategies_user, $this->file_user);
        return $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']];
    }

    //сохраняем config
    public function strateg_config_save($POST){

        $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']]['config'] = $POST['config'];
        Functions::saveFile($this->strategies_user, $this->file_user);
        return $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']];
    }

    //удаляем стратегию
    public function remove_strateg($POST){
        unset($this->strategies_user[$POST['exchange']]['strategies'][$POST['key']]);
        Functions::saveFile($this->strategies_user, $this->file_user);

    }



    //***********************************СТРАТЕГИИ****************************************************
    //






}