<?php

// namespace binance;
class Indicators{


    private $klines = [];
    public $indicator_arrey = [];


   	//конструктор КЛАССА
    public function __construct(){
            // $this->symbol = $symbol;
            // $this->interval = $interval;

    }
    //деструктор КЛАССА
    public function __destruct(){
    }


    //****************************Аналитика**********************************************
    //определение средних значений в масиве 1000 klines
    public function analytics_klines($interval, $funded_klines = ''){
        if ($klines != '') {
            // $klines = $GLOBALS['Bin']->klines(array('symbol'=>$symbol, 'interval' => $interval, 'limit' => 1000));
            $klines = $funded_klines;
        }
        $klines = $this->klines;
    	$min =  $max = 0;
        foreach ($klines as $key => $value) {
    		//определяем тренд и кофициент свечи
    		$klines[$key] += $this->klin_Trend($interval, $klines[$key]);
            //выбераем значения последней свесичи
    		$klines[$key] += $this->indicator_klin_Coefficient($interval, $klines[$key]);
            $klines[$key] += $this->indicator_klin_Volume($interval, $klines[$key]);
            $klines[$key] += $this->indicator_klin_Quote_asset_volume($interval, $klines[$key]);
            $klines[$key] += $this->indicator_klin_Taker_buy_base_asset_volume($interval, $klines[$key]);
            $klines[$key] += $this->indicator_klin_Taker_buy_quote_asset_volume($interval, $klines[$key]);

        	//запоминаем максимум и минимум
            if (-1 == bccomp((string)$klines[$max][2], (string)$value['2'], 8))  $max = $key;
            if (1 == bccomp((string)$klines[$min][3], (string)$value['3'], 8))  $min = $key;
        }
        // Functions::show(end($klines));
        // Functions::showArrayTable($klines);
		//формируем масив результата
        $result['all']['time'] = ['value'=>date("Y-m-d H:i:s", $klines[0][0]/1000),
        					'description'=>'дата первой свечи анализа'];

        $result['all']['count'] = ['value'=>count($klines),
						        		'description'=>"всего проанализировано klines $interval"];

        $closePrice = end($klines)[4];
        $result['all']['priceChangeCoefficient'] = ['value'=>bcdiv($closePrice, $klines[0][1],  8),
						        			'description'=>"кофициент изминения цены за период анализируемых свеч"];
        //находи максимум и минимум масива
        $result['all']['price_max'] = ['value'=>$klines[$max][2],
                            'description'=>"максимальна цены за период анализируемых свеч"];
        $result['all']['price_min'] = ['value'=>$klines[$min][3],
                            'description'=>"максимальна цены за период анализируемых свеч"];


        $indicators = array_keys($klines[0]);
        //анализ показателей
        foreach ($indicators as $key => $indicator) {
           if (strpos($indicator, 'indicator') === false || strpos($indicator, 'Trend') == true ) continue;
           $array_column = array_column($klines, $indicator);
           $result['all'][$indicator]['avg'] = bcdiv(array_sum($array_column), $result['all']['count']['value'],  8);
           $result['all'][$indicator]['max'] = max($array_column);
           $result['all'][$indicator]['min'] = min($array_column);
        }

        foreach (['down', 'up', 'equally'] as $key => $trend) {
            //анализ trend
            if ($search_trend = Functions::multiSearch($klines, array('klin_Trend_'.$interval => $trend))) {
                $result[$trend]['count'] = count($search_trend);
                //анализ показателей
                foreach ($indicators as $key => $indicator) {
                   if (strpos($indicator, 'indicator') === false || strpos($indicator, 'Trend') == true ) continue;
                   $array_column = array_column($search_trend, $indicator);
                   $result[$trend][$indicator]['avg'] = bcdiv(array_sum($array_column), $result[$trend]['count'],  8);
                   $result[$trend][$indicator]['max'] = max($array_column);
                   $result[$trend][$indicator]['min'] = min($array_column);
                }
            }
        }
        return $result;
    }

    //индикатор Trend
    public function klin_Trend($interval, $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Определяем тренд
		if (-1== bccomp((string)$klin[4], (string)$klin[1], 8)) {
            $result[$nickname] = 'down';
        }else if (1== bccomp((string)$klin[4], (string)$klin[1], 8)) {
			$result[$nickname] = 'up';
        }else if (0== bccomp((string)$klin[4], (string)$klin[1], 8)) {
			$result[$nickname] = 'equally';
        }
        return $result;
    }
    // ****************************Индикаторы**********************************************
    //все индикатры klines с биржи
    public function all_indicator($symbol, $interval, &$klines = ''){
        $result = [];
        if ($interval != 'off') {
            if ($klines == '') {
                $klines = $GLOBALS['Bin']->klines(array('symbol'=>$symbol, 'interval' => $interval, 'limit' => 1000));
            }
            $this->klines = &$klines;
            $klin = end($klines);

            $result += $this->indicator_klin_Coefficient($interval,  $klin);
            $result += $this->indicator_klin_Volume($interval,  $klin);
            $result += $this->indicator_klin_Quote_asset_volume($interval,  $klin);
            $result += $this->indicator_klin_Taker_buy_base_asset_volume($interval,  $klin);
            $result += $this->indicator_klin_Taker_buy_quote_asset_volume($interval,  $klin);
            $result += $this->indicator_klin_last_price($interval,  $klin);
            $result += $this->indicator_klines_last_up($interval, $klines);
            $result += $this->indicator_klines_last_down($interval, $klines);
            $result += $this->indicator_klines_last_volume_below_avg($interval, $klines);

        }
        // $result += $this->indicator_ticker24hr($symbol);
        return $result;
    }
     //Иникаторы клинов
    // public function all_klines_indicator(&$klines, $interval){
    //         $result = [];
    //         $klin = end($klines);

    //         $result += $this->indicator_klin_Coefficient($interval,  $klin);
    //         $result += $this->indicator_klin_Volume($interval,  $klin);
    //         $result += $this->indicator_klin_Quote_asset_volume($interval,  $klin);
    //         $result += $this->indicator_klin_Taker_buy_base_asset_volume($interval,  $klin);
    //         $result += $this->indicator_klin_Taker_buy_quote_asset_volume($interval,  $klin);
    //         $result += $this->indicator_klin_last_price($interval,  $klin);
    //         $result += $this->indicator_klines_last_down($interval, $klines);
    //         $result += $this->indicator_klines_last_volume_below_avg($interval, $klines);
    //     return $result;
    // }

    // ****************************Индикаторы источник klines **********************************************


    //индикатор  кофициент свечи
    public function indicator_klin_Coefficient($interval, $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
         $this->indicator_arrey[$nickname] = ['title' => "Кофициент  последней свечи (timeframe $interval)",
                                                 'description' => "делим цену закрытия на цену открытия" ];
        //Определяем
        $result[$nickname] = bcdiv($klin[4], $klin[1], 8);
        return $result;
    }

    //индикатор  volume свечи $interval
    public function indicator_klin_Volume($interval,  $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Volume  последней свечи (timeframe $interval)",
                                             'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = round($klin[5], 2);
        return $result;
    }

    //индикатор  volume свечи $interval
    public function indicator_klin_Quote_asset_volume($interval,  $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Quote_asset_volume  последней свечи (timeframe $interval)",
                                             'description' => "дает биржа" ];
        //Определяем
        $result[$nickname]= round($klin[7], 2);
        return $result;
    }

    //индикатор  quote_asset_volume свечи $interval
    public function indicator_klin_Taker_buy_quote_asset_volume($interval,  $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Taker_buy_quote_asset_volume последней свечи (timeframe $interval)",
                                             'description' => "дает биржа" ];
        //Определяем
        $result[$nickname]= round($klin[10], 2);
        return $result;
    }

    //индикатор  base_asset_volume свечи $interval
    public function indicator_klin_Taker_buy_base_asset_volume($interval,  $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Taker_buy_base_asset_volume последней свечи (timeframe $interval)",
                                             'description' => "дает биржа" ];
        //Определяем
        $result[$nickname]= round($klin[9], 2);
        return $result;
    }

        //индикатор  Цена закрытия последней свечи
    public function indicator_klin_last_price($interval, $klin){
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
         $this->indicator_arrey[$nickname] = ['title' => "Цена закрытия последней свечи (timeframe $interval)",
                                                 'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $klin[4];
        return $result;
    }
    //     //индикатор  base_asset_volume свечи $interval
    // public function indicator_klin_price_min($interval,  $klin){
    //     $nickname = __FUNCTION__.'_'.$interval;
    //     //Заносим даные в масив индикаторов
    //     $this->indicator_arrey[$nickname] = ['title' => "Цена  формирует минимум последней свечи  (timeframe $interval)",
    //                                          'description' => "дает биржа" ];
    //     //Определяем
    //     $result[$nickname]= $klin[9];
    //     return $result;
    // }

    // 2 индикатора  last_down $interval
    public function indicator_klines_last_down($interval, &$klines){
        $count = 0;
        $coef = 1;
        for ($i=count($klines)-1; $i > 0; $i--) {
            if (-1 != bccomp(bcdiv($klines[$i][4], $klines[$i][1], 8), 1, 8)) break;
            $count ++;
            $coef = bcdiv($klines[count($klines)-1][4], $klines[$i][1],  8);
        }
        //*************************
        $nickname_count = __FUNCTION__.'_count_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname_count] = ['title' => "Количество последних свечий в низ (timeframe $interval)",
                                             'description' => "считаем последние свечи" ];
        //Определяем
        $result[$nickname_count] = $count;

        //*************************
        $nickname_coef = __FUNCTION__.'_coef_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname_coef] = ['title' => "Кофициент последних свечий в низ (timeframe $interval)",
                                                    'description' => "считаем кофициент последних свечей" ];
        //Определяем
        $result[$nickname_coef] = $coef;

        return $result;
    }
        // 2 индикатора  last_up $interval
    public function indicator_klines_last_up($interval, &$klines){
        $count = 0;
        $coef = 1;
        for ($i=count($klines)-1; $i > 0; $i--) {
            if (1 != bccomp(bcdiv($klines[$i][4], $klines[$i][1], 8), 1, 8)) break;
            $count ++;
            $coef = bcdiv($klines[count($klines)-1][4], $klines[$i][1],  8);
        }
        //*************************
        $nickname_count = __FUNCTION__.'_count_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname_count] = ['title' => "Количество последних свечий в верх (timeframe $interval)",
                                             'description' => "считаем последние свечи" ];
        //Определяем
        $result[$nickname_count] = $count;

        //*************************
        $nickname_coef = __FUNCTION__.'_coef_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname_coef] = ['title' => "Кофициент последних свечий в верх (timeframe $interval)",
                                                    'description' => "считаем кофициент последних свечей" ];
        //Определяем
        $result[$nickname_coef] = $coef;

        return $result;
    }
        // 2 индикатора  last_down $interval
    public function indicator_klines_level_avg_3_klin($interval, &$klines){
        $sum = $coef = 1;
        for ($i=count($klines)-1; $i > 3; $i--) {
            if (-1 != bccomp(bcdiv($klines[$i][4], $klines[$i][1], 8), 1, 8)) break;
            $count ++;
            $coef = bcdiv($klines[count($klines)-1][4], $klines[$i][1],  8);
        }


        //*************************
        $nickname_coef = __FUNCTION__.'_coef_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname_coef] = ['title' => "Кофициент последних свечий в низ (timeframe $interval)",
                                                    'description' => "считаем кофициент последних свечей" ];
        //Определяем
        $result[$nickname_coef] = $coef;

        return $result;
    }

    // индикатора  количество последних свичей с обемом ниже среднего $interval
    public function indicator_klines_last_volume_below_avg($interval, &$klines){
        $array_column = array_column($klines, '5');
        $count_klines = count($klines);
        $avg = bcdiv(array_sum($array_column), $count_klines,  8);
        $count = 0;
        for ($i=count($klines)-2; $i > 0; $i--) {
            if (-1 != bccomp($klines[$i][5], $avg, 8)) break;
            $count ++;
        }
        //*************************
        $nickname = __FUNCTION__.'_'.$interval;
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Количество последних свичей с volume ниже среднего (timeframe $interval)",
                                             'description' => "анализ последних свечей(без последней)" ];
        //Определяем
        $result[$nickname] = $count;
        return $result;
    }

    //     // индикатора  количество последних свичей с обемом ниже среднего $interval
    // public function indicator_klines_price_min_klin($interval, &$klines){
    //     $array_column = array_column($klines, '5');
    //     $count_klines = count($klines);
    //     $avg = bcdiv(array_sum($array_column), $count_klines,  8);
    //     $count = 0;
    //     for ($i=count($klines)-2; $i > 0; $i--) {
    //         if (-1 != bccomp($klines[$i][5], $avg, 8)) break;
    //         $count ++;
    //     }
    //     //*************************
    //     $nickname = __FUNCTION__.'_'.$interval;
    //     //Заносим даные в масив индикаторов
    //     $this->indicator_arrey[$nickname] = ['title' => "Количество последних свичей с volume ниже среднего (timeframe $interval)",
    //                                          'description' => "анализ последних свечей(без последней)" ];
    //     //Определяем
    //     $result[$nickname] = $count;
    //     return $result;
    // }

    // ****************************Индикаторы источник ticker24hr ******************************************************
    //индикаторы  24hr_priceChangePercent
    public function indicator_ticker24hr($symbol){
        //Получаем даные $ticker24hr_symbol
        $ticker24hr_symbol = $GLOBALS['Bin']->ticker24hr(array('symbol' => $symbol));
        // Functions::show($ticker24hr_symbol, '');

        //****priceChangePercent******************************
        $nickname = __FUNCTION__.'_priceChangePercent';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Изминение цены за 24 часа (ticker24hr)",
                                                    'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['priceChangePercent'];

        //***weightedAvgPrice******************************
        $nickname = __FUNCTION__.'_weightedAvgPrice';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Cредневзвешеная цена за 24 часа (ticker24hr)",
                                                 'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['weightedAvgPrice'];

        //***lastPrice******************************
        $nickname = __FUNCTION__.'_lastPrice';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Последняя цены за даными (ticker24hr)",
                                                  'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['lastPrice'];

        //***quoteVolume******************************
        $nickname = __FUNCTION__.'_highPrice';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Высокая цена за 24 час (ticker24hr)",
                                                  'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['highPrice'];

        //***quoteVolume******************************
        $nickname = __FUNCTION__.'_lowPrice';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Низкая цена за 24 час (ticker24hr)",
                                                  'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['lowPrice'];

        //***volume******************************
        $nickname = __FUNCTION__.'_volume';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Объем (Volume) за 24 час (ticker24hr)",
                                                  'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['volume'];

        //***quoteVolume******************************
        $nickname = __FUNCTION__.'_quoteVolume';
        //Заносим даные в масив индикаторов
        $this->indicator_arrey[$nickname] = ['title' => "Объем (quoteVolume) за 24 час (ticker24hr)",
                                                  'description' => "дает биржа" ];
        //Определяем
        $result[$nickname] = $ticker24hr_symbol['quoteVolume'];

        return $result;
    }

}
















