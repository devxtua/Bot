<?php
// namespace binance;
class Binance{
    //рабочии
    private $KEY = '';
    private $SEC = '';
    private $URL = 'https://api.binance.com';
    private $Proxy = '';

    public $interval = array('1m'=> 60,'3m'=> 180,'5m'=> 300,'15m'=> 900,'30m'=> 1800,'1h'=> 3600,'2h'=> 7200,'4h'=> 14400,'6h'=> 21600,'8h'=> 28800,'12h'=>43200,'1d'=> 86400,'3d'=> 259200,'1w'=> 604800,'1M'=> 2592000);


    private $filetradeFeeKom = 'D:\binance\tradeFeeKom.txt';
    private $fileexchangeInfo = 'D:\binance\exchangeInfo.txt';
    private $fileticker24hr = 'D:\binance\ticker24hr.txt';

    public $tradeFeeKom = array();
    public $exchangeInfo = array();
    public $ticker24hr = array();



    //конструктор КЛАССА
    public function __construct($KEY = '', $SEC = '', $Proxy = ''){
        $this->KEY = $KEY;
        $this->SEC = $SEC;
        $this->Proxy = $Proxy;

        if ($this->ticker24hr = Functions::readFile($this->fileticker24hr)) {
        }
        if ($this->tradeFeeKom = Functions::readFile($this->filetradeFeeKom)) {
        }
        if ($this->exchangeInfo = Functions::readFile($this->fileexchangeInfo)) {
        }

        //Сверяем время API и
        $timestamp = $this->timestamp();
        if ($this->time()['serverTime'] - $timestamp < -1000) {
            echo 'host : ', $this->timestamp(),"<br/>";
            echo 'api  : ', $this->time()['serverTime'],"<br/>";
            echo 'difference: ', $this->time()['serverTime'] - $timestamp,"<br/>";
            die(' КОНСТРУКТОР STOP');
        }
        // // Проверяем торговый статус API аккаунта
        // if ($apiTradingStatus= $this->apiTradingStatus(array())) {
        //     Functions::show($apiTradingStatus, 'apiTradingStatus');
        // }

    }

    //деструктор КЛАССА
    public function __destruct(){
    }

    //конструктор КЛАССА
    public function initialization($KEY = '', $SEC = '', $Proxy = ''){
        $this->KEY = $KEY;
        $this->SEC = $SEC;
        $this->Proxy = $Proxy;

        //Проверяем актуальность
        if(time()-filemtime($this->fileticker24hr) > 100){
            //ticker24hr
            if ($this->ticker24hr = $this->ticker24hr()) {
                unlink($this->fileticker24hr);
                Functions::saveFile($this->ticker24hr, $this->fileticker24hr);
            }
        }else{
            if ($this->ticker24hr = Functions::readFile($this->fileticker24hr)) {
            }
        }

        //Проверяем актуальность базовой информации и обновляем один раз в сутки
        if(time()-filemtime($this->filetradeFeeKom) > 3600 || time()-filemtime($this->fileexchangeInfo) > 3600){
            //Получаем актуальную информацию О комисиях
            if ($this->tradeFeeKom= $this->tradeFeeKom()) {
                unlink($this->filetradeFeeKom);
                Functions::saveFile($this->tradeFeeKom, $this->filetradeFeeKom);
            }
            //Правила биржевой торговли и символьная информация
            if ($this->exchangeInfo = $this->exchangeInfo()) {
                unlink($this->fileexchangeInfo);
                Functions::saveFile($this->exchangeInfo, $this->fileexchangeInfo);
            }

        }else{
            //Даные в файле актуальны читаем
            if ($this->tradeFeeKom = Functions::readFile($this->filetradeFeeKom)) {
            }
            if ($this->exchangeInfo = Functions::readFile($this->fileexchangeInfo)) {
            }
        }
        // $GLOBALS['tradeFeeKom'] = &$this->tradeFeeKom;
        // $GLOBALS['exchangeInfo'] = &$this->exchangeInfo;
        // // $GLOBALS['ticker24hr'] = &$this->ticker24hr;
        $GLOBALS['Bin'] = &$this;

    }

    //*************************************curl********************************************************
    //подготовка и выполнение curl запроса
    private function curl($Endpoints){
        //просмотр масив параметров
        // echo "✺";
        // Functions::show_str($Endpoints);
        //устанавливаем настройки
        $array_curl = array(
            CURLOPT_CUSTOMREQUEST => $Endpoints['quest'],
            CURLOPT_HTTPHEADER => $this->header(),
            CURLOPT_URL => $this->URL.$Endpoints['points'],
            // CURLOPT_USERAGENT => $this->useragent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER=> 0,
            CURLOPT_SSL_VERIFYHOST=> 2
        );
        //устанавливаем настройки proxy
        if (isset($this->Proxy)) {
            $array_curl[CURLOPT_PROXY] = $Proxy['ip'];             // Прокси, через который будут направляться запросы
            $array_curl[CURLOPT_PROXYUSERPWD] = $Proxy['proxyauth'];  // Пароль логин proxy
            $array_curl[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;        // Вид прокси
        }
        //выполняем сам запрос в оболоче отлавливани исключений
        try{
            $curl = curl_init();
            if (FALSE === $curl){
                throw new Exception('Ошибка инициализации');
            }
            curl_setopt_array($curl, $array_curl);
            $response = curl_exec($curl);
            if (FALSE === $response){
                throw new Exception(curl_error($curl), curl_errno($curl));
            }
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status){
                throw new Exception($response, $http_status);
            }
            curl_close($curl);
        }
        //обработка исключений
        catch(Exception $e){
            $response = $e->getCode() . $e->getMessage();
            Functions::show_str($Endpoints, $response);
            if ($e->getCode() == 429) sleep(1);
        }
        //ответ всегда масив
        if (!is_array($response)) {
            $array = json_decode($response, true);
            return $array;
        }
        return $response;
    }

    //Получение системного времени
    private function timestamp(){

       return   round(microtime(true) * 1000);
    }

    //Хеширование параметров запроса
    private function signature($ParamsCurl){

       return   '&signature='.hash_hmac('SHA256', $ParamsCurl, $this->SEC);
    }

    //header доступа к API
    private function header(){
        $header = array(
         "Content-Type: application/x-www-form-urlencoded",
         "X-MBX-APIKEY: ".$this->KEY);
        return $header;
    }

    //*********************************** binance ****************************************
    //Получить информацию и варианты о symbol для конкретного asset или по списку конкретных symbols (масив)
    public function marketsSymbolInfo($AccountBalance, $exchangeInfo){
        foreach ($AccountBalance as $keyasset => $valueasset) {
            if ($valueasset['total_BTC']> 0.001) {
                foreach ($exchangeInfo['symbols'] as $key => $value) {
                    if ($value['quoteAsset']== $keyasset) {
                        $symbol[$keyasset][$value['symbol']]=$value;
                    }
                }
            }
        }
        return  $symbol;
    }

    //Получить баланс АКТИВОВ + баланс остатка в BTC и USD
    public function accountBalance($base=''){
        $sumtotal_BTC = $sumtotal_USD='';
        $balance = array();
        if (!$account = $this->account(array())) return $balance;
        foreach ($account['balances'] as $key => $value) {
            // echo $value['asset'], "<br/>";
            //общий остаток валюты
            if (($value['locked']>0 || $value['free']>0) &&  isset($value['asset']) && $value['asset'] !== '') {
                $balance[$value['asset']]['asset']=$value['asset'];
                $balance[$value['asset']]['free'] = number_format($value['free'], 8, '.', '');
                $balance[$value['asset']]['locked'] = (float)$value['locked'];
                $balance[$value['asset']]['total'] = number_format($value['locked']+$value['free'], 8, '.', '');
                $balance[$value['asset']]['base'] = '';
                $balance[$value['asset']]['min'] = '';
                $balance[$value['asset']]['minPriceBuy'] = '';

                $balance[$value['asset']]['kursBTC'] = '';
                $balance[$value['asset']]['total_BTC'] = '';
                $balance[$value['asset']]['kursUSD'] = '';
                $balance[$value['asset']]['total_USD'] = '';

                $balance[$value['asset']]['update']= '';

                if (in_array($value['asset'], array_keys($base))) {
                    $balance[$value['asset']]['base'] = 'base';
                    $balance[$value['asset']]['min'] = $base[$value['asset']]['minBalans'];
                    $balance[$value['asset']]['minPriceBuy'] = number_format($base[$value['asset']]['minPriceBuy'], 8, '.', '');
                }

            }
        }
        // $this->show($Balance);
        //Определяем баланс остатка в BTC, ETH, USDT
        foreach ($balance as $key => $value) {
            $kurs = $this->kurs($key);
            // $this->show($kurs);
            $balance[$key]['kursUSD'] = (float)$kurs['kursUSD'];
            $balance[$key]['kursBTC'] = $kurs['kursBTC'];

            $balance[$key]['total_USD'] = bcmul($value['total'],$kurs['kursUSD'], 4);
            $balance[$key]['total_BTC'] = bcmul($value['total'],$kurs['kursBTC'], 8);
        }
        return  $balance;
    }

    //Получить курс в BTC и USD и BNB
    public function kurs($key){
        // if (!$tickerPriceAll = $Bin->tickerPrice(array())) continue;
        $kurs=array();
        $kurs['base '] = $key;
        $kurs['kursBTC'] = '';
        $kurs['kursUSD'] = '';
        // $kurs['kursBNB'] = '';
        //Определяем курс
            if ($key=='BTC') {
                $kurs['kursUSD'] = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BTCUSDT'))[0]['lastPrice'];
                $kurs['kursBTC'] =  1;
                // $kurs['kursBNB'] = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BNBUSDT'))[0]['lastPrice'];
            }elseif($key=='USDT') {
                $kurs['kursUSD'] =  1;
                $kursBTC_USDT = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BTCUSDT'))[0]['lastPrice'];
                $kurs['kursBTC'] = bcdiv($kurs['kursUSD'],$kursBTC_USDT, 8);
                // $kursBNB_USDT = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BNBUSDT'))[0]['lastPrice'];
                // $kurs['kursBNB'] = bcdiv($kurs['kursUSD'],$kursBNB_USDT, 8);
            }else{
                $kursBTC_USDT = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BTCUSDT'))[0]['lastPrice'];
                // $kursBNB_USDT = Functions::multiSearch($this->ticker24hr, array('symbol'=>'BNBUSDT'))[0]['lastPrice'];
                if($tickerPricesymbol = Functions::multiSearch($this->ticker24hr, array('symbol'=>$key.'USDT'))) {
                    $kurs['kursUSD'] =  number_format($tickerPricesymbol[0]['lastPrice'], 8, '.', '');
                    $kurs['kursBTC'] =  bcdiv($kurs['kursUSD'],$kursBTC_USDT, 8);
                    // $kurs['kursBNB'] =  bcdiv($kurs['kursUSD'],$kursBNB_USDT, 8);
                }else{
                    $tickerPricesymbol = Functions::multiSearch($this->ticker24hr, array('symbol'=>$key.'BTC'));
                    $kurs['kursBTC'] =  number_format($tickerPricesymbol[0]['lastPrice'], 8, '.', '');
                    $kurs['kursUSD'] =  bcmul($kurs['kursBTC'],$kursBTC_USDT, 8);
                    // $kurs['kursBNB'] =  bcmul($kurs['kursBTC'],$kursBNB_USDT, 8);
                }
            }
        return  $kurs;
    }

    //сохранение пар
    public function funded_USDT($interval_control) {
        foreach ($this->ticker24hr as $key => $value) {
            foreach ($interval_control as $key_i => $interval) {
                if (!$symbolInfo = array_values(Functions::multiSearch($this->exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
                 // Исключаем если база не USDT
                if ($symbolInfo[0]['quoteAsset']!='USDT') continue;
                //загружаем klines
                if (!$klines = $this->klines(array('symbol'=>$value['symbol'], 'interval' => $interval, 'limit' => 1000))) {
                  $symbol['statusTREND'] = 'NOT klines';
                  continue;
                }
                //заносим в общий масив
                $filefunded = 'E:\binance\symbols\\'.$value['symbol'].'_klines_'.$interval.'_funded.txt';

                if ($funded = Functions::readFile($filefunded)){
                   foreach ($klines as $k => $v) {
                      $time = array_column($funded, 0);
                      if (!in_array($v[0], $time)) {
                        $funded[] = $v;
                      }
                   }
                }else{
                    $funded = $klines;

                }
                //сохраняем накопительые
                Functions::saveFile($funded, $filefunded);
                //сохраняем klines
                $fileklin = 'E:\binance\symbols\\'.$value['symbol'].'_klines_'.$interval.'.txt';
                Functions::saveFile($klines, $fileklin);
            }
            sleep(1);
        }
    }
    //получение исторических даных
    public function funded_klines(&$strateg, $startTime='', $endTime=''){

        if ($endTime == '') $endTime = time()*1000;
        if ($startTime =='') $startTime = $endTime - $this->interval[$strateg['interval']]*1000000;
        $startTime = $startTime - $this->interval[$strateg['interval']]*1000000;
        $funded = [];
        while ($startTime < $endTime) {
            $end = $startTime + $this->interval[$strateg['interval']]*1000000;
            $klines = $GLOBALS['Bin']->klines(array('symbol'=>$strateg['symbol'],
                                                    'interval' => $strateg['interval'],
                                                    'startTime' => $startTime,
                                                    'endTime' => $endTime,
                                                    'limit' => 1000));
            if (count($funded)>1){
               foreach ($klines as $k => $v) {
                  $time = array_column($funded, 0);
                  if (!in_array($v[0], $time)) {
                    $funded[] = $v;
                  }
               }
            }else{
                $funded = $klines;
            }
            $startTime = $end;
        }
        return $funded;
    }

    //*********************************** ОБЩЕЕ ****************************************
    //Тест API
    public function ping(){
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/ping');
        return  $this->curl($Endpoints);
    }
    //Время сервера
    public function time(){
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/time');
        return  $this->curl($Endpoints);
    }
    //Состояние системы*
    public function systemStatus(){
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/systemStatus.html');
        return  $this->curl($Endpoints);
    }
    //Текущие правила биржевой торговли и символьная информация
    public function exchangeInfo(){
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/exchangeInfo');
        return  $this->curl($Endpoints);
    }

    //*********************************** АКАУНТ ****************************************
    //История депозитов*
    public function depositHistory($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['asset'])? '&asset='.$Params['asset']: '';//0(0:pending,6: credited but cannot withdraw, 1:success)
        $ParamsCurl .= isset($Params['status'])? '&status='.$Params['status']: '';
        $ParamsCurl .= isset($Params['startTime'])? '&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])? '&endTime='.$Params['endTime']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/depositHistory.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
     //ЗАКРЫТО адрес депозита*
    public function depositAddress($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&asset='.$Params['asset'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['status'])? '&status='.$Params['status']: '';//0(0:pending,6: credited but cannot withdraw, 1:success)
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/depositAddress.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Статус акаунта*
    public function accountStatus($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/accountStatus.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Деталь актива и остатки на счетах
    public function account($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/account?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Торговый статус API аккаунта*
    public function apiTradingStatus($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/apiTradingStatus.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Конвертировать пылев активы в BNB
    public function dust($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        foreach ($Params as $key => $value){
            if($key == 'asset'){
                $ParamsCurl .= '&asset='.$value;
            }
        }
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'POST', 'points'=>'/sapi/v3/asset/dust?'.$ParamsCurl.$this->signature($ParamsCurl));
        return $this->curl($Endpoints);
    }
    //Получить небольшие суммы активов оторые обменялись на BNB
    public function userAssetDribbletLog($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/userAssetDribbletLog.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return $this->curl($Endpoints);
    }
    //Сбор комиссии за торговлю
    public function tradeFee($Params=''){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['symbol'])? '&symbol='.$Params['symbol']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/tradeFee.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return $this->curl($Endpoints);
    }
    //Деталь актива
    public function assetDetail($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
         $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/wapi/v3/assetDetail.html?'.$ParamsCurl.$this->signature($ParamsCurl));
        return $this->curl($Endpoints);
    }

    //*********************************** ДАНЫЕ ****************************************
    //Книга открытых заказов
    public function depth($Params){
        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['limit'])?'&limit='.$Params['limit']: ''; //Default 100; max 1000
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/depth?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Список последних сделок
    public function trades($Params){
        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['limit'])?'&limit='.$Params['limit']: ''; //Default 500; max 1000
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/trades?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Список старых рыночны сделок ДУБЛИРУЕТ ДАНЫЕ trades
    public function historicalTrades($Params){
        //обязательные параметры
        $ParamsCurl = isset($Params['symbol'])?'symbol='.$Params['symbol']: '';
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['limit'])?'&limit='.$Params['limit']: ''; //DeDefault 500; max 1000.
        $ParamsCurl .= isset($Params['fromId'])?'&fromId='.$Params['fromId']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/historicalTrades?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Сжатый список сделок которые исполняются одновременно из одного и того же ордера с одинаковой ценой, количество агрегировано
    public function aggTrades($Params){
        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['limit'])?'&limit='.$Params['limit']: ''; //Default 500; max 1000.
        $ParamsCurl .= isset($Params['fromId'])?'&fromId='.$Params['fromId']: '';//ID to get aggregate trades from INCLUSIVE
        $ParamsCurl .= isset($Params['startTime'])?'&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])?'&endTime='.$Params['endTime']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/aggTrades?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Подсвечники для символа
    public function klines($Params){
        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        $ParamsCurl .= isset($Params['interval'])?'&interval='.$Params['interval']: '&interval=1m'; //1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['limit'])?'&limit='.$Params['limit']: ''; //Default 500; max 1000.
        $ParamsCurl .= isset($Params['startTime'])?'&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])?'&endTime='.$Params['endTime']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/klines?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Текущая средняя цена
    public function avgPrice($Params){
        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        //НЕ обязательные параметры

        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/avgPrice?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Статистика изменения цены за 24 часа
    public function ticker24hr($Params = ''){
        //обязательные параметры

        //НЕ обязательные параметры
        $ParamsCurl = isset($Params['symbol'])?'&symbol='.$Params['symbol']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/ticker/24hr?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Последняя цена за символ или символы
    public function tickerPrice($Params){
        //обязательные параметры

        //НЕ обязательные параметры
        $ParamsCurl = isset($Params['symbol'])?'&symbol='.$Params['symbol']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/ticker/price?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }
    //Лучшая цена / кол-во в книге заказов на символ или символы
    public function bookTicker($Params){
        //обязательные параметры

        //НЕ обязательные параметры
        $ParamsCurl = isset($Params['symbol'])?'&symbol='.$Params['symbol']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/ticker/bookTicker?'.$ParamsCurl);
        return  $this->curl($Endpoints);
    }

    //*********************************** ОРДЕРА *********************************
     //Новый Заказ
    public function orderNEW($Params){

        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        $ParamsCurl .= '&side='.$Params['side']; //BUY, SELL
        $ParamsCurl .= '&type='.$Params['type']; //LIMIT,MARKET,STOP_LOSS,STOP_LOSS_LIMIT,TAKE_PROFIT,TAKE_PROFIT_LIMIT,LIMIT_MAKER
        $ParamsCurl .= '&quantity='.$Params['quantity'];//Количество
        $ParamsCurl .= '&timestamp='.$this->timestamp();

            /*Дополнительные обязательные параметры
            Type                Параметры
            LIMIT               timeInForce, quantity, price
            MARKET              quantity
            STOP_LOSS           quantity, stopPrice
            STOP_LOSS_LIMIT     timeInForce, quantity, price, stopPrice
            TAKE_PROFIT         quantity, stopPrice
            TAKE_PROFIT_LIMIT   timeInForce, quantity, price, stopPrice
            LIMIT_MAKER         quantity, price

            Другая информация:
            LIMIT_MAKER - это ордера LIMIT, которые будут отклонены, если они сразу же совпадут и будут торговаться как покупатель.
            STOP_LOSS и TAKE_PROFIT выполнят ордер MARKET при достижении стоп-цены.
            Любой заказ типа LIMIT или LIMIT_MAKER можно сделать заказом айсберга, отправив icebergQty.
            Любой заказ с айсбергом ДОЛЖЕН иметь timeInForce, установленный в GTC.

            Правила цены триггерного ордера по отношению к рыночной цене для версий MARKET и LIMIT:
            Цена выше рыночной: STOP_LOSS BUY, TAKE_PROFIT SELL
            Цена ниже рыночной: STOP_LOSS SELL, TAKE_PROFIT BUY
            */
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['timeInForce']) && $Params['type']!='MARKET'? '&timeInForce='.$Params['timeInForce']: '';   //GTC, IOC, FOK
        $ParamsCurl .= isset($Params['price']) && $Params['type']!='MARKET'? '&price='.$Params['price']: '';
        $ParamsCurl .= isset($Params['newClientOrderId'])? '&newClientOrderId='.$Params['newClientOrderId']: '';
        $ParamsCurl .= isset($Params['stopPrice'])? '&stopPrice='.$Params['stopPrice']: '';
        $ParamsCurl .= isset($Params['icebergQty'])? '&icebergQty='.$Params['icebergQty']: ''; //срок выполнения
        $ParamsCurl .= isset($Params['newOrderRespType'])? '&newOrderRespType='.$Params['newOrderRespType']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'POST', 'points'=>'/api/v3/order?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Отмена заказа (ТОРГОВЛЯ)
    public function orderDELETE($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];
        $ParamsCurl .= '&orderId='.$Params['orderId']; //или origClientOrderId
        // $ParamsCurl .= '&origClientOrderId='.$Params['clientOrderId'];

        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['newClientOrderId'])? '&newClientOrderId='.$Params['newClientOrderId']: ''; //Автоматически генерируется
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'DELETE', 'points'=>'/api/v3/order?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
        //Отмена заказа (ТОРГОВЛЯ)
    public function AllorderDELETE($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];

        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'DELETE', 'points'=>'/api/v3/openOrders?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Проверьте статус заказа
    public function orderSTATUS($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];

        $ParamsCurl .= '&orderId='.$Params['orderId']; //или origClientOrderId
        // $ParamsCurl .= '&origClientOrderId='.$Params['clientOrderId'];

        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/order?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Проверьте открытые заказы
    public function orderOPEN($Params){
      //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['symbol'])? '&symbol='.$Params['symbol']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/openOrders?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Получить все заказы на счетах; активный, отмененный или заполненный
    public function allOrders($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['orderId'])? '&orderId='.$Params['orderId']: '';
        $ParamsCurl .= isset($Params['startTime'])? '&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])? '&endTime='.$Params['endTime']: '';
        $ParamsCurl .= isset($Params['limit'])? '&limit='.$Params['limit']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/allOrders?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Получить сделки для конкретного счета и символа
    public function myTrades($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['fromId'])? '&fromId='.$Params['fromId']: '';
        $ParamsCurl .= isset($Params['startTime'])? '&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])? '&endTime='.$Params['endTime']: '';
        $ParamsCurl .= isset($Params['limit'])? '&limit='.$Params['limit']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/myTrades?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }

    //************************ OCO (TRADE)**************************************
      //Новый OCO
    public function newOCO($Params){

        //обязательные параметры
        $ParamsCurl = 'symbol='.$Params['symbol'];
        $ParamsCurl .= '&side='.$Params['side']; //BUY, SELL
        $ParamsCurl .= '&quantity='.$Params['quantity'];//Количество
        $ParamsCurl .= '&price='.$Params['price'];
        $ParamsCurl .= '&stopPrice='.$Params['stopPrice'];
        $ParamsCurl .= '&timestamp='.$this->timestamp();

        // Other Info:
        // Price Restrictions:
        // SELL: Limit Price > Last Price > Stop Price
        // BUY: Limit Price < Last Price < Stop Price

        // Quantity Restrictions:
        // Both legs must have the same quantity
        // ICEBERG quantities however do not have to be the same

        //НЕ обязательные параметры

        $ParamsCurl .= isset($Params['listClientOrderId'])? '&listClientOrderId='.$Params['listClientOrderId']: '';
        $ParamsCurl .= isset($Params['limitClientOrderId'])? '&limitClientOrderId='.$Params['limitClientOrderId']: '';
        $ParamsCurl .= isset($Params['limitIcebergQty'])? '&limitIcebergQty='.$Params['limitIcebergQty']: '';
        $ParamsCurl .= isset($Params['stopClientOrderId'])? '&stopClientOrderId='.$Params['stopClientOrderId']: '';
        $ParamsCurl .= isset($Params['stopLimitPrice'])? '&stopLimitPrice='.$Params['stopLimitPrice']: '';
        $ParamsCurl .= isset($Params['stopIcebergQty'])? '&stopIcebergQty='.$Params['stopIcebergQty']: '';
        $ParamsCurl .= isset($Params['stopLimitTimeInForce'])? '&stopLimitTimeInForce='.$Params['stopLimitTimeInForce']: '';  //GTC/FOK/IOC
        $ParamsCurl .= isset($Params['newOrderRespType'])? '&newOrderRespType='.$Params['newOrderRespType']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'POST', 'points'=>'/api/v3/order/oco?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Отмена заказа (ТОРГОВЛЯ)
    public function cancelOCO($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        $ParamsCurl .= '&symbol='.$Params['symbol'];
        $ParamsCurl .= '&orderListId='.$Params['orderListId'];

        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['listClientOrderId'])? '&listClientOrderId='.$Params['listClientOrderId']: '';
        $ParamsCurl .= isset($Params['newClientOrderId'])? '&newClientOrderId='.$Params['newClientOrderId']: ''; //Автоматически генерируется
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'DELETE', 'points'=>'/api/v3/orderList?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    //Извлекает определенный OCO на основе предоставленных необязательных параметров
    public function orderListOCO($Params){
        //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();

        //НЕ обязательные параметры
        $ParamsCurl .= '&orderListId='.$Params['orderListId'];
        $ParamsCurl .= isset($Params['origClientOrderId'])? '&origClientOrderId='.$Params['origClientOrderId']: '';
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/orderList?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    // Получает все OCO на основе предоставленных необязательных параметров
    public function allOrderListOCO($Params){
      //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['fromId'])? '&fromId='.$Params['fromId']: '';//If supplied, neither startTime or endTime can be provided
        $ParamsCurl .= isset($Params['startTime'])? '&startTime='.$Params['startTime']: '';
        $ParamsCurl .= isset($Params['endTime'])? '&endTime='.$Params['endTime']: '';
        $ParamsCurl .= isset($Params['limit'])? '&limit='.$Params['limit']: ''; //Default Value: 500; Max Value: 1000
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/allOrderList?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }
    // Получает все OCO на основе предоставленных необязательных параметров
    public function openOrderListOCO($Params){
      //обязательные параметры
        $ParamsCurl = 'timestamp='.$this->timestamp();
        //НЕ обязательные параметры
        $ParamsCurl .= isset($Params['recvWindow'])? '&recvWindow='.$Params['recvWindow']: '';
        //формируем конечную точку
        $Endpoints = array('quest' => 'GET', 'points'=>'/api/v3/openOrderList?'.$ParamsCurl.$this->signature($ParamsCurl));
        return  $this->curl($Endpoints);
    }





    //маржинального счета (MARGIN)

    //Отправить запрос на снятие (НЕ ГОТОВО)
    // public function withdraw(){
    //     $Endpoints = array('POST', '/wapi/v3/withdraw.html');
    //     return  $this->curl($Endpoints);
    // }

    //Получить историю снятие
    // public function withdrawHistory(){
    //     $Endpoints = array('GET', '/wapi/v3/withdrawHistory.html');
    //     return  $this->curl($Endpoints);
    // }
    //

    //*********************************************************************************************
    //Наростающая груперовка открытых ордеров 0,5,10,25,50,99
    public function depthTotal($symbol){
        $depth = $this->depth($symbol);

        // $this->show($depth);
        $interval = array(0,5,10,25,50,99);
        $sum_bids = $sum_asks = $bids = $asks = 0;

        for ($i=0; $i < 100; $i++) {
            $sum_asks += $depth['asks'][$i][1];
            $sum_bids += $depth['bids'][$i][1];
            if (in_array($i, $interval)) {
                $depthTotal[$i]['askQty'] = $sum_asks;
                $depthTotal[$i]['askPrice'] = $asks = isset($depth['asks'][$i][0])?$depth['asks'][$i][0]:$asks;
                $depthTotal[$i]['bidQty'] =$sum_bids;
                $depthTotal[$i]['bidPrice'] =  $bids = isset($depth['bids'][$i][0])?$depth['bids'][$i][0]:$bids;
                $depthTotal[$i]['spros'] = round($sum_bids/$sum_asks, 2);
            }

        }

        return  $depthTotal;
    }

    //Определяем комисию
    public function tradeFeeKom($symbol = ''){
        $tradeFee= $this->tradeFee($symbol);
        // Functions::show($tradeFee);
        $trade = array();
        foreach ($tradeFee['tradeFee'] as $key => $value) {
            $trade[$value['symbol']] = $value;
        }
        return  $trade;
    }

        //округляем цену или количество кратно min
    public function round_min($temp, $min){
        $temp = (float)$temp;
            $round_n = 0;
            $n = 1;
            if ($min == 1) {

                return floor($temp);
            }else{
                for ($i=1; $i < 8; $i++) {
                    if ($min < 1) {
                        $min = $min *10;
                        $round_n = $i;
                    }
                }
                for ($i=0; $i < $round_n; $i++) {
                    $n *=10;
                }
               return number_format(floor($temp * $n) / $n, $round_n+1, '.', '');
            }
    }



    // //Определяем бал по спросу
    // public function ball_depthTotal($symbol, $depthTotal){
    //     // $depthTotal= $this->depthTotal($symbol);
    //     $ball = $sum = $i = 0;
    //     foreach ($depthTotal as $key => $value) {
    //        if ($key !=0) {
    //            $sum += $value['spros'];
    //            $i++;
    //        }
    //     }
    //     $return['spros'] = $sum/$i;

    //     return $return ;
    // }

    // //Определяем бал по свечам
    // public function ball_klines($symbol){
    //     //,'3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M'
    //     $array_interval = array('1m');
    //     foreach ($array_interval as $interval) {
    //         $klines = $this->klines($symbol);
    //         foreach ($klines as $key => $klin) {
    //            $klines[$key]['%'] =  $klin['4'] /  $klin['1'];
    //         }

    //         $this->showArrayTable($klines);
    //         // $this->show(max($klines));
    //     }
    //     // die();
    //     //находим минимальную и максимальную цену

    //     // $ball=0;
    //     // foreach ($depthTotal as $key => $value) {
    //     //    if ($value['spros']<=0.95) {
    //     //        $ball+=-1;
    //     //    }elseif ($value['spros']>0.95 && $value['spros']<=1.3) {
    //     //        $ball+= 0;
    //     //    }elseif ($value['spros']>1.3 && $value['spros']<=2) {
    //     //        $ball+= 1;
    //     //    }elseif ($value['spros']>2) {
    //     //        $ball+= 2;
    //     //    }
    //     // }
    //     return  $ball;
    // }



    // //Определяем тренд
    // public function trend($klines){
    //     // echo date("Y-m-d H:i:s", $klines[0]['0']/1000), "<br/>";
    //       $trend = $trendN = $trend_p = 0;
    //       $g_trend = '';
    //     foreach ($klines as $key => $value) {
    //         if (1==bccomp($value[4], $value[1], 8)) {
    //             $change = bcsub($value[4], $value[1], 8);
    //             $Price_p = bcmul(bcdiv($change, $value[1], 8),100, 8);
    //             $к = 'top';
    //             $g_trend .= '+';
    //         }

    //         // if (0==bccomp($value[4], $value[1], 8)) {
    //         //     $consolidation = 0;
    //         //     $consolN++;
    //         // }

    //         if (-1==bccomp($value[4], $value[1], 8)) {
    //             $change = bcsub($value[4], $value[1], 8);
    //             $Price_p = bcmul(bcdiv($change, $value[1], 8),100, 8);
    //             $к = 'bottom';
    //             $g_trend .= '-';
    //         }

    //         if ($trend == $к || $trend=='') {
    //             $trendN++;
    //             $trend = $к;
    //             $trend_p = bcadd($trend_p, $Price_p, 8);
    //         }else{

    //             break;
    //         }


    //     }

    //     $result['trend_p'] = $trend_p;
    //     $result['trend'] = $trend;
    //     $result['count'] = $trendN;
    //     $result['g_trend'] = strrev($g_trend);
    //     return $result;
    // }


    //*********************************************************************************************
}
