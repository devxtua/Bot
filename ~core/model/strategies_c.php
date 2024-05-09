<?php
class Strategies {

        public $exchange = '';//биржа

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
    //добавление стратегии
    public function strateg_add($POST){
        // Functions::show($POST, "POST");
        $array['key'] = $POST['key'];
        $array['title'] = $POST['title'];
        $array['symbol'] = $POST['symbol'];
        $array['interval'] = $POST['interval'];
        $array['trading_limit'] = $POST['trading_limit'];
        $array['BUY_OCO'] = $POST['BUY_OCO'];
        $array['SELL_OCO'] = $POST['SELL_OCO'];
        //проверяем индикаторы и на стройкит обновления
        $i = 0;
        foreach ($POST['indicator_arrey'] as $key => $value) {
            if (isset($value['indicator']) && $value['indicator'] !== ''
                && isset($value['operator']) && $value['operator'] !== ''
                && isset($value['value']) && $value['value'] !== '') {
                $array['indicator_arrey'][$i]['indicator'] = $value['indicator'];
                $array['indicator_arrey'][$i]['operator'] = $value['operator'];
                $array['indicator_arrey'][$i]['value'] = $value['value'];

                if (isset($value['updat']) && $value['updat'] !== ''
                    && isset($value['updat_analytics_key']) && $value['updat_analytics_key'] !== ''
                    && isset($value['updat_operator']) && $value['updat_operator'] !== ''
                    && isset($value['updat_value']) && $value['updat_value'] !== '') {
                    $array['indicator_arrey'][$i]['updat'] = $value['updat'];
                    $array['indicator_arrey'][$i]['updat_analytics_key'] = $value['updat_analytics_key'];
                    $array['indicator_arrey'][$i]['updat_operator'] = $value['updat_operator'];
                    $array['indicator_arrey'][$i]['updat_value'] = $value['updat_value'];
                }
                $i++;
            }else{
                continue;
            }
        }
        $array['config'] = []; // масив настроек для нахождения оптимальных настроек
        $array['setting'] = 'user'; //'user' 'avto'
        $array['creat_time'] = $POST['creat_time'];
        $array['change_time'] = $POST['creat_time'];
        $array['histori_time'] = $POST['creat_time'];
        $array['status'] = $POST['status'];;  //'stop' 'off' 'on'

        $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']] = $array;
        //Сортируем стратегии по symbols intervals
        $strategies = &$this->strategies_user[$POST['exchange']]['strategies'];
        $symbols  = array_column($strategies, 'symbol');
        $intervals = array_column($strategies, 'interval');
        array_multisort($symbols, SORT_ASC, $intervals, SORT_DESC, $strategies);

        Functions::saveFile($this->strategies_user, $this->file_user);
    }

    //изминение стратегии
    public function strateg_change($POST){


        $array = $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']];
        $array['key'] = $POST['key'];
        $array['title'] = $POST['title'];
        // $array['symbol'] = $POST['symbol'];
        // $array['interval'] = $POST['interval'];
        $array['trading_limit'] = $POST['trading_limit'];
        $array['BUY_OCO'] = $POST['BUY_OCO'];
        $array['SELL_OCO'] = $POST['SELL_OCO'];
        $array['indicator_arrey'] = $POST['indicator_arrey'];
        $array['config'] = $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']]['config'];
        $array['setting'] = $POST['setting'];
        // $array['creat_time'] = $POST['creat_time'];
        $array['change_time'] = $POST['change_time'];
        $array['histori_time'] = strtotime($POST['histori_time'])*1000;
        $array['status'] = $POST['status'];  //'stop' 'off' 'on'

        $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']] = $array;
        Functions::saveFile($this->strategies_user, $this->file_user);
        return $this->strategies_user[$POST['exchange']]['strategies'][$POST['key']];
    }

    //добавляем изменяем config стратегии
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