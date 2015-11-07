<?php
/**
 * @var Transaction[] $transactionGroups
 */

$stockData = '';
if (!empty($transactionGroups)) {
    $stockData = array();
    foreach ($transactionGroups as $trans => $transItem) {
        $stockData[] = "[ '{$trans}', {$transItem['open']}, {$transItem['hi']}, {$transItem['low']}, {$transItem['close']}, {$transItem['volume']} ]";
    }
    $stockData = join(', ', $stockData);
}
$stockData = "[ {$stockData} ]";
?>
    var stockData = <?php echo $stockData;?> ;
    var plot;

    var drawOHLCV = function(values) {
        $.jqplot.config.enablePlugins = true;
        window.plot = $.jqplot('jqplot', [values], {
            title: 'Котировки',
            axesDefaults: {},
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickOptions: {formatString: '%d %b%n%H:%M'}
                },
                yaxis: {
                    tickOptions: {prefix: '$'}
                }
            },
            series: [{renderer: $.jqplot.OHLCRenderer, rendererOptions: {candleStick: true}}],
            cursor: {
                zoom: true,
                tooltipOffset: 10,
                tooltipLocation: 'nw'
            },
            highlighter: {
                showMarker: false,
                tooltipAxes: 'xy',
                yvalues: 5 ,
                formatString: '<table class="jqplot-highlighter"> \
          <tr><td>date:</td><td>%s</td></tr> \
          <tr><td>open:</td><td>%s</td></tr> \
          <tr><td>hi:</td><td>%s</td></tr> \
          <tr><td>low:</td><td>%s</td></tr> \
          <tr><td>close:</td><td>%s</td></tr> \
          <tr><td>volume:</td><td>%s</td></tr> \
            </table>'
            }
        });
    };
    var testData = false;
    if (testData) {
        for(var i = 0; i<stockData.length; i++) {
            stockData[i][2] = stockData[i][1] + Math.floor((Math.random() * 100) + 30);
            stockData[i][3] = stockData[i][1] - Math.floor((Math.random() * 100) );
            stockData[i][4] = stockData[i][2]-30;
            stockData[i][5] = Math.floor((Math.random() * 100) + 1)
        }
    }
    drawOHLCV(stockData);

$('document').ready(function(){
    $(window).resize(function(){
        window.plot.replot({resetAxis : true});
    });
});
