<?php

// namespace binance;
class Functions{
        //конструктор КЛАССА
    public function __construct(){

    }
    //деструктор КЛАССА
    public function __destruct(){
    }


    //***********************************РАЗНЫЕ*************************************************

    //посмотреть масив
    public static function show($array, $title='Название не указано'){
        echo $title;
        print_r('<pre>');
        print_r($array);
        print_r('</pre>');
    }
    //посмотреть масив в строку
    public static function show_str($array, $title=''){
        echo $title, '<br/>';
        print_r($array);
        echo '<br/>';
    }

    //посмотреть масив таблицей
    public static function showArrayTable($array, $title='Название не указано'){
        echo  "$title";
        echo  "<table border='1'>";
        $i = $sum = 0;
        if (!is_array($array)) {
           return;
        }
        foreach ($array as $key1 => $value1) {
                    if ( $i == 0) {
                        echo "<tr>";
                        foreach ($value1 as $key => $value) {
                            echo '<th>', $key, '</th>';
                        }
                        echo '</tr>';
                        $i++;
                    }
                    echo "<tr>";
                    if (is_array($value1)) {
                       foreach ($value1 as $key => $value) {

                            if (strcasecmp($key, 'time') == 0
                                ||strcasecmp($key, 'timeBUY') == 0
                                || strcasecmp($key, 'timeSELL') == 0
                                || strcasecmp($key, 'closeTime') == 0
                                || strcasecmp($key, 'BUYTime') == 0
                                || strcasecmp($key, 'updateTime') == 0) {
                                echo '<td>', date("Y-m-d H:i:s", $value/1000), '</td>';
                            }elseif (strcasecmp($key, '0') == 0
                                || strcasecmp($key, 'Start_time') == 0
                                || strcasecmp($key, 'Control_time') == 0
                                || strcasecmp($key, 'max_date') == 0
                                || strcasecmp($key, 'min_date') == 0) {
                                echo '<td>', date("H:i:s", $value/1000), '</td>';
                            }else{
                                if (is_array($value)) {
                                    echo '<td> arrey(', count($value), ')</td>';
                                }else{
                                    echo '<td>', $value, '</td>';
                                }
                            }
                        }
                    }else{

                        echo '<td>   ', $value1, '   </td>';
                    }

                     echo '</tr>';
        }

        echo '</table><br/>';
    }
        //посмотреть масив таблицей
    public static function showArrayTable_key($array, $title='Название не указано'){
        echo  "$title";
        echo  "<table border='1'>";
        $i = $sum = 0;
        if (!is_array($array)) {
           return;
        }
        foreach ($array as $key1 => $value1) {
                    if ( $i == 0) {
                        echo "<tr>";
                        echo '<th>key</th>';
                        foreach ($value1 as $key => $value) {
                            echo '<th>', $key, '</th>';
                        }
                        echo '</tr>';
                        $i++;
                    }
                    echo "<tr>";
                    if (is_array($value1)) {
                        echo '<td>', $key1, '</td>';
                       foreach ($value1 as $key => $value) {

                            if (strcasecmp($key, 'time') == 0
                                ||strcasecmp($key, 'timeBUY') == 0
                                || strcasecmp($key, 'timeSELL') == 0
                                || strcasecmp($key, 'closeTime') == 0
                                || strcasecmp($key, 'BUYTime') == 0
                                || strcasecmp($key, 'updateTime') == 0) {
                                echo '<td>', date("Y-m-d H:i:s", $value/1000), '</td>';
                            }elseif (strcasecmp($key, '0') == 0
                                || strcasecmp($key, 'Start_time') == 0
                                || strcasecmp($key, 'Control_time') == 0
                                || strcasecmp($key, 'max_date') == 0
                                || strcasecmp($key, 'min_date') == 0) {
                                echo '<td>', date("H:i:s", $value/1000), '</td>';
                            }else{
                                if (is_array($value)) {
                                    echo '<td> arrey(', count($value), ')</td>';
                                }else{
                                    echo '<td>', $value, '</td>';
                                }



                            }
                        }
                    }else{

                        echo '<td>   ', $value1, '   </td>';
                    }

                     echo '</tr>';
        }

        echo '</table><br/>';
    }

    //посмотреть масив таблицей
    public static function showStrategies($user, $title='Название не указано'){

        if (!is_array($user)) return;
        foreach ($user as $key_user => $value_user) {
            if ($key_user == 'login') continue;

            echo  "$title";
            echo  "<table border='1'>";
            $i = 0;
            foreach ($value_user['strategies'] as $key1 => $value1) {
                if ( $i == 0) {
                    echo "<tr>";
                    foreach ($value1 as $key => $value) {
                        echo '<th>', $key, '</th>';
                    }
                    echo '</tr>';
                    $i++;
                }
                echo "<tr>";
                if (is_array($value1)) {
                   foreach ($value1 as $key => $value) {
                        if($key == 'symbol'){
                            echo '<td><a href="https://www.binance.com/ru/trade/'.$value1['asset'].'_'.str_replace($value1['asset'], '', $value1['symbol']).'" target="_blank">'. $value.'</a></td>';
                        }elseif (strcasecmp($key, 'time') == 0
                            ||strcasecmp($key, 'timeSELL') == 0
                            || strcasecmp($key, 'openTime') == 0
                            || strcasecmp($key, 'closeTime') == 0
                            || strcasecmp($key, 'BUYTime') == 0
                            || strcasecmp($key, 'updateTime') == 0
                            || strcasecmp($key, '0') == 0) {
                            echo '<td>', date("Y-m-d H:i:s", $value/1000), '</td>';
                        }elseif (strcasecmp($key, 'statusTime') == 0
                            || strcasecmp($key, 'Start_time') == 0
                            || strcasecmp($key, 'Control_time') == 0
                            || strcasecmp($key, 'max_date') == 0
                            || strcasecmp($key, 'min_date') == 0) {
                            echo '<td>', date("H:i:s", $value/1000), '</td>';
                        }else{
                            if (is_array($value)) {
                                echo '<td> arrey(', count($value), ')</td>';
                            }else{
                                echo '<td>', $value, '</td>';
                            }
                        }
                    }
                }else{
                    echo '<td>   ', $value1, '   </td>';
                }

                //кнопка тест
                echo'<td><form action="index.php?action=optimization" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="Оптимизация">
                    </form></td>';
                //кнопка тест
                echo'<td><form action="index.php?action=history" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="ИСТОРИЯ СДЕЛОК">
                    </form></td>';
                //кнопка изменить
                echo'<td><form action="index.php?action=strateg_change" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="&#9881;">
                    </form></td>';

                echo '</tr>';
            }
            echo '</table><br/>';
        }
    }

    //посмотреть масив таблицей сделок
    public static function showArrayTableOrders($array){

        echo  "<table border='1'>";
        $i = 0;
        foreach ($array as $key => $value) {
                // if ( $i > 10) break;
                if ( $i == 0) {
                    echo "<tr>";
                    echo '<th>', 'Дата и время', '</th>';
                    echo '<th>', 'Пара', '</th>';
                    echo '<th>', 'Тип', '</th>';
                    echo '<th>', 'Сторона', '</th>';
                    echo '<th>', 'Количество', '</th>';
                    echo '<th>', 'Цена', '</th>';
                    echo '<th>', 'ЦенаUSD', '</th>';
                    echo '<th>', '* $ МАРЖА $ *', '</th>';

                    echo '</tr>';
                    $i++;
                }

                    echo "<tr>";
                    echo '<td>', date("Y-m-d H:i:s", $value['transactTime']/1000), '</td>';
                    echo '<td>', $value['symbol'], '</td>';
                    echo '<td>', $value['type'], '</td>';
                    echo '<td>', $value['side'], '</td>';
                    echo '<td>', $value['executedQty'], '</td>';
                    echo '<td>', $value['orderPrice'], '</td>';
                    echo '<td>', $value['orderPriceUSD'], '</td>';
                    echo '<td>', round($value['marginUSD'],4), '</td>';




                   // echo '<td>'.$array[$key+1]['cummulativeQuoteQty']. '</td>';

                    echo '</tr>';
                   $i++;

        }
        echo '</table><br/>';
    }

    //Сохранение в файла
    public static function saveFile($array, $filename){
        $data = serialize($array);                  // PHP формат сохраняемого значения.
        // $data = json_encode($accountBalance);    // JSON формат сохраняемого значения.
        file_put_contents($filename, $data);  // Запись.
    }

    //Чтение с файла
    public static function readFile($filename){
        if (file_exists($filename)) {
            $data = file_get_contents($filename);      // Чтение.
        }
        $array = unserialize($data);                     // PHP формат сохраняемого значения.
        // $accountBalance = json_decode($data, TRUE);   // Если нет TRUE то получает объект, а не массив.
        return $array;
    }

    //Поиск по вложеным массивам по нескольким условиям +
    public static function multiSearch(array $array, array $pairs){
        $found = array();
        //если масив пустой пустой вернем
        if (count($array)==0) return $found;
        //
        foreach ($array as $aKey => $aVal) {
            $coincidences = 0;
            foreach ($pairs as $pKey => $pVal) {
                if (array_key_exists($pKey, $aVal) && $aVal[$pKey] == $pVal) {
                    $coincidences++;
                }
            }
            if ($coincidences == count($pairs)) {
                $found[$aKey] = $aVal;
            }
        }
        return array_values($found);
    }

    //обеденение двух двухмерных масивов по ключу
    public static function array_map_keys($param1,$param2,$param3=NULL){
        $res = array();

        if ($param3 !== NULL)
        {
            foreach(array(2,3) as $p_name)
            {
                if (!is_array(${'param'.$p_name}))
                {
                    trigger_error(__FUNCTION__.'(): Argument #'.$p_name.' should be an array',E_USER_WARNING);
                    return;
                }
            }
            foreach($param2 as $key => $val)
            {
                $res[$key] = call_user_func($param1,$param2[$key],$param3[$key]);
            }
        }
        else
        {
            if (!is_array($param2))
            {
                trigger_error(__FUNCTION__.'(): Argument #2 should be an array',E_USER_WARNING);
                return;
            }
            foreach($param2 as $key => $val)
            {
                $res[$key] = call_user_func($param1,$param2[$key]);
            }
        }
        return $res;
    }

    //***********************************ТЕСТИРОВАНИЕ*************************************************
    //определяем варианты индикатора
    public static function options_indicator ($arrays){
      $result = [];
      foreach ($arrays as $key => $value) {
        $explodeDigits = explode('.', (string)$value['step']);
        $num = strlen((string)$explodeDigits[1]);
        $options = [];
        $n = 1;
        if (0!=bccomp($value['min'], $value['max'], $num)) {
            if (-1==bccomp($value['min'], $value['max'], $num)) {
                for ($i=round($value['min'],$num); -1==bccomp($i, $value['max'], $num) || 0==bccomp($i, $value['max'], $num); $i = bcadd($i, $value['step'], $num)) {
                    $options[] = $i;
                }
            }else{
                for ($i=round($value['min'],$num); 1==bccomp($i, $value['max'], $num) || 0==bccomp($i, $value['max'], $num); $i = bcsub($i, $value['step'], $num)) {
                    $options[] = $i;
                }
            }
        }else{
            $options[] = $value['min'];
        }
        $result[$key] = $options;
      }



      return $result;
    }

    //комбинатор нескольких индикаторов
    public static function combinations_options ($arrays, $key, $N=-1, $count=FALSE, $weight=FALSE){
        if ($N == -1) {
            // Функция запущена в первый раз и запущена "снаружи", а не из самой себя.
            $arrays = array_values($arrays);
            $count = count($arrays);
            $weight = array_fill(-1, $count+1, 1);
            $Q = 1;

            // Подсчитываем:
            // $Q - количество возможных комбинаций,
            // $weight - массив "весов" разрядов.
            foreach ($arrays as $i=>$array) {
                $size = count($array);
                $Q = $Q * $size;
                $weight[$i] = $weight[$i-1] * $size;
            }

            $result = array();
            for ($n=0; $n<$Q; $n++)
                $result[] = Functions::combinations_options($arrays, $key, $n, $count, $weight);

            return $result;
        }else{
            // Дано конкретное число, надо его "преобразовать" в комбинацию.
            // Чтобы не переспрашивать функцию count() обо всём каждый раз, нам уже даны:
            // $count - общее количество массивов, т.е. count($arrays),
            // $weight - "вес" одной единицы "разряда", с учётом веса предыдущих разрядов.

            // Заготавливаем нулевой массив состояний
            $SostArr = array_fill(0, $count, 0);

            $oldN = $N;

            // Идём по радрядам начиная с наибольшего
            for ($i=$count-1; $i>=0; $i--)
            {
                // Поступаем как с числами в позиционных системах счисления,
                // то есть максимально заполняем наибольшие значения
                // и по остаточному принципу - наименьшие.
                // Число в i-ом разряде выражается как количество весов (i-1)0ых разрядов...
                // Да-да, я очень криво объясняю, просто поверьте на слово.
                // Вообще, эти две строки можно проверить и самостоятельно... =)
                $SostArr[$i] = floor( $N/$weight[$i-1] );
                $N = $N - $SostArr[$i] * $weight[$i-1];
            }

            // Наконец, переводим "состояния" в реальные значения
            $result = array();
            for ($i=0; $i<$count; $i++){
                  $result[$key[$i]] = $arrays[$i][ $SostArr[$i] ];
            }
            return $result;
        }
    }

    //проверяем условия индикатора
    public static function comparison_indicator ($indicator, $operator, $condition){

        if ($operator == '<' && -1 == bccomp($indicator,  $condition, 8)) {
            return 1;
        }elseif ($operator == '=' && 0 == bccomp($indicator,  $condition, 8)) {
            return 1;
        }elseif ($operator == '>' && 1 == bccomp($indicator,  $condition, 8)) {
            return 1;
        }
            return 0;
    }
    //****
    //тестовая закупка OCO и добавление в масив
    public static function test_buy_OCO(&$test, $strategies, $klin, $combination){
        //Формируем параметры тестового ОСО
            $temp['timeBUY'] = $klin[6];
            $temp['Price_BUY'] = $klin[4];
            $temp['Price_TP'] = bcmul($klin[4], $combination['coefficient_profit'], 8);
            $temp['Price_SL'] = bcmul($klin[4], $combination['coefficient_stop_loss'], 8);
            $temp['quantity'] = bcdiv($strategies['trading_limit'], $klin[4], 8);

            $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 8);
            $temp['taker'] = bcmul($temp['sum_BUY'], $GLOBALS['tradeFeeKom'][$strategies['symbol']]['taker'], 8);

            $temp['sum_SELL'] = '';
            $temp['maker'] = '';

            $temp['open'] = $open;
            $temp['status'] = '';
            $temp['timeSELL'] = '';

            $temp['margin'] = '';
            $temp['commission'] = '';
            $temp['profit'] = '';
        //заносим в масив тестовых покупок
        $test[] = $temp;
    }

    //проверка на продажу масива тестовых закупок OCO
    public static function test_check_sell(&$test, $strategies, $klin, $end){
        $lastBUY = $klin[2];
         //Проверка открытых ордеров
        $open= 0;
        foreach ($test as $testK => $value) {

            if ($value['status'] != '') continue;

            //продажа profit
            if (1 == bccomp($klin[2], $value['Price_TP'], 8)) {
                $test[$testK]['status'] = 'TP';
                $test[$testK]['timeSELL'] = $klin[0];

                $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_TP'], $test[$testK]['quantity'], 8);
                $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strategies['symbol']]['maker'], 8);

                $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);
                continue;
            }
            //продажа sl
            if (-1 == bccomp($klin[3], $value['Price_SL'], 8)) {
                $test[$testK]['status'] = 'SL';
                $test[$testK]['timeSELL'] = $klin[0];

                $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_SL'], $test[$testK]['quantity'], 8);
                $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strategies['symbol']]['maker'], 8);

                $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);
                continue;
            }
            //распродажа открытых
            if ($end) {
                $test[$testK]['status'] = 'out';
                $test[$testK]['timeSELL'] = $klin[0];

                $test[$testK]['sum_SELL'] = bcmul($klin[4], $test[$testK]['quantity'], 8);
                $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strategies['symbol']]['maker'], 8);

                $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);


            }
            //запоменаем количество открытых ордеров
            $test[$testK]['open'] = ++$open;
            //запоменаем цену окрытого ордера
            $lastBUY = bcmul($test[$testK]['Price_BUY'], $settings['thresholdBuy'], 8);

        }

        return $lastBUY;
    }
    //проверка проверка индикаторов одной стратегии
    public static function check_strateg(&$strateg, &$Indicators){
        $conditions = count($strateg['indicator_arrey']);//количество условий в стратегии
        $on = 0;
        foreach ($strateg['indicator_arrey'] as $key => $strateg_indicator) {
                    $t=0;
                if ($strateg_indicator['operator'] == '<' && -1 == bccomp($Indicators->indicator_arrey[$strateg_indicator['indicator']]['value'],  $strateg_indicator['value'], 8)) {
                    $on++;
                    $t++;
                }elseif ($strateg_indicator['operator'] == '=' && 0 == bccomp($Indicators->indicator_arrey[$strateg_indicator['indicator']]['value'],  $strateg_indicator['value'], 8)) {
                    $on++;
                    $t++;
                }elseif ($strateg_indicator['operator'] == '>' && 1 == bccomp($Indicators->indicator_arrey[$strateg_indicator['indicator']]['value'],  $strateg_indicator['value'], 8)) {
                    $on++;
                    $t++;
                }

            // echo  $t, ' -> ', $Indicators->indicator_arrey[$strateg_indicator['indicator']]['title'], ' (', $Indicators->indicator_arrey[$strateg_indicator['indicator']]['value'],' ', $strateg_indicator['operator'], ' ',$strateg_indicator['value'], ")<br/>";
        }
        // ПРОВЕРИЛИ ИНДИКАТОРЫ И
        if ($on == $conditions) {
            if ($strateg['symbol'] == 'TRADING') {

                $strateg['indicator_check'] = 'TRADING_BUY';
            }elseif ($strateg['symbol'] == 'TEST') {

                $strateg['indicator_check'] = 'TEST_BUY';
            }else{
                $strateg['indicator_check'] = 'show_BUY';
            }
        }else{
          $strateg['indicator_check'] = $t.'/'.$conditions;
        }
    }

    //TEST COMBINATIONS
    public static function test_combination(&$combinations, $strategies, $klines, &$Indicators){
        // Functions::show($strategies, 'strategies');
        $klines = $GLOBALS['Bin']->klines(array('symbol'=>$strategies['symbol'], 'interval' => $strategies['interval'], 'limit' => 1000));

        $control = count($strategies['indicator_arrey']);
        foreach ($combinations as $key_com => $combination) {
            $klinesTest = $klines;

            // Functions::show($combination, 'test');
            $test = [];
            //тестируем
            $Ind ='';
            $end_klines = array_pop($klinesTest); //фиксируем последний klin
            foreach ($klinesTest as $klin) {
                $all_indicator = $GLOBALS['Indicators']->all_indicator($strategies['symbol'], $strategies['interval']);
                // Functions::show($all_indicator, 'all_indicator');
                //проверяем на срабатывание выставленые ордера на каждой свече
                Functions::test_check_sell($test, $strategies, $klin, false);

                //Проверяем индикаторы стратегии на каждой свече
                $yes = 0;
                foreach ($strategies['indicator_arrey'] as $key=> $indicator) {
                    //Проверяем и плюсуем подтверждения
                    $yes += Functions::comparison_indicator($all_indicator[$indicator['indicator']], $indicator['operator'], $combination[$indicator['indicator']]);
                }

                //если все условия выполняются покупаем
                if ($control == $yes) Functions::test_buy_OCO($test, $strategies, $klin, $combination);
            }
            //конечная распродажа на последний klin (закупки нет)
            Functions::test_check_sell($test, $strategies, $end_klines, true);


            //ИТОГИ
            $combinations[$key_com]['**'] = '***';
            $combinations[$key_com]['klines'] = count($klines);
            foreach (['TP','SL','out'] as $status) {
                if ($status_array = Functions::multiSearch($test, array('status' => $status))) {

                    $combinations[$key_com][$status] = count($status_array);
                    $combinations[$key_com][$status.'_sum'] = round(array_sum(array_column($status_array, 'profit')), 2);
                }else{
                    $combinations[$key_com][$status] = 0;
                    $combinations[$key_com][$status.'_sum'] = 0;
                }
            }

            if ($combinations[$key_com]['TP'] < 1 || $combinations[$key_com]['SL'] < 1 || $combinations[$key_com]['SL'] > $combinations[$key_com]['TP']) {
                unset($combinations[$key_com]);
                continue;
            }

            $combinations[$key_com]['all'] = count($test);
            $combinations[$key_com]['***'] = '***';
            $combinations[$key_com]['max_open'] = max(array_column($test, 'open'));
            $combinations[$key_com]['invest'] = bcmul($combinations[$key_com]['max_open'], $strategies['trading_limit'], 3);
            $combinations[$key_com]['profit'] = round(array_sum(array_column($test, 'profit')), 2);
            $combinations[$key_com]['invest_%'] = bcdiv($combinations[$key_com]['profit'], $combinations[$key_com]['invest'], 5)*100;
            $combinations[$key_com]['key_com'] = $key_com;

            if (-1 == bccomp($combinations[$key_com]['invest_%'], 1.5, 8)){
                unset($combinations[$key_com]);
                continue;
            }

            //останавливаем для отладки
            // if ($combinations[$key_com]['all'] > 10) {
            //     // Functions::show($Ind, 'Ind');
                // Functions::show_str($combinations[$key_com], 'combination');
                // Functions::showArrayTable($test, $key_com);
            //     // break;
            //     return;
            // }

            unset($test);
        }
        usort($combinations, function($a, $b) {
            return $b['invest_%']*100000 - $a['invest_%']*100000;
        });
    }

    //
    public static function settings_statistics(&$combinations){
        $strateg = $combinations[0];
        $strateg['*'] = '***';
        $strateg['time'] = time()*1000;
        $strateg['count'] = count($combinations);

        $file = 'D:\binance\settings_statistics.txt';
        $settings_statistics = Functions::readFile($file);

        $settings_statistics[] = $strateg;
        Functions::saveFile($settings_statistics, $file);
        return $settings_statistics;
    }
    //***********************************ТОРГОВЛЯ*************************************************




}
