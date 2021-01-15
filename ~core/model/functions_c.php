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
    public static function  header_table($array){
        //бераем полную шапку
        $k = $max_count = 0;
        foreach ($array as $key => $value) {
            $count = count($value);
            if ($max_count<$count) {
                $max_count = $count;
                $k = $key;
            }
        }
        return $array[$k];
    }


    //посмотреть масив таблицей
    public static function showArrayTable($array, $title='Название не указано'){
        echo  "$title";
        //бераем полную шапку
        $header = Functions::header_table($array);
        echo  "<table border='1'>";
        $i = $sum = 0;
        if (!is_array($array)) {
           return;
        }
        $n = 0;
        foreach ($array as $key1 => $value1) {
                    if ( $i == 0) {
                        echo "<tr>";
                        foreach ($header as $key => $value) {
                            echo '<th>', $key, '</th>';
                        }
                        echo '</tr>';
                        $i++;
                    }
                    if (++$n%2==0) {
                        echo '<tr class="strok">';
                    }else{
                        echo "<tr>";
                    }
                    if (is_array($value1)) {
                       foreach ($value1 as $key => $value) {

                            if (strcasecmp($key, '0') == 0
                                ||strcasecmp($key, 'timeBUY') == 0
                                || strcasecmp($key, 'timeSELL') == 0
                                || strcasecmp($key, 'closeTime') == 0
                                || strcasecmp($key, 'time') == 0
                                || strcasecmp($key, 'updateTime') == 0) {
                                echo '<td>', date("Y-m-d H:i:s", $value/1000), '</td>';
                            }elseif (strcasecmp($key, '1110') == 0
                                || strcasecmp($key, 'Start_time') == 0
                                || strcasecmp($key, 'Control_time') == 0
                                || strcasecmp($key, 'max_date') == 0
                                || strcasecmp($key, 'min_date') == 0) {
                                echo '<td>', date("H:i:s", $value/1000), '</td>';
                            }elseif(strcasecmp($key, 'time_wday') == 0
                                || strcasecmp($key, 'time_mday') == 0) {
                                echo '<td>', date("Y-m-d H:i:s", $value), '</td>';
                            }elseif(strcasecmp($key, 'time_hours') == 0
                                || strcasecmp($key, 'time_minutes') == 0
                                || strcasecmp($key, 'time_seconds') == 0 ) {
                                echo '<td>', date("H:i:s", $value), '</td>';
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
        $n=0;
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
                    if (++$n%2==0) {
                        echo '<tr class="strok">';
                    }else{
                        echo "<tr>";
                    }
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
    public static function showHistory($array, $title='Название не указано'){
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
                    if ($value1['side'] == 'BUY') {
                        echo '<tr class="marked">';
                    }else{
                        echo "<tr>";
                    }

                    if (is_array($value1)) {
                        echo '<td>', $key1, '</td>';
                       foreach ($value1 as $key => $value) {

                            if (strcasecmp($key, 'time') == 0
                                || strcasecmp($key, 'updateTime') == 0) {
                                echo '<td>', date("Y-m-d H:i:s", $value/1000), '</td>';
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
            $n = 0;
            foreach ($value_user['strategies'] as $key1 => $value1) {
                if ( $i == 0) {
                    echo "<tr>";
                    foreach ($value1 as $key => $value) {
                        echo '<th>', $key, '</th>';
                    }
                    echo '</tr>';
                    $i++;
                }
                if(++$n%2==0) {
                    echo '<tr class="strok">';
                }else{
                    echo "<tr>";
                }

                if (!is_array($value1)) die('НЕ МАСИВ');
               foreach ($value1 as $key => $value) {

                    if ($key == 'order_open' &&  $value != '') {
                        echo '<td class="marked">';
                    }elseif ($key == 'status' &&  $value =='ON') {
                        echo '<td class="marked">';
                    }else{
                        echo "<td>";
                    }

                    if($key == 'symbol'){
                        echo '<a href="https://www.binance.com/ru/trade/'.$value1['asset'].'_'.str_replace($value1['asset'], '', $value1['symbol']).'" target="_blank">'. $value.'</a></td>';
                    }elseif (strcasecmp($key, 'creat_time') == 0
                                || strcasecmp($key, 'change_time') == 0
                                || strcasecmp($key, 'histori_time') == 0
                                || strcasecmp($key, 'histori_time') == 0) {
                                echo  date("Y-m-d H:i:s", $value/1000), '</td>';
                    }else{
                        echo is_array($value)?' arrey('. count($value). ')</td>':$value, '</td>';
                    }
                }
                //кнопка тест
                echo'<td><form action="index.php?action=optimization" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="Optimization">
                    </form></td>';
                //кнопка тест
                echo'<td><form action="index.php?action=history" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="History">
                    </form></td>';
                //кнопка изменить
                echo'<td><form action="index.php?action=remove" method="post">
                    <input type="hidden" name="login" value="'.$user['login'].'">
                    <input type="hidden" name="exchange" value="'.$key_user.'">
                    <input type="hidden" name="key" value="'.$key1.'">
                    <input type="submit" value="&times;">
                    </form></td>';
                echo '</tr>';
            }
            echo '</table><br/>';
        }
    }


        //посмотреть масив таблицей
    public static function show_Analytics_symbol($array, $title='Название не указано'){
        foreach ($array as $key => $value1) {
            echo $key;
            foreach ($value1 as $key => $value) {
                //бераем полную шапку
                $header = Functions::header_table($value);
                echo  '<table border=1';
                $i = $sum = 0;
                if (!is_array($value))  return;

                echo "<tr>";
                foreach ($header as $keyheader => $valueheader) {
                    echo '<th>', $keyheader, '</th>';
                }
                echo '</tr>';
                $n = 0;
                $class = '';
                foreach ($value as $k => $val) {
                    echo ++$n%2==0? '<tr class="strok">': '<tr>';

                    if (is_array($val)) {
                       foreach ($val as $key => $v) {
                                echo '<td >', is_array($v)?arrey(', count($value), '):$v, '</td>';

                                 $class = '';
                        }
                    }else{

                        echo '<td>   ', $val, '   </td>';
                    }

                     echo '</tr>';
                }

                echo '</table><br/>';
            }
        }  //посмотреть масив таблицей
    }

    public static function showAnalytics_indicators($array, $title='Название не указано'){

        echo  "$title &nbsp;&nbsp;&nbsp;&nbsp;";
        echo '<input type="submit" name="button" name="bloc" value="ALL">&nbsp;';
        echo '<input type="submit" name="button" name="bloc" value="down">&nbsp;';
        echo '<input type="submit" name="button" name="bloc" value="up">&nbsp;';
        echo '<input type="submit" name="button" name="bloc" value="equally">&nbsp;';

        //бераем полную шапку
        $header = Functions::header_table($array);
        echo  '<table border=1';
        $i = $sum = 0;
        if (!is_array($array))  return;

        echo "<tr>";
        foreach ($header as $key => $value) {
            if (stristr($key, 'down') !=false || stristr($key, 'up') !=false || stristr($key, 'equally') !=false)   $class = 'class="none"';
            echo '<th '.$class.'>', $key, '</th>';
        }
        echo '</tr>';
        $n = 0;
        $class = '';
        foreach ($array as $key1 => $value1) {
            echo ++$n%2==0? '<tr class="strok">': '<tr>';

            if (is_array($value1)) {
               foreach ($value1 as $key => $value) {
                        if (stristr($key, 'down') !=false||stristr($key, 'up') !=false||stristr($key, 'equally') !=false)   $class = 'class="none"';
                        echo '<td '.$class.'>', is_array($value)?arrey(', count($value), '):$value, '</td>';

                         $class = '';
                }
            }else{

                echo '<td>   ', $value1, '   </td>';
            }

             echo '</tr>';
        }

        echo '</table><br/>';
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
    //***********************************ТЕСТИРОВАНИЕ*************************************************
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
    public static function test_book_OCO(&$testBUY, $symbol, $trading_limit, $book_SELL_OCO){

        //Формируем параметры тестового ОСО
            $temp = $book_SELL_OCO;
            $temp['symbol'] = $symbol['symbol'];
            $temp['timeBUY'] = time()*1000;
            $temp['Price_BUY'] = $symbol['askPrice'];
            $temp['Price_TP'] = bcmul($symbol['askPrice'], $book_SELL_OCO['Price'], 8);
            $temp['Price_SL'] = bcmul($symbol['askPrice'], $book_SELL_OCO['S_Price'], 8);
            $temp['quantity'] = bcdiv($trading_limit, $symbol['askPrice'], 8);

            $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 2);
            $temp['taker'] = bcmul($temp['sum_BUY'], $GLOBALS['tradeFeeKom'][$symbol['symbol']]['taker'], 8);

            $temp['sum_SELL'] = '';
            $temp['maker'] = '';

            $temp['lastPrice'] = '';
            $temp['coefficient'] = '';

            $temp['open'] = '';
            $temp['status'] = '';
            $temp['timeSELL'] = 0;

            $temp['margin'] = '';
            $temp['commission'] = '';
            $temp['profit'] = '';

        //заносим в масив тестовых покупок
        $testBUY[] = $temp;
    }


    public static function test_check_book_OCO(&$testBUY, $ticker){
         //Проверка открытых ордеров
        $open= 0;
        foreach ($testBUY as $testK => $value) {

            if ($value['status'] != '' || $ticker['symbol'] != $value['symbol']) continue;
            $testBUY[$testK]['lastPrice'] = $ticker['bidPrice'];
            $testBUY[$testK]['coefficient'] = bcdiv($ticker['bidPrice'], $testBUY[$testK]['Price_BUY'], 4) ;

            //klin up

                //продажа sl
                if (-1 == bccomp($ticker['bidPrice'], $value['Price_SL'], 8)) {

                    $testBUY[$testK]['status'] = 'SL';
                    $testBUY[$testK]['timeSELL'] = time()*1000;

                    $testBUY[$testK]['sum_SELL'] = bcmul($testBUY[$testK]['Price_SL'], $testBUY[$testK]['quantity'], 2);
                    $testBUY[$testK]['maker'] = bcmul($testBUY[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$ticker['symbol']]['maker'], 8);

                    $testBUY[$testK]['margin'] = bcsub($testBUY[$testK]['sum_SELL'], $testBUY[$testK]['sum_BUY'], 8);
                    $testBUY[$testK]['commission'] =  bcadd($testBUY[$testK]['taker'], $testBUY[$testK]['maker'], 8);
                    $testBUY[$testK]['profit'] = bcsub($testBUY[$testK]['margin'], $testBUY[$testK]['commission'], 8);
                    continue;
                }
                //продажа profit
                if (1 == bccomp($ticker['bidPrice'], $value['Price_TP'], 8)) {

                    $testBUY[$testK]['status'] = 'TP';
                    $testBUY[$testK]['timeSELL'] = time()*1000;

                    $testBUY[$testK]['sum_SELL'] = bcmul($testBUY[$testK]['Price_TP'], $testBUY[$testK]['quantity'], 8);
                    $testBUY[$testK]['maker'] = bcmul($testBUY[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$ticker['symbol']]['maker'], 8);

                    $testBUY[$testK]['margin'] = bcsub($testBUY[$testK]['sum_SELL'], $testBUY[$testK]['sum_BUY'], 8);
                    $testBUY[$testK]['commission'] =  bcadd($testBUY[$testK]['taker'], $testBUY[$testK]['maker'], 8);
                    $testBUY[$testK]['profit'] = bcsub($testBUY[$testK]['margin'], $testBUY[$testK]['commission'], 8);
                    continue;
                }

            //запоменаем количество открытых ордеров

            $testBUY[$testK]['open'] = ++$open;

        }

        return $lastBUY;
    }



    //тестовая закупка OCO и добавление в масив
    public static function test_buy_OCO(&$test, $strateg, $klin, $combination =''){
        $param = ($combination == '')?  $strateg: $combination;

        //Формируем параметры тестового ОСО
        $temp = $strateg['all_klines_indicator'];
            $temp['timeBUY'] = $klin[6];
            $temp['Price_BUY'] = $klin[4];
            $temp['Price_TP'] = bcmul($klin[4], $param['coefficient_profit'], 8);
            $temp['Price_SL'] = bcmul($klin[4], $param['coefficient_stop_loss'], 8);
            $temp['quantity'] = bcdiv($strateg['trading_limit'], $klin[4], 8);

            $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 8);
            $temp['taker'] = bcmul($temp['sum_BUY'], $GLOBALS['tradeFeeKom'][$strateg['symbol']]['taker'], 8);

            $temp['sum_SELL'] = '';
            $temp['maker'] = '';

            $temp['open'] = '';
            $temp['status'] = '';
            $temp['timeSELL'] = '';

            $temp['margin'] = '';
            $temp['commission'] = '';
            $temp['profit'] = '';

        //заносим в масив тестовых покупок
        $test[] = $temp;
    }

    //проверка на продажу масива тестовых закупок OCO
    public static function test_check_sell(&$test, $strateg, $klin, $end){
        $lastBUY = $klin[2];
         //Проверка открытых ордеров
        $open= 0;
        foreach ($test as $testK => $value) {
            if ($value['status'] != '') continue;
            //klin up
            if (1 == bccomp(bcdiv($klin[1], $klin[4], 8), 1, 8)) {
                //продажа sl
                if (-1 == bccomp($klin[3], $value['Price_SL'], 8)) {
                    $test[$testK]['status'] = 'SL';
                    $test[$testK]['timeSELL'] = $klin[0];

                    $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_SL'], $test[$testK]['quantity'], 8);
                    $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strateg['symbol']]['maker'], 8);

                    $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                    $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                    $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['commission'], 8);
                    continue;
                }
                //продажа profit
                if (1 == bccomp($klin[2], $value['Price_TP'], 8)) {
                    $test[$testK]['status'] = 'TP';
                    $test[$testK]['timeSELL'] = $klin[0];

                    $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_TP'], $test[$testK]['quantity'], 8);
                    $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strateg['symbol']]['maker'], 8);

                    $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                    $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                    $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['commission'], 8);
                    continue;
                }
            }
            //klin down
            if (-1 == bccomp(bcdiv($klin[1], $klin[4], 8), 1, 8)) {
                //продажа profit
                if (1 == bccomp($klin[2], $value['Price_TP'], 8)) {
                    $test[$testK]['status'] = 'TP';
                    $test[$testK]['timeSELL'] = $klin[0];

                    $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_TP'], $test[$testK]['quantity'], 8);
                    $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strateg['symbol']]['maker'], 8);

                    $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                    $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                    $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['commission'], 8);
                    continue;
                }
                //продажа sl
                if (-1 == bccomp($klin[3], $value['Price_SL'], 8)) {
                    $test[$testK]['status'] = 'SL';
                    $test[$testK]['timeSELL'] = $klin[0];

                    $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_SL'], $test[$testK]['quantity'], 8);
                    $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $GLOBALS['tradeFeeKom'][$strateg['symbol']]['maker'], 8);

                    $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                    $test[$testK]['commission'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                    $test[$testK]['profit'] = bcsub($test[$testK]['margin'], $test[$testK]['commission'], 8);
                    continue;
                }
            }

            //конечная распродажа открытых
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
    public static function test_combination(&$combinations, $strateg, &$funded_klines){
        // Functions::showArrayTable($funded_klines, 'Всего '.count($funded_klines));
        // die();
        $finish = count($funded_klines);
        $control = count($strateg['indicator_arrey']); //количество индикаторов стратегии
        foreach ($combinations as $key_com => $combination) {
            // Functions::show($combination, 'test');
            $klinesTest = $funded_klines;
            $test = [];
            //тестируем
            $Ind = '';
            $end_klines = array_pop($klinesTest); //фиксируем последний klin
            for ($i=1000; $i < $finish; $i++) {
                $start = $i-1000;
                $klines = array_slice($klinesTest, $start, 1000);
                $klin = end($klines);

                // $all_klines_indicator = $GLOBALS['Indicators']->all_klines_indicator($klines, $strateg['interval']);
                $all_klines_indicator = $GLOBALS['Indicators']->all_indicator($strateg['symbol'], $strateg['interval'], $klines);

                // echo date("Y-m-d H:i:s", $klin[0]/1000), "<br/>";
                // Functions::show($all_klines_indicator, '');

                //проверяем на срабатывание выставленые ордера на каждой свече
                Functions::test_check_sell($test, $strateg, $klin, false);

                //Проверяем индикаторы стратегии на каждой свече
                $yes = 0;
                foreach ($strateg['indicator_arrey'] as $key=> $indicator) {
                    //Проверяем и плюсуем подтверждения
                    $yes += Functions::comparison_indicator($all_klines_indicator[$indicator['indicator']], $indicator['operator'], $combination[$indicator['indicator']]);
                }
                //если все условия выполняются покупаем
                if ($control == $yes) Functions::test_buy_OCO($test, $strateg, $klin, $combination);
            }
            //конечная распродажа на последний klin (закупки нет)
            Functions::test_check_sell($test, $strateg, $end_klines, true);

            //******************ИТОГИ тестирования комбинации***************************
            $combinations[$key_com]['**'] = '***';
            $combinations[$key_com]['klines'] = $finish-1000;
            foreach (['TP','SL', 'out'] as $status) {
                if ($status_array = Functions::multiSearch($test, array('status' => $status))) {
                    $combinations[$key_com][$status] = count($status_array);
                    $combinations[$key_com][$status.'_sum'] = round(array_sum(array_column($status_array, 'profit')), 2);
                }else{
                    $combinations[$key_com][$status] = 0;
                    $combinations[$key_com][$status.'_sum'] = 0;
                }
            }
            //Удаляем combinations если нет TP
            if ($combinations[$key_com]['TP'] < 1) {
                unset($combinations[$key_com]);
                continue;
            }

            //Удаляем combinations если нет max_open
            $max_open = max(array_column($test, 'open'));
            if ($max_open < 1) {
                unset($combinations[$key_com]);
                continue;
            }

            $combinations[$key_com]['all'] = count($test);
            $combinations[$key_com]['***'] = '***';
            $combinations[$key_com]['profit'] = round(array_sum(array_column($test, 'profit')), 2);
            $combinations[$key_com]['invest_max'] = bcmul($max_open, $strateg['trading_limit'], 3);
            $combinations[$key_com]['ROI'] = bcdiv($combinations[$key_com]['profit'], $combinations[$key_com]['invest_max'], 5)*100;

            //Удаляем combinations если ROI меньше 0,01
            // if (-1 == bccomp($combinations[$key_com]['ROI'], 3.01, 8)){
            //     unset($combinations[$key_com]);
            //     continue;
            // }

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
            return $b['ROI']*100000 - $a['ROI']*100000;
        });
    }
    //сохранение результатов тестирования
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

    //***********************************Аналитиз*************************************************
    //лучшие показатели индикаторов
    public static function best_indicators($strateg, &$funded_klines){
        // Functions::show($strategies, 'strategies');
        // Functions::showArrayTable($funded_klines, 'Всего '.count($funded_klines));

        $finish = count($funded_klines);
        $klinesTest = $funded_klines;
        $test = [];
        //тестируем
        $Ind = '';
        $end_klines = array_pop($klinesTest); //фиксируем последний klin
        for ($i=1000; $i < $finish; $i++) {
            $start = $i-1000;
            $klines = array_slice($klinesTest, $start, 1000);
            $klin = end($klines);

            //проверяем на срабатывание выставленые ордера на каждой свече
            Functions::test_check_sell($test, $strateg, $klin, false);

            $strateg['all_klines_indicator'] = $GLOBALS['Indicators']->all_indicator($strateg['symbol'], $strateg['interval'], $klines);

            //закупаем
            Functions::test_buy_OCO($test, $strateg, $klin);
            // if ($i == 1100) break;
        }
        //конечная распродажа на последний klin (закупки нет)
        Functions::test_check_sell($test, $strateg, $end_klines, true);

        foreach ($test as $key => $value) {
            //Удаляем убыточные закупки
            if (-1 == bccomp($value['profit'], 0, 8)) unset($test[$key]);
        }

        //******************ИТОГИ тестирования комбинации***************************

        $result['klines'] = $finish-1000;
        $result['all'] = count($test);
        foreach (['TP','SL','out'] as $status) {
            if ($status_array = Functions::multiSearch($test, array('status' => $status))) {
                $result[$status] = count($status_array);
                $result[$status.'_sum'] = round(array_sum(array_column($status_array, 'profit')), 2);
            }else{
                $result[$status] = 0;
                $result[$status.'_sum'] = 0;
            }
        }

        $max_open = max(array_column($test, 'open'));

        $result['profit'] = round(array_sum(array_column($test, 'profit')), 2);
        $result['invest_max'] = bcmul($max_open, $strateg['trading_limit'], 3);
        $result['ROI'] = bcdiv($result['profit'], $result['invest_max'], 5)*100;

        $array_indicator = [];
        foreach (reset($test) as $key => $value) {
            if (!stristr($key, 'indicator')) continue;
                $array_indicator[$key]['indicator'] = $key;
                $array_indicator[$key]['unique'] = count(array_unique(array_column($test, $key)));
                $array_column = array_column($test, $key);
                $array_indicator[$key]['min'] = min($array_column);
                $array_indicator[$key]['max'] = max($array_column);
                $array_indicator[$key]['avg'] = bcdiv(array_sum($array_column), $result['all'],  8);


        }
        // usort($combinations, function($a, $b) {
        //     return $b['ROI']*100000 - $a['ROI']*100000;
        // });

        Functions::show($result, 'result');
        Functions::showArrayTable($array_indicator, 'array_indicator ');
        // Functions::showArrayTable($test, 'test ');
        return ;
    }

    //лучшие показатели индикаторов
    public static function analytics_indicators($strateg, &$funded_klines){
        // Functions::show($strategies, 'strategies');
        // Functions::showArrayTable($funded_klines, 'Всего '.count($funded_klines));

        $finish = count($funded_klines);
        $klinesTest = $funded_klines;
        $test = [];
        //получаем индикаторы каждой свичи
        for ($i=1000; $i < $finish; $i++) {
            $start = $i-1000;
            $klines = array_slice($klinesTest, $start, 1000);
            //анализируемый klin
            $klin = end($klines);

            $all_indicator = $GLOBALS['Indicators']->all_indicator($strateg['symbol'], $strateg['interval'], $klines);
                //Определяем тренд свечи
                if (-1== bccomp((string)$klin[4], (string)$klin[1], 8)) {
                    $all_indicator['trend'] = 'down';
                }else if (1== bccomp((string)$klin[4], (string)$klin[1], 8)) {
                    $all_indicator['trend'] = 'up';
                }else if (0== bccomp((string)$klin[4], (string)$klin[1], 8)) {
                    $all_indicator['trend'] = 'equally';
                }

            //добавляем в масив
            $test[] = $all_indicator;
        }

        //******************ИТОГИ ***************************


        foreach (['all', 'down', 'up', 'equally'] as $key => $trend) {
            //анализ trend
            if ($trend == 'all') {
                $search = $test;
            }else{
                $search = Functions::multiSearch($test, array('trend' => $trend));
            }

            foreach (reset($search) as $key => $value) {
                if (!stristr($key, 'indicator')) continue;
                    $array_column = array_column($search, $key);
                    $array_indicator[$key]['indicator'] = $key;
                    $array_indicator[$key][$trend.'_c'] = count($search);
                    $array_indicator[$key][$trend.'_min'] = min($array_column);
                    $array_indicator[$key][$trend.'_max'] = max($array_column);

                        // $explodeDigits = explode('.', (string)$array_indicator[$key][$trend.'_max']);
                        // $num = strlen((string)$explodeDigits[1]);
                    $array_indicator[$key][$trend.'_avg'] = bcdiv(array_sum($array_column), $array_indicator[$key][$trend.'_c'],  8);
            }
        }

        // Functions::showArrayTable($array_indicator, 'analytics_indicators ');
        // Functions::showArrayTable($test, 'test ');
        return $array_indicator;
    }

    //Определяем максимум минимум 0,5,15,30,60,120,180,240,480 мин
    public static function klines_max_min($klines, $max_minInterval = ''){
        // Functions::showArrayTable($klines, '');
        if ($max_minInterval == '') {
            $max_minInterval = [0,5,15,30,60,120,180,240,480];
        }

        $max_min = array();
        $top = $max = $klines[0][2];
        $down = $min = $klines[0][3];
        $flagdown = $average_0_down = $count = 0;
        $time = time();
        $IntervalOLD = '';
        foreach ($klines as $key => $klin) {
            // echo $key, date("Y-m-d H:i:s", $klin['0']/1000), "<br/>";

            if (-1 == bccomp((string)$max, (string)$klin['2'], 8))  $max = $klin['2'];
            if (1 == bccomp((string)$min, (string)$klin['3'], 8))  $min = $klin['3'];

            if (in_array($key, $max_minInterval)) {
                $max_min[$key]['Interval'] = $key;

                $max_min[$key]['date'] = date("Y-m-d H:i:s", $klin['0']/1000);
                // $max_min[$key]['age'] = date("m-d H:i:s", mktime(0, 0, $time - $klin['0']/1000));
                $max_min[$key]['Open'] = $klin['1'];
                $max_min[$key]['High'] = $klin['2'];
                $max_min[$key]['Low'] = $klin['3'];
                $max_min[$key]['Close'] = $klin['4'];

                $max_min[$key]['max'] = $max;
                $max_min[$key]['min'] = $min;
                $max_min[$key]['spred'] = bcsub($max, $min, 8);
                $max_min[$key]['spred_%'] = bcdiv(bcmul($max_min[$key]['spred'],100, 8), $max_min[$key]['min'], 8);

                $max_min[$key]['trend'] = bcsub($IntervalOLD['4'], $klin['4'], 8);
                $max_min[$key]['trend_%'] = bcdiv(bcmul($max_min[$key]['trend'],100, 8), $klin['4'], 8);

                $max_min[$key]['trend_ALL'] = bcsub($klines[0]['4'], $klin['4'], 8);
                $max_min[$key]['trend_ALL_%'] = bcdiv(bcmul($max_min[$key]['trend_ALL'],100, 8), $klin['4'], 8);
                $IntervalOLD = $klin;
            }
        }
        return  $max_min;
    }
    //
    public static function settings_strategy($strateg, $analytics_indicators, &$funded_klines){
        // Functions::showArrayTable($funded_klines, 'funded_klines');
        $klines_max_min = Functions::klines_max_min($funded_klines, [0,5,10,15,20,30]);
        Functions::showArrayTable_key($klines_max_min, 'klines_max_min');


        $settings['indicator_arrey']= $strateg['indicator_arrey'];




        return $settings;

    }



    //***********************************ТОРГОВЛЯ*************************************************




}
