<?php
function v($text)
{
    echo '<pre>' . print_r($text, true) . '</pre>';
}
$db = new PDO('sqlite:basketball.db');
$data = json_decode(file_get_contents('php://input'), true);
if ($data !== null){
    $params = [];$params[] = $data['accurateBalls'];
    $params[] = $data['roundedAccuracy'];
    $params[] = $data['thrownBalls'];
    $query = "INSERT INTO basketball (accurateBalls, accuracy, thrownBalls) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $success = $stmt->execute($params);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <title>Basketball assistant</title>
</head>
<body>
<div id="app">
    <div class="box">
        <img src="images/logo.png" height="131" width="174" class="logo"/>
        <div class="score">
            <div class="accurate-shots" id="accurateShots">{{accurateBalls}}</div>
            <div class="separator">&nbsp/&nbsp</div>
            <div class="amount-of-shots-thrown" id="thrownShots">{{thrownBalls}}</div>
        </div>
        <div class="clearfix"></div>
        <button class="accurate-throw-button" @click="accurateShot">Rzut celny</button>
        <button class="missed-throw-button" @click="missedThrow">Rzut niecelny</button>
        <div class="clearfix"></div>
        <div class="accuracy">Celność: </div>
        <div class="percentage" id="accuracy">{{roundedAccuracy}}%</div>
        <div class="clearfix"></div>
        <div class="statistics" @click="goToData">Statystyki</div>
        <div class="save-data-to-db" @click="saveData">Zapisz wynik</div>
    </div>
</div>
</body>
<script>
    const { createApp } = Vue
    createApp({
        data() {
            return {
                accurateBalls: 0,
                thrownBalls: 0,
                accuracy: 0,
                roundedAccuracy: 0,
            }
        },
        methods: {
            saveData() {
                let app = this;
                axios.post('index.php', {
                    accurateBalls: this.accurateBalls,
                    thrownBalls: this.thrownBalls,
                    roundedAccuracy: this.roundedAccuracy,
                })
                    .then(function (response) {
                        app.accurateBalls = 0;
                        app.thrownBalls = 0;
                        app.accuracy = 0;
                        app.roundedAccuracy = 0;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            accurateShot()
            {
                this.accurateBalls += 1;
                this.thrownBalls += 1;
                this.accuracy = this.accurateBalls / this.thrownBalls * 100;
                this.roundedAccuracy = this.accuracy % 1 === 0 ? this.accuracy : this.accuracy.toFixed(2);
            },
            missedThrow()
            {
                this.thrownBalls += 1;
                this.accuracy = this.accurateBalls / this.thrownBalls * 100;
                this.roundedAccuracy = this.accuracy % 1 === 0 ? this.accuracy : this.accuracy.toFixed(2);
            },
            goToData()
            {
                window.location = 'data.html';
            }
    },
    created() {
    },
    computed: {
    }

    }).mount('#app')
</script>
</html>