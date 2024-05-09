$s = 4000;
$z = 0.002;
$yes_percentage = [0.005, 0.01, 0.015, 0.02, 0.025, 0.03, 0.035, 0.04, 0.045, 0.05];
$no_percentage = 0.005;

$numbers = [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10];

foreach ($numbers as $number) {
    $sum = $s;
    $sums = [];
    $sums[] = $sum;
    

    for ($i = 0; $i < $number; $i++) {
        $random = mt_rand(0, 1);
        $sum  = $sum  - ($sum * $z);

        if($random == 1){            
            $k = mt_rand(0, 9);
            $change = $sum * $yes_percentage[$k];
            $sum = $sum + $change;
            $sums[] = $sum.'+'.$change.' : '.$random;
        }else{
            $sum  = $sum  - ($sum * $z);
            $change = $sum * $no_percentage;
            $sum = $sum - $change;
            $sums[] = $sum.'-'.$change.' : '.$random;
        }
        $sum  = $sum  - ($sum * $z);
        $sum = round($sum);
    }
    // dump($sums);
    dump($number. ' - '. $sum. ' -> '. $sum-$s);
}