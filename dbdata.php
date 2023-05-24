<?php
function v($text)
{
    echo '<pre>' . print_r($text, true) . '</pre>';
}
    $db = new PDO('sqlite:basketball.db');
    $query = "SELECT * FROM basketball";
    $rows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $accurateBalls = 0;
    $thrownBalls = 0;
    $highestAccuracy = 0;
    $accuracy = 0;
    $HAAccurateBalls = 0;
    $HAThrownBalls = 0;
    $HADate = '';
    foreach ($rows as $row) {
        $accurateBalls += $row['accurateBalls'];
        $thrownBalls += $row['thrownBalls'];
        if ($row['thrownBalls'] > 50) {
            $highestAccuracy = $row['accuracy'] > $highestAccuracy ? $highestAccuracy = $row['accuracy'] : $highestAccuracy;
        }
        if ($row['accuracy'] === $highestAccuracy) {
            $HAAccurateBalls = $row['accurateBalls'];
            $HAThrownBalls = $row['thrownBalls'];
            $HADate = substr($row['date'], 0, 10);
        }
    }
    if ($thrownBalls !== 0){
        $accuracy = round($accurateBalls / $thrownBalls * 100, 2);
    }
    $data = [
        'accurateBalls' => $accurateBalls,
        'thrownBalls' => $thrownBalls,
        'accuracy' => $accuracy,
        'highestAccuracy' => $highestAccuracy,
        'HAAccurateBalls' => $HAAccurateBalls,
        'HAThrownBalls' => $HAThrownBalls,
        'HADate' => $HADate,
    ];
    $result = json_encode($data);
    echo $result;

