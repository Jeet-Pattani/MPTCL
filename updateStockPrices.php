<script>
    function updateStockData(stockData) {
        const trend = document.getElementById('trend');
        const stockInfoTable = document.getElementById('stock-info-table');
        const stockDataTable1 = document.getElementById('stock-data-table-1');
        const stockDataTable2 = document.getElementById('stock-data-table-2');
        const depthTable = document.getElementById('depth-table');
        const token = document.getElementById('token');
        const name = document.getElementById('name');
        const symbol = document.getElementById('symbol');
        const exchange = document.getElementById('exchange');

        trend.textContent = `It seems ${stockData.trend}`;
        token.value = stockData.token;
        name.value = stockData.name;
        symbol.value = stockData.symbol;
        exchange.value = stockData.exchange;

        stockInfoTable.innerHTML = `
            <tr><td><strong>Token:</strong></td><td>${stockData.symbolToken}</td></tr>
            <tr><td><strong>Name:</strong></td><td><?php echo $_GET['name'];?></td></tr>
            <tr><td><strong>Symbol:</strong></td><td>${stockData.tradingSymbol}</td></tr>
            <tr><td><strong>Exchange:</strong></td><td>${stockData.exchange}</td></tr>
        `;

        stockDataTable1.innerHTML = `
            <tr>
                <th>LTP</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
                <th>Percent Change</th>
                <th>Change</th>
            </tr>
            <tr>
                <td>${stockData.ltp}</td>
                <td>${stockData.open}</td>
                <td>${stockData.high}</td>
                <td>${stockData.low}</td>
                <td>${stockData.close}</td>
                <td>${new Intl.NumberFormat().format(stockData.tradeVolume)}</td>
                <td>${stockData.percentChange}%</td>
                <td>${stockData.netChange}</td>
            </tr>
        `;

        stockDataTable2.innerHTML = `
            <tr>
                <th>Last Traded Qty</th>
                <th>Avg Price</th>
                <th>Upper Circuit</th>
                <th>Lower Circuit</th>
                <th>52 Week High</th>
                <th>52 Week Low</th>
                <th>Exch Feed Time</th>
                <th>Exch Trade Time</th>
            </tr>
            <tr>
                <td>${stockData.lastTradeQty}</td>
                <td>${stockData.avgPrice}</td>
                <td>${stockData.high}</td>
                <td>${stockData.low}</td>
                <td>${stockData['52WeekHigh']}</td>
                <td>${stockData['52WeekLow']}</td>
                <td>${stockData.exchFeedTime}</td>
                <td>${stockData.exchTradeTime}</td>
            </tr>
        `;

        depthTable.innerHTML = `
                <thead>
                    <tr>
                        <th>Buy Price</th>
                        <th>Buy Quantity</th>
                        <th>Buy Orders</th>
                        <th>Sell Price</th>
                        <th>Sell Quantity</th>
                        <th>Sell Orders</th>
                    </tr>
                </thead>
                <tbody>
                    ${stockData.depth.buy.slice(0, 5).map((buy, index) =>
                        `<tr>
                            <td>${buy.price}</td>
                            <td>${buy.quantity}</td>
                            <td>${buy.orders}</td>
                            <td>${stockData.depth.sell[index].price}</td>
                            <td>${stockData.depth.sell[index].quantity}</td>
                            <td>${stockData.depth.sell[index].orders}</td>
                        </tr>`
                    ).join('')}
                </tbody>
            `;

        const ltp = stockData.ltp;
        const open = stockData.open;

        let trendText = 'Unknown'; // Default trend is unknown
        let trendClass = 'neutral'; // Default class is neutral

        if (ltp > open) {
            trendText = 'Bullish';
            trendClass = 'bullish';
        } else if (ltp < open) {
            trendText = 'Bearish';
            trendClass = 'bearish';
        }

        trend.textContent = `It Seems: ${trendText}`;
        trend.className = trendClass; // Add the appropriate class to the element

    }

    function fetchData() {
        const jwtToken = '<?php echo $jwtToken; ?>';
        const apiKey = '<?php echo $apiKey; ?>';
        const token = '<?php echo $_GET['token']; ?>';
        const exchange = '<?php echo $_GET['exchange']; ?>';

        // Data to be sent in the request
        const requestData = JSON.stringify({
            mode: 'FULL',
            exchangeTokens: {
                [exchange]: [token]
            }
        });

        // URL for the API
        const apiUrl = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/';

        // Set the request headers
        const requestHeaders = {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-UserType': 'USER',
            'X-SourceID': 'WEB',
            'X-ClientLocalIP': 'CLIENT_LOCAL_IP',
            'X-ClientPublicIP': 'CLIENT_PUBLIC_IP',
            'X-MACAddress': 'MAC_ADDRESS',
            'X-PrivateKey': apiKey,
        };

        // Perform the AJAX request to fetch the data
        $.ajax({
            type: 'POST',
            url: apiUrl,
            headers: requestHeaders,
            data: requestData,
            success: function (response) {
                console.log(response);
                // Handle the response and update the content
                const stockData = response['data']['fetched'][0];
                // const symbolToken = response['data']['fetched'][0].symbolToken;
                // const tradingSymbol = response['data']['fetched'][0].tradingSymbol;
                // const tokenName = 'get from the url';
                // console.log(symbolToken,tradingSymbol,tokenName);
                // console.log(stockData)
                updateStockData(stockData);
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }

    // Fetch data initially and every second
    fetchData(); // Fetch data initially
    setInterval(fetchData, 100000000); // Fetch data every 1000 ms (1 second)
</script>