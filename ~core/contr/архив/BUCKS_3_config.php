


# ВКЛЮЧИТЬ создания ордеров покупку
orderBUY = 0


# минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000; 
# максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= 0; 


# Включить создания ордеров Stop Loss
$settings['orderSELL_SL'] = $orderSELL_SL = 0;
# Увеличить моментальную потерю маржи
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0.2;
# потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -1;

# Включить создания ордеров продажи TakeProfit
$settings['orderSELL'] = $orderSELL = 1;
# минимальная маржа % TakeProfit
$settings['TakeProfit_p'] = $TakeProfit_p = 1;
# сниженеи (потеря) маржи % при TakeProfit
$settings['declinem_p'] = $declinem_p = -0.2;


# КОНТРОЛЬНЫЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalControl'] = $IntervalControl = '1h';
# количество свечей
$settings['countIntervalControl'] = $countIntervalControl = 24;

# РАБОЧИЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalBUY'] = $IntervalBUY = '1m';
# количество свечей
$settings['countIntervalBUY'] = $countIntervalBUY = 100;

# минимальный процент  Волонтильность за последние countKlines свечей
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 2;
# процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 0.3;