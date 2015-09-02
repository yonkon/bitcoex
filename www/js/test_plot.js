$(document).ready(function () {

    /* // Add a new localization
     $.jsDate.regional['it'] = {
     monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
     monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'],
     dayNames: ['Domenica','Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato'],
     dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
     formatString: '%d-%m-%Y %H:%M:%S'
     };
     // Do not forget to call
     $.jsDate.regional.getLocale();
     */
    ohlc = [['07/06/2009', 138.7, 139.68, 135.18, 135.4],
        ['06/29/2009', 143.46, 144.66, 139.79, 140.02, 140.02],
        ['06/22/2009', 140.67, 143.56, 132.88, 142.44],
        ['06/15/2009', 136.01, 139.5, 134.53, 139.48],
        ['06/08/2009', 143.82, 144.56, 136.04, 136.97],
        ['06/01/2009', 136.47, 146.4, 136, 144.67],
        ['05/26/2009', 124.76, 135.9, 124.55, 135.81],
        ['05/18/2009', 123.73, 129.31, 121.57, 122.5],
        ['05/11/2009', 127.37, 130.96, 119.38, 122.42],
        ['05/04/2009', 128.24, 133.5, 126.26, 129.19],
        ['04/27/2009', 122.9, 127.95, 122.66, 127.24],
        ['04/20/2009', 121.73, 127.2, 118.6, 123.9],
        ['04/13/2009', 120.01, 124.25, 115.76, 123.42],
        ['04/06/2009', 114.94, 120, 113.28, 119.57],
        ['03/30/2009', 104.51, 116.13, 102.61, 115.99],
        ['03/23/2009', 102.71, 109.98, 101.75, 106.85],
        ['03/16/2009', 96.53, 103.48, 94.18, 101.59],
        ['03/09/2009', 84.18, 97.2, 82.57, 95.93],
        ['03/02/2009', 88.12, 92.77, 82.33, 85.3],
        ['02/23/2009', 91.65, 92.92, 86.51, 89.31],
        ['02/17/2009', 96.87, 97.04, 89, 91.2],
        ['02/09/2009', 100, 103, 95.77, 99.16],
        ['02/02/2009', 89.1, 100, 88.9, 99.72],
        ['01/26/2009', 88.86, 95, 88.3, 90.13],
        ['01/20/2009', 81.93, 90, 78.2, 88.36],
        ['01/12/2009', 90.46, 90.99, 80.05, 82.33],
        ['01/05/2009', 93.17, 97.17, 90.04, 90.58],
        ['12/29/2008', 86.52, 91.04, 84.72, 90.75],
        ['12/22/2008', 90.02, 90.03, 84.55, 85.81],
        ['12/15/2008', 95.99, 96.48, 88.02, 90],
        ['12/08/2008', 97.28, 103.6, 92.53, 98.27],
        ['12/01/2008', 91.3, 96.23, 86.5, 94],
        ['11/24/2008', 85.21, 95.25, 84.84, 92.67],
        ['11/17/2008', 88.48, 91.58, 79.14, 82.58],
        ['11/10/2008', 100.17, 100.4, 86.02, 90.24],
        ['11/03/2008', 105.93, 111.79, 95.72, 98.24],
        ['10/27/2008', 95.07, 112.19, 91.86, 107.59],
        ['10/20/2008', 99.78, 101.25, 90.11, 96.38],
        ['10/13/2008', 104.55, 116.4, 85.89, 97.4],
        ['10/06/2008', 91.96, 101.5, 85, 96.8],
        ['09/29/2008', 119.62, 119.68, 94.65, 97.07],
        ['09/22/2008', 139.94, 140.25, 123, 128.24],
        ['09/15/2008', 142.03, 147.69, 120.68, 140.91],
        ['09/08/2008', 164.57, 164.89, 146, 148.94]
    ];

    $.jqplot.config.enablePlugins = true;
    plot = $.jqplot('jqplot', [ohlc], {
        title: 'Котировки',
        axesDefaults: {},
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickOptions: {formatString: '%d %b%n%H:%M:%S'}
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
            yvalues: 4,
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

    volumeData =
        [
            ['07/06/2009', 138],
            ['06/29/2009', 143],
            ['06/22/2009', 140],
            ['06/15/2009', 136],
            ['06/08/2009', 143],
            ['06/01/2009', 136],
            ['05/26/2009', 124],
            ['05/18/2009', 123],
            ['05/11/2009', 127],
            ['05/04/2009', 128],
            ['04/27/2009', 122],
            ['04/20/2009', 121],
            ['04/13/2009', 120],
            ['04/06/2009', 114],
            ['03/30/2009', 104],
            ['03/23/2009', 102],
            ['03/16/2009', 96.],
            ['03/09/2009', 84.],
            ['03/02/2009', 88.],
            ['02/23/2009', 91.],
            ['02/17/2009', 96.],
            ['02/09/2009', 100],
            ['02/02/2009', 89.],
            ['01/26/2009', 88.],
            ['01/20/2009', 81.],
            ['01/12/2009', 90.],
            ['01/05/2009', 93.],
            ['12/29/2008', 86.],
            ['12/22/2008', 90.],
            ['12/15/2008', 95.],
            ['12/08/2008', 97.],
            ['12/01/2008', 91.],
            ['11/24/2008', 85.],
            ['11/17/2008', 88.],
            ['11/10/2008', 100],
            ['11/03/2008', 105],
            ['10/27/2008', 95.],
            ['10/20/2008', 99.],
            ['10/13/2008', 104],
            ['10/06/2008', 91.],
            ['09/29/2008', 119],
            ['09/22/2008', 139],
            ['09/15/2008', 142],
            ['09/08/2008', 164]
        ];
});


