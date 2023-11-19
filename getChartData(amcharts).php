

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/stock.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<!-- Chart code -->
<script>
        am5.ready(function() {
        
        // Create root element
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv");
        
        
        // Set themes
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
        am5themes_Animated.new(root)
        ]);
        
        
        // Create a stock chart
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/#Instantiating_the_chart
        var stockChart = root.container.children.push(am5stock.StockChart.new(root, {
        }));
        
        
        // Set global number format
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/concepts/formatters/formatting-numbers/
        root.numberFormatter.set("numberFormat", "#,###.00");
        
        
        // Create a main stock panel (chart)
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/#Adding_panels
        var mainPanel = stockChart.panels.push(am5stock.StockPanel.new(root, {
        wheelY: "zoomX",
        panX: true,
        panY: true
        }));
        
        
        // Create value axis
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var valueAxis = mainPanel.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {
            pan: "zoom"
        }),
        extraMin: 0.1, // adds some space for for main series
        tooltip: am5.Tooltip.new(root, {}),
        numberFormat: "#,###.00",
        extraTooltipPrecision: 2
        }));
        
        var dateAxis = mainPanel.xAxes.push(am5xy.GaplessDateAxis.new(root, {
        baseInterval: {
            timeUnit: "minute",
            count: 1
        },
        renderer: am5xy.AxisRendererX.new(root, {}),
        tooltip: am5.Tooltip.new(root, {})
        }));
        
        
        // Add series
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var tradingSymbol = '<?php echo $_GET["symbol"]; ?>';

        var valueSeries = mainPanel.series.push(am5xy.CandlestickSeries.new(root, {
            name: tradingSymbol,
            clustered: false,
            valueXField: "Date",
            valueYField: "Close",
            highValueYField: "High",
            lowValueYField: "Low",
            openValueYField: "Open",
            calculateAggregates: true,
            xAxis: dateAxis,
            yAxis: valueAxis,
            legendValueText: "open: [bold]{openValueY}[/] high: [bold]{highValueY}[/] low: [bold]{lowValueY}[/] close: [bold]{valueY}[/]",
            legendRangeValueText: ""
        }));
        
        
        // Set main value series
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/#Setting_main_series
        stockChart.set("stockSeries", valueSeries);
        
        
        // Add a stock legend
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/stock-legend/
        var valueLegend = mainPanel.plotContainer.children.push(am5stock.StockLegend.new(root, {
        stockChart: stockChart
        }));
        
        
        // Create volume axis
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var volumeAxisRenderer = am5xy.AxisRendererY.new(root, {
        inside: true
        });
        
        volumeAxisRenderer.labels.template.set("forceHidden", true);
        volumeAxisRenderer.grid.template.set("forceHidden", true);
        
        var volumeValueAxis = mainPanel.yAxes.push(am5xy.ValueAxis.new(root, {
        numberFormat: "#.#a",
        height: am5.percent(20),
        y: am5.percent(100),
        centerY: am5.percent(100),
        renderer: volumeAxisRenderer
        }));
        
        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var volumeSeries = mainPanel.series.push(am5xy.ColumnSeries.new(root, {
        name: "Volume",
        clustered: false,
        valueXField: "Date",
        valueYField: "Volume",
        xAxis: dateAxis,
        yAxis: volumeValueAxis,
        legendValueText: "[bold]{valueY.formatNumber('#,###.0a')}[/]"
        }));
        
        volumeSeries.columns.template.setAll({
        strokeOpacity: 0,
        fillOpacity: 0.5
        });
        
        // color columns by stock rules
        volumeSeries.columns.template.adapters.add("fill", function(fill, target) {
        var dataItem = target.dataItem;
        if (dataItem) {
            return stockChart.getVolumeColor(dataItem);
        }
        return fill;
        })
        
        
        // Set main series
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/#Setting_main_series
        stockChart.set("volumeSeries", volumeSeries);
        valueLegend.data.setAll([valueSeries, volumeSeries]);
        
        
        // Add cursor(s)
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        mainPanel.set("cursor", am5xy.XYCursor.new(root, {
        yAxis: valueAxis,
        xAxis: dateAxis,
        snapToSeries: [valueSeries],
        snapToSeriesBy: "y!"
        }));
        
        
        // Add scrollbar
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
        var scrollbar = mainPanel.set("scrollbarX", am5xy.XYChartScrollbar.new(root, {
        orientation: "horizontal",
        height: 50
        }));
        stockChart.toolsContainer.children.push(scrollbar);
        
        var sbDateAxis = scrollbar.chart.xAxes.push(am5xy.GaplessDateAxis.new(root, {
        baseInterval: {
            timeUnit: "minute",
            count: 1
        },
        renderer: am5xy.AxisRendererX.new(root, {})
        }));
        
        var sbValueAxis = scrollbar.chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {})
        }));
        
        var sbSeries = scrollbar.chart.series.push(am5xy.LineSeries.new(root, {
        valueYField: "Close",
        valueXField: "Date",
        xAxis: sbDateAxis,
        yAxis: sbValueAxis
        }));
        
        sbSeries.fills.template.setAll({
        visible: true,
        fillOpacity: 0.3
        });
        
        // Set up series type switcher
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/toolbar/series-type-control/
        var seriesSwitcher = am5stock.SeriesTypeControl.new(root, {
        stockChart: stockChart
        });
        
        seriesSwitcher.events.on("selected", function(ev) {
        setSeriesType(ev.item.id);
        });
        
        function getNewSettings(series) {
        var newSettings = [];
        am5.array.each(["name", "valueYField", "highValueYField", "lowValueYField", "openValueYField", "calculateAggregates", "valueXField", "xAxis", "yAxis", "legendValueText", "stroke", "fill"], function(setting) {
            newSettings[setting] = series.get(setting);
        });
        return newSettings;
        }
        
        function setSeriesType(seriesType) {
        // Get current series and its settings
        var currentSeries = stockChart.get("stockSeries");
        var newSettings = getNewSettings(currentSeries);
        
        // Remove previous series
        var data = currentSeries.data.values;
        mainPanel.series.removeValue(currentSeries);
        
        // Create new series
        var series;
        switch (seriesType) {
            case "line":
            series = mainPanel.series.push(am5xy.LineSeries.new(root, newSettings));
            break;
            case "candlestick":
            case "procandlestick":
            newSettings.clustered = false;
            series = mainPanel.series.push(am5xy.CandlestickSeries.new(root, newSettings));
            if (seriesType == "procandlestick") {
                series.columns.template.get("themeTags").push("pro");
            }
            break;
            case "ohlc":
            newSettings.clustered = false;
            series = mainPanel.series.push(am5xy.OHLCSeries.new(root, newSettings));
            break;
        }
        
        // Set new series as stockSeries
        if (series) {
            valueLegend.data.removeValue(currentSeries);
            series.data.setAll(data);
            stockChart.set("stockSeries", series);
            var cursor = mainPanel.get("cursor");
            if (cursor) {
            cursor.set("snapToSeries", [series]);
            }
            valueLegend.data.insertIndex(0, series);
        }
        }
        
        
        // Stock toolbar
        // -------------------------------------------------------------------------------
        // https://www.amcharts.com/docs/v5/charts/stock/toolbar/
        var toolbar = am5stock.StockToolbar.new(root, {
        container: document.getElementById("chartcontrols"),
        stockChart: stockChart,
        controls: [
            am5stock.IndicatorControl.new(root, {
            stockChart: stockChart,
            legend: valueLegend
            }),
            seriesSwitcher,
            am5stock.DrawingControl.new(root, {
            stockChart: stockChart
            }),
            am5stock.ResetControl.new(root, {
            stockChart: stockChart
            }),
            am5stock.SettingsControl.new(root, {
            stockChart: stockChart
            })
        ]
        })
        
        // data
        var data = <?php echo json_encode($amChartsData, JSON_PRETTY_PRINT); ?>
        
        // set data to all series
        valueSeries.data.setAll(data);
        volumeSeries.data.setAll(data);
        sbSeries.data.setAll(data);
        
        }); // end am5.ready()
</script>