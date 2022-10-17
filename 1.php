<?php

/**
    Выведет на экран:
    FIX SALE: 500 RUB
    Кросовки: 286 RUB
    Шорты: 143 RUB
    Футболка: 71 RUB

    PERCENT SALE: 50%
    Кросовки: 1000 RUB
    Шорты: 500 RUB
    Футболка: 250 RUB
 */
//скидка по фиксированной сумме
function getSaleByFixSum(array $positions, int $saleSum): array
{
    if ($saleSum < 0) {
        throw new ErrorException('Sale sum illegal.');
    }

    $sum = 0;
    foreach ($positions as $iPosition) {
        $sum += $iPosition[1];
    }

    if ($sum <= $saleSum) {
        return $positions;
    }

    $percent = ($saleSum * 100) / $sum;

    $result = [];
    $userSale = $saleSum;
    foreach ($positions as $key => $iPosition) {
        $valueSalePosition = round(($iPosition[1] / 100) * $percent);
        if ($key === count($positions) - 1) {
            $result[] = [
                $iPosition[0],
                $userSale
            ];
        } else {
            $result[] = [
                $iPosition[0],
                $valueSalePosition
            ];
            $userSale -= $valueSalePosition;
        }
    }

    return $result;
}

// скидка в процентах
function getSaleByPercent(array $positions, float $percent): array
{
    if ($percent < 0 || $percent > 100) {
        throw new ErrorException('Percent illegal.');
    }

    $result = [];
    foreach ($positions as $iPosition) {
        $valueSalePosition = round(($iPosition[1] / 100) * $percent);
        $result[] = [
            $iPosition[0],
            $valueSalePosition
        ];
    }

    return $result;
}

function show(array $data): void
{
    echo implode("\n", array_map(static fn($array) => implode(': ', $array) . ' RUB', $data)) . "\n";
}

$positions = [
    ['Кросовки', 2000],
    ['Шорты', 1000],
    ['Футболка', 500]
];

$fix = 500;// в рублях
$percent = 50;// в процентах

$fixSale = getSaleByFixSum($positions, $fix);
$percentSale = getSaleByPercent($positions, $percent);

echo "FIX SALE: {$fix} RUB\n";
show($fixSale);

echo "\nPERCENT SALE: {$percent}%\n";
show($percentSale);


