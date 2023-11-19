<?php
session_start();
include "dbconfig.php";

// Check if the user is logged in
if (!isset($_SESSION['angel_login']) || $_SESSION['angel_login'] !== true) {
    include 'sidebar.php';
    echo '<div class="content"><h1>Login To Access</h1></div>';
    exit; // Stop executing the rest of the code
} else {
    $clientCode = $_SESSION['clientcode'];
    $sql = "SELECT jwttoken, apikey FROM angel_login WHERE clientcode = '$clientCode'";
    // Execute the query
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $jwtToken = $row['jwttoken'];
    $apiKey = $row['apikey'];
    $_SESSION['jwtToken'] = $jwtToken;
    $_SESSION['apiKey'] = $apiKey;
}
// Automate historical data request date and time
$currentDate = date('Y-m-d');
$fromDateTime = $currentDate . ' 09:15';
$toDateTime = date('Y-m-d H:i', strtotime('270 minute'));

$setTimeVisible = 'true'; //used by the charts to show only date

$currentDate = date('Y-m-d');
    
// Get the day of the week (1 for Monday, 7 for Sunday)
$dayOfWeek = date('N');

// // Subtract one day
// $previousDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));

// Subtract days based on the day of the week
if ($dayOfWeek == 6 || $dayOfWeek == 7) {
    $previousDate = date('Y-m-d', strtotime($currentDate . ' -' . ($dayOfWeek == 7 ? 2 : 1) . ' day'));
} else {
    $previousDate = $currentDate;
}

// Combine with time to create the fromDateTime
$fromDateTime = $previousDate . ' 09:15';

// Get the current date and time
$toDateTime = date('Y-m-d H:i', strtotime('now'));

$dataHistorical = json_encode([
    'exchange' => $_GET['exchange'],
    'symboltoken' => $_GET['token'],
    'interval' => 'ONE_MINUTE',
    'fromdate' => $fromDateTime,
    'todate' => $toDateTime
]);
if(isset($_POST['1d'])){
    //$setTimeVisible = 'true'; //used by the charts to show only date
    // Get the current date
    $currentDate = date('Y-m-d');
    
    // Get the day of the week (1 for Monday, 7 for Sunday)
    $dayOfWeek = date('N');

    // // Subtract one day
    // $previousDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));

    // Subtract days based on the day of the week
    if ($dayOfWeek == 6 || $dayOfWeek == 7) {
        $previousDate = date('Y-m-d', strtotime($currentDate . ' -' . ($dayOfWeek == 7 ? 2 : 1) . ' day'));
    } else {
        $previousDate = $currentDate;
    }

    // Combine with time to create the fromDateTime
    $fromDateTime = $previousDate . ' 09:15';

    // Get the current date and time
    $toDateTime = date('Y-m-d H:i', strtotime('now'));

    $dataHistorical = json_encode([
        'exchange' => $_GET['exchange'],
        'symboltoken' => $_GET['token'],
        'interval' => 'ONE_MINUTE',
        'fromdate' => $fromDateTime,
        'todate' => $toDateTime
    ]);
}
if(isset($_POST['1w'])){
    //$setTimeVisible = true; //used by the charts to show only date
       // Get the current date
       $currentDate = date('Y-m-d');

       // Subtract one day
       $previousDate = date('Y-m-d', strtotime($currentDate . ' -7 day'));
   
       // Combine with time to create the fromDateTime
       $fromDateTime = $previousDate . ' 09:15';
   
       // Get the current date and time
       $toDateTime = date('Y-m-d H:i', strtotime('now'));
   
       $dataHistorical = json_encode([
           'exchange' => $_GET['exchange'],
           'symboltoken' => $_GET['token'],
           'interval' => 'ONE_MINUTE',
           'fromdate' => $fromDateTime,
           'todate' => $toDateTime
       ]);
}
if(isset($_POST['1m'])){
   // $setTimeVisible = true; //used by the charts to show only date
        // Get the current date
        $currentDate = date('Y-m-d');

        // Subtract one day
        $previousDate = date('Y-m-d', strtotime($currentDate . ' -30 day'));
    
        // Combine with time to create the fromDateTime
        $fromDateTime = $previousDate . ' 09:15';
    
        // Get the current date and time
        $toDateTime = date('Y-m-d H:i', strtotime('now'));
    
        $dataHistorical = json_encode([
            'exchange' => $_GET['exchange'],
            'symboltoken' => $_GET['token'],
            'interval' => 'ONE_MINUTE',
            'fromdate' => $fromDateTime,
            'todate' => $toDateTime
        ]);
}
if(isset($_POST['3m'])){
    //$setTimeVisible = true; //used by the charts to show only date
       // Get the current date
       $currentDate = date('Y-m-d');

       // Subtract one day
       $previousDate = date('Y-m-d', strtotime($currentDate . ' -90 day'));
   
       // Combine with time to create the fromDateTime
       $fromDateTime = $previousDate . ' 09:15';
   
       // Get the current date and time
       $toDateTime = date('Y-m-d H:i', strtotime('now'));
   
       $dataHistorical = json_encode([
           'exchange' => $_GET['exchange'],
           'symboltoken' => $_GET['token'],
           'interval' => 'ONE_HOUR',
           'fromdate' => $fromDateTime,
           'todate' => $toDateTime
       ]);
}
if(isset($_POST['6m'])){
   // $setTimeVisible = true; //used by the charts to show only date
    // Get the current date
    $currentDate = date('Y-m-d');

    // Subtract one day
    $previousDate = date('Y-m-d', strtotime($currentDate . ' -180 day'));

    // Combine with time to create the fromDateTime
    $fromDateTime = $previousDate . ' 09:15';

    // Get the current date and time
    $toDateTime = date('Y-m-d H:i', strtotime('now'));

    $dataHistorical = json_encode([
        'exchange' => $_GET['exchange'],
        'symboltoken' => $_GET['token'],
        'interval' => 'ONE_HOUR',
        'fromdate' => $fromDateTime,
        'todate' => $toDateTime
    ]);
}
if(isset($_POST['1y'])){

    $setTimeVisible = 'false'; //used by the charts to show only date

    // Get the current date
    $currentDate = date('Y-m-d');

    // Subtract one day
    $previousDate = date('Y-m-d', strtotime($currentDate . ' -365 day'));

    // Combine with time to create the fromDateTime
    $fromDateTime = $previousDate . ' 09:15';

    // Get the current date and time
    $toDateTime = date('Y-m-d H:i', strtotime('now'));

    $dataHistorical = json_encode([
        'exchange' => $_GET['exchange'],
        'symboltoken' => $_GET['token'],
        'interval' => 'ONE_DAY',
        'fromdate' => $fromDateTime,
        'todate' => $toDateTime
    ]);
}
if(isset($_POST['5y'])){
        $setTimeVisible = 'false'; //used by the charts to show only date
        echo $setTimeVisible;
       // Get the current date
       $currentDate = date('Y-m-d');

       // Subtract one day
       $previousDate = date('Y-m-d', strtotime($currentDate . ' -2000 day'));
   
       // Combine with time to create the fromDateTime
       $fromDateTime = $previousDate . ' 09:15';
   
       // Get the current date and time
       $toDateTime = date('Y-m-d H:i', strtotime('now'));
   
       $dataHistorical = json_encode([
           'exchange' => $_GET['exchange'],
           'symboltoken' => $_GET['token'],
           'interval' => 'ONE_DAY',
           'fromdate' => $fromDateTime,
           'todate' => $toDateTime
       ]);
}

/* // use below to get test data
// Get the current date
$currentDate = date('Y-m-d');

// Subtract one day
$previousDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));

// Combine with time to create the fromDateTime
$fromDateTime = $previousDate . ' 09:15';

// Get the current date and time
$toDateTime = date('Y-m-d H:i', strtotime('now'));
   

$dataHistorical = json_encode([
    'exchange' => $_GET['exchange'],
    'symboltoken' => $_GET['token'],
    'interval' => 'ONE_MINUTE',
    'fromdate' => '2023-11-17 09:15',
    'todate' => '2023-11-17 15:30'
]);
 */

$urlHistorical = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData';

$headersHistorical = [
    'Authorization: Bearer ' . $jwtToken,
    'Content-Type: application/json',
    'Accept: application/json',
    'X-UserType: USER',
    'X-SourceID: WEB',
    'X-ClientLocalIP: CLIENT_LOCAL_IP',
    'X-ClientPublicIP: CLIENT_PUBLIC_IP',
    'X-MACAddress: MAC_ADDRESS',
    'X-PrivateKey: ' . $apiKey
];

$chHistorical = curl_init();

curl_setopt($chHistorical, CURLOPT_URL, $urlHistorical);
curl_setopt($chHistorical, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chHistorical, CURLOPT_POST, 1);
curl_setopt($chHistorical, CURLOPT_POSTFIELDS, $dataHistorical);
curl_setopt($chHistorical, CURLOPT_HTTPHEADER, $headersHistorical);

$responseHistorical = curl_exec($chHistorical);

if (curl_errno($chHistorical)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Error fetching historical data']);
    exit;
}

curl_close($chHistorical);
// Decode the historical data response
$historicalData = json_decode($responseHistorical, true);

$dataArray = json_decode($responseHistorical, true)['data'];

$formattedData = [];
foreach ($dataArray as $item) {
    $formattedData[] = [
        'time'   => strtotime($item[0]) + 19800,// we faced an issue in converting the timestamp given by the angel historical API. The time when converted to unix seconds was shown 5.5Hrs behind and hence we have added the difference of 5.5Hrs in seconds which is 19800.
        'open'   => $item[1],
        'high'   => $item[2],
        'low'    => $item[3],
        'close'  => $item[4],
        'volume' => $item[5],
    ];
}
include 'getChartData.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image.png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="css/trade.css">
            <!-- Adding the standalone version of Lightweight charts -->
            <script
          type="text/javascript"
          src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"
        ></script>
        <!-- Resources -->
        <!-- <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/stock.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script> -->
        <!-- Styles -->
        <style>
            #chartcontrols {
                height: auto;
                padding: 5px 5px 0 16px;
                max-width: 100%;
            }

            #chartdiv {
                width: 100%;
                /* height: 600px; */
                max-width: 100%;
            }

            .chartContainer {
                height: 400px;
                width: 800px;
                margin: 1rem auto 7rem;
            }

            .chart-container{
            /* position: absolute; */
            height: 400px;
                width: 800px;
          }
        </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1 id="trend" class="neutral">It seems Neutral</h1>
          <!-- <div class="chartContainer">
                <div id="chartcontrols"></div>
                <div id="chartdiv"></div>
          </div> -->
            <div class="chart-container" id="chart1"></div>
            <div class="buttonsContainer">
                <form action="" method="post">
                    <button name="1d">1D</button>
                    <button name="1w">1W</button>
                    <button name="1m">1M</button>
                    <button name="3m">3M</button>
                    <button name="6m">6M</button>
                    <button name="1y" id="one_year">1Y</button>
                    <button name="5y" id="five_year">5Y</button>
                    <button name="custom">Custom</button>
                </form>
            </div>
<script>
    // Lightweight Charts™ Example: Tracking Tooltip
    // https://tradingview.github.io/lightweight-charts/tutorials/how_to/tooltips



    function generateCandlestickData() {
        const candlestickData = <?php echo json_encode($formattedData, JSON_PRETTY_PRINT); ?>;
        return candlestickData;
    }

    var chartOptions = {
        layout: {
            background: { color: "#050505" },
            textColor: "#C3BCDB",
        },
        grid: {
            vertLines: { color: "#444" },
            horzLines: { color: "#444" },
        },
        timeScale: {
            timeVisible: <?php echo $setTimeVisible ?>, // Show time on the time axis
            secondsVisible: false, // You can adjust this based on your data
        },
    };


    /** @type {import('lightweight-charts').IChartApi} */
    const chart = LightweightCharts.createChart(document.getElementById('chart1'), chartOptions);

    // Setting the border color for the vertical axis
    chart.priceScale().applyOptions({
        borderColor: "#71649C",
        scaleMargins: {
            top: 0.8,
            bottom: 0,
        },
    });

    // Setting the border color for the horizontal axis
    chart.timeScale().applyOptions({
        borderColor: "#71649C",
    });

    chart.applyOptions({
        crosshair: {
            // hide the horizontal crosshair line
            horzLine: {
                visible: false,
                labelVisible: false,
            },
            // hide the vertical crosshair label
            vertLine: {
                labelVisible: false,
            },
        },
        // hide the grid lines
        grid: {
            vertLines: {
                visible: false,
            },
            horzLines: {
                visible: false,
            },
        },
    });


    

    const candleStickData = generateCandlestickData();

    // Create the Main Series (Candlesticks)
    const mainSeries = chart.addCandlestickSeries();

    // Set the data for the Main Series, adjusting for time zone offset
    mainSeries.setData(candleStickData);
    

    // Create the Volume Series (Histogram)
    const volumeData = candleStickData.map(datapoint => ({
    time: datapoint.time,
    value: datapoint.volume, // Ensure that the volume is correctly mapped
    color: datapoint.close >= datapoint.open ? '#26A69Ab3' : '#EF5350b3', // Adjust the condition as needed
    }));


    const volumeSeries = chart.addHistogramSeries({
        priceFormat: {
            type: 'volume',
        },
        priceScaleId: '', // set as an overlay by setting a blank priceScaleId
    });

    volumeSeries.priceScale().applyOptions({
        // set the positioning of the volume series
        scaleMargins: {
            top: 0.7, // highest point of the series will be 70% away from the top
            bottom: 0,
        },
    });

    mainSeries.priceScale().applyOptions({
        scaleMargins: {
            top: 0.1, // highest point of the series will be 10% away from the top
            bottom: 0.4, // lowest point will be 40% away from the bottom
        },
    });

    // Set the data for the Volume Series
    volumeSeries.setData(volumeData);

    /* Code to embed a tooltip  starts */
    const container = document.getElementById('chart1');

    const toolTipWidth = 160;
    const toolTipHeight = 180;
    const toolTipMargin = 15;
    // Create and style the tooltip html element
    const toolTip = document.createElement('div');
    toolTip.style = `width: 160px; height: 180px; position: absolute; display: none; padding: 8px; box-sizing: border-box; font-size: 12px; text-align: left; z-index: 1000; top: 12px; left: 12px; pointer-events: none; border: 1px solid; border-radius: 2px;font-family: -apple-system, BlinkMacSystemFont, 'Trebuchet MS', Roboto, Ubuntu, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;`;
    toolTip.style.background = 'black';
    toolTip.style.color = 'white';
    toolTip.style.borderColor = '#2962FF';
    container.appendChild(toolTip);

    // update tooltip
    chart.subscribeCrosshairMove(param => {
        if (
            param.point === undefined ||
            !param.time ||
            param.point.x < 0 ||
            param.point.x > container.clientWidth ||
            param.point.y < 0 ||
            param.point.y > container.clientHeight
        ) {
            toolTip.style.display = 'none';
        } else {
            // time will be in the same format that we supplied to setData.
            // thus it will be YYYY-MM-DD
            var timestamp = param.time;

            // Create a new Date object with the timestamp in milliseconds
            var date = new Date(timestamp * 1000);

            // Subtract 5 hours and 30 minutes
            date.setHours(date.getHours() - 5);
            date.setMinutes(date.getMinutes() - 30);

            // Get the individual components of the adjusted date
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2); // Months are zero-based
            var day = ("0" + date.getDate()).slice(-2);
            var hours = ("0" + date.getHours()).slice(-2);
            var minutes = ("0" + date.getMinutes()).slice(-2);
            var seconds = ("0" + date.getSeconds()).slice(-2);

            //show hours and minutes only if it is there in the data
            if(hours === '00'){
            // Create a formatted string with the date and time
            var formattedDate = year + "-" + month + "-" + day //+ " " + hours + ":" + minutes + ":" + seconds;
            } else{
            // Create a formatted string with the date and time
            var formattedDate = year + "-" + month + "-" + day + " " + hours + ":" + minutes// + ":" + seconds;
            }


            const dateStr = formattedDate;
            toolTip.style.display = 'block';
            const data = param.seriesData.get(mainSeries);
            const dataVol = param.seriesData.get(volumeSeries);
            const open = data.value !== undefined ? data.value : data.open;
            const high = data.value !== undefined ? data.value : data.high;
            const low = data.value !== undefined ? data.value : data.low;
            const close = data.value !== undefined ? data.value : data.close;
            const volume = dataVol.value !== undefined ? dataVol.value : dataVol.volume;
            const formattedVolume = volume.toLocaleString();
            toolTip.innerHTML = `<div style="color: ${'#2962FF'};font-size: 15px;"><?php echo $_GET['symbol'];?></div>
            <div style="font-size: 15px; margin: 4px 0px; color: ${'white'}">
                Open: ${Math.round(100 * open) / 100}
                </div>
                <div style="font-size: 15px; margin: 4px 0px; color: ${'white'}">
                High: ${Math.round(100 * high) / 100}
                </div>
                <div style="font-size: 15px; margin: 4px 0px; color: ${'white'}">
                Low: ${Math.round(100 * low) / 100}
                </div>
                <div style="font-size: 15px; margin: 4px 0px; color: ${'white'}">
                Close: ${Math.round(100 * close) / 100}
                </div>
                <div style="font-size: 15px; margin: 4px 0px; color: ${'white'}">
                Volume: ${formattedVolume}
                </div>
                <div style="color: ${'white'};font-size: 13px;">
                ${dateStr}
                </div>`;

            const coordinate = mainSeries.priceToCoordinate(open);
            let shiftedCoordinate = param.point.x - 50;
            if (coordinate === null) {
                return;
            }
            shiftedCoordinate = Math.max(
                0,
                Math.min(container.clientWidth - toolTipWidth, shiftedCoordinate)
            );
            const coordinateY =
                coordinate - toolTipHeight - toolTipMargin > 0
                    ? coordinate - toolTipHeight - toolTipMargin
                    : Math.max(
                        0,
                        Math.min(
                            container.clientHeight - toolTipHeight - toolTipMargin,
                            coordinate + toolTipMargin
                        )
                    );
            toolTip.style.left = shiftedCoordinate + 'px';
            toolTip.style.top = coordinateY + 'px';
        }
    });
    /*Code to embed a tooltip ends*/
    
   // chart.timeScale().fitContent();
</script>

        <table class="stock-data" id="stock-info-table">
            <!-- Data will be populated using JavaScript -->
        </table>

        <div class="stock-info">Stock Price Data</div>
        <table class="stock-data" id="stock-data-table-1">
            <!-- Data will be populated using JavaScript -->
        </table>
        <table class="stock-data" id="stock-data-table-2">
            <!-- Data will be populated using JavaScript -->
        </table>

        <div class="table-container">
            <h2 class="stock-info">Depth</h2>
            <table id="depth-table">
                <!-- Data will be populated using JavaScript -->
            </table>
        </div>

        <!-- Quantity input with plus and minus buttons -->
        <div class="quantity-input">
            <span>Quantity:</span>
            <button id="decrease-quantity">-</button>
            <input type="text" id="quantity" name="quantity" value="1">
            <button id="increase-quantity">+</button>
        </div>

        <!-- Buy and Sell buttons within a form -->
        <form action='buy_sell_handler.php' method='post'>
            <input type='hidden' id="token" name='token' value='<?php echo $_GET['token']; ?>'>
            <input type='hidden' id="name" name='name' value='<?php echo $_GET['name']; ?>'>
            <input type='hidden' id="symbol" name='symbol' value='<?php echo $_GET['symbol']; ?>'>
            <input type='hidden' id="exchange" name='exchange' value='<?php echo $_GET['exchange']; ?>'>
            <input type="hidden" id="action" name="action" value="buy"> <!-- Default action -->
            <button class='buy-button' type='submit' id="buy-button">Buy</button>
            <button class='sell-button' type='submit' id="sell-button">Sell</button>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?php include "updateStockPrices.php"; ?>
</body>

</html>