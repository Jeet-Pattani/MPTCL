<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <script src="https://kit.fontawesome.com/a81f64a541.js" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider-extensions.min.js" crossorigin="anonymous" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous"> -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="css/style1.css">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            color: black;
        }
    </style>
</head>

<body>
    <?php
    include 'sidebar.php';
    ?>

    <div class="content" id="dynamic-content">

        <div class="ticker-wrapper">
            <div class="ticker-container">
                <div class="stocks-ticker" id="marquee-container"></div>
            </div>
        </div>

        <div class="indices-box">
            <div id="index-container">

            </div>
        </div>

        <div class="market-data-table">
            <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-quotes.js" async>
                {
                "width": "100%",
                "height": "100%",
                "symbolsGroups": [
                    {
                    "name": "Indices",
                    "originalName": "Indices",
                    "symbols": [
                        {
                        "name": "FOREXCOM:SPXUSD",
                        "displayName": "S&P 500"
                        },
                        {
                        "name": "FOREXCOM:NSXUSD",
                        "displayName": "US 100"
                        },
                        {
                        "name": "FOREXCOM:DJI",
                        "displayName": "Dow 30"
                        },
                        {
                        "name": "INDEX:NKY",
                        "displayName": "Nikkei 225"
                        },
                        {
                        "name": "INDEX:DEU40",
                        "displayName": "DAX Index"
                        },
                        {
                        "name": "FOREXCOM:UKXGBP",
                        "displayName": "UK 100"
                        }
                    ]
                    },
                    {
                    "name": "Futures",
                    "originalName": "Futures",
                    "symbols": [
                        {
                        "name": "CME_MINI:ES1!",
                        "displayName": "S&P 500"
                        },
                        {
                        "name": "CME:6E1!",
                        "displayName": "Euro"
                        },
                        {
                        "name": "COMEX:GC1!",
                        "displayName": "Gold"
                        },
                        {
                        "name": "NYMEX:CL1!",
                        "displayName": "Oil"
                        },
                        {
                        "name": "NYMEX:NG1!",
                        "displayName": "Gas"
                        },
                        {
                        "name": "CBOT:ZC1!",
                        "displayName": "Corn"
                        }
                    ]
                    },
                    {
                    "name": "Bonds",
                    "originalName": "Bonds",
                    "symbols": [
                        {
                        "name": "CME:GE1!",
                        "displayName": "Eurodollar"
                        },
                        {
                        "name": "CBOT:ZB1!",
                        "displayName": "T-Bond"
                        },
                        {
                        "name": "CBOT:UB1!",
                        "displayName": "Ultra T-Bond"
                        },
                        {
                        "name": "EUREX:FGBL1!",
                        "displayName": "Euro Bund"
                        },
                        {
                        "name": "EUREX:FBTP1!",
                        "displayName": "Euro BTP"
                        },
                        {
                        "name": "EUREX:FGBM1!",
                        "displayName": "Euro BOBL"
                        }
                    ]
                    },
                    {
                    "name": "Forex",
                    "originalName": "Forex",
                    "symbols": [
                        {
                        "name": "FX:EURUSD",
                        "displayName": "EUR to USD"
                        },
                        {
                        "name": "FX:GBPUSD",
                        "displayName": "GBP to USD"
                        },
                        {
                        "name": "FX:USDJPY",
                        "displayName": "USD to JPY"
                        },
                        {
                        "name": "FX:USDCHF",
                        "displayName": "USD to CHF"
                        },
                        {
                        "name": "FX:AUDUSD",
                        "displayName": "AUD to USD"
                        },
                        {
                        "name": "FX:USDCAD",
                        "displayName": "USD to CAD"
                        }
                    ]
                    },
                    {
                    "name": "Economy",
                    "symbols": [
                        {
                        "name": "ECONOMICS:INGDP"
                        },
                        {
                        "name": "FRED:SP500"
                        },
                        {
                        "name": "ECONOMICS:ININTR"
                        },
                        {
                        "name": "ECONOMICS:USINTR"
                        },
                        {
                        "name": "FRED:NIKKEI225"
                        }
                    ]
                    }
                ],
                "showSymbolLogo": true,
                "colorTheme": "dark",
                "isTransparent": false,
                "locale": "in"
                }
                </script>
                </div>
            <!-- TradingView Widget END -->
        </div>

        <div class="forex-table">
            <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
                {
                "width": "100%",
                "height": "100%",
                "currencies": [
                    "EUR",
                    "USD",
                    "JPY",
                    "GBP",
                    "CHF",
                    "AUD",
                    "CAD",
                    "CNY",
                    "SGD",
                    "INR"
                ],
                "isTransparent": false,
                "colorTheme": "dark",
                "locale": "in"
                }
                </script>
                </div>
            <!-- TradingView Widget END -->
        </div>

        <div class="crypto-data-table">
            <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
                {
                "width": "100%",
                "height": "100%",
                "defaultColumn": "overview",
                "screener_type": "crypto_mkt",
                "displayCurrency": "USD",
                "colorTheme": "dark",
                "locale": "in"
                }
                </script>
                </div>
            <!-- TradingView Widget END -->
        </div>

        <div class="widget-wrapper">

            <div class="tv-widget">
                <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-hotlists.js" async>
                    {
                    "colorTheme": "dark",
                    "dateRange": "3M",
                    "exchange": "NASDAQ",
                    "showChart": true,
                    "locale": "in",
                    "largeChartUrl": "",
                    "isTransparent": false,
                    "showSymbolLogo": false,
                    "showFloatingTooltip": true,
                    "width": "400",
                    "height": "600",
                    "plotLineColorGrowing": "rgba(41, 98, 255, 1)",
                    "plotLineColorFalling": "rgba(41, 98, 255, 1)",
                    "gridLineColor": "rgba(240, 243, 250, 0)",
                    "scaleFontColor": "rgba(106, 109, 120, 1)",
                    "belowLineFillColorGrowing": "rgba(41, 98, 255, 0.12)",
                    "belowLineFillColorFalling": "rgba(41, 98, 255, 0.12)",
                    "belowLineFillColorGrowingBottom": "rgba(41, 98, 255, 0)",
                    "belowLineFillColorFallingBottom": "rgba(41, 98, 255, 0)",
                    "symbolActiveColor": "rgba(41, 98, 255, 0.12)"
                    }
                    </script>
                    </div>
                <!-- TradingView Widget END -->
            </div>

            <div class="tv-widget">
                <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-hotlists.js" async>
                    {
                    "colorTheme": "dark",
                    "dateRange": "12M",
                    "exchange": "BSE",
                    "showChart": true,
                    "locale": "in",
                    "width": "100%",
                    "height": "100%",
                    "largeChartUrl": "",
                    "isTransparent": false,
                    "showSymbolLogo": false,
                    "showFloatingTooltip": true,
                    "plotLineColorGrowing": "rgba(41, 98, 255, 1)",
                    "plotLineColorFalling": "rgba(41, 98, 255, 1)",
                    "gridLineColor": "rgba(240, 243, 250, 0)",
                    "scaleFontColor": "rgba(106, 109, 120, 1)",
                    "belowLineFillColorGrowing": "rgba(41, 98, 255, 0.12)",
                    "belowLineFillColorFalling": "rgba(41, 98, 255, 0.12)",
                    "belowLineFillColorGrowingBottom": "rgba(41, 98, 255, 0)",
                    "belowLineFillColorFallingBottom": "rgba(41, 98, 255, 0)",
                    "symbolActiveColor": "rgba(41, 98, 255, 0.12)"
                    }
                    </script>
                    </div>
                <!-- TradingView Widget END -->
            </div>

        </div>


    </div>




  <!-- js to fetch live stock prices -->
  <script src="js/fetchPrices.js"></script>
</body>

</html>