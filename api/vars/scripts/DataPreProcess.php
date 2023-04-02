<?php

$day = 86400;
$file = 'testing';
//$file = 'training';
$json = file_get_contents(__DIR__ . "/../data/$file.json");

$data = json_decode($json, true);

$processed = [];
foreach ($data as $weatherData) {
    $processed[$weatherData['timestamp']] = $weatherData;
}

ksort($processed);
$sorted = array_values($processed);

foreach ($sorted as $key => &$weatherData) {
    $rainGroup = [];
    $i         = 0;
    while (
        array_key_exists($innKey = ++$i + $key, $sorted)
        && $weatherData['timestamp'] + $day > $sorted[$innKey]['timestamp']
    ) {
        $rainGroup[] = $sorted[$innKey]['rain'];
    }

    if ($rainGroup) {
        $weatherData['rainAvgDayAfter'] = array_sum($rainGroup) / count($rainGroup);
    }
}

$sorted = array_filter($sorted, function (array $data) {
    return isset($data['rainAvgDayAfter']);
});

file_put_contents(__DIR__ . "/../data/mapped_$file.json", json_encode(array_values($sorted)));
