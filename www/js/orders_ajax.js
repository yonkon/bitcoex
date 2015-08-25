$(document).ready(
    function(){
        var $buy_form = $('buy_form');
        var $sell_form = $('sell_form');
        $('#buy_calculate').click(
            function(e) {
                buy_count = $('#buy_count').val();
                buy_price = $('#buy_price').val();
                commission_ratio = 0.01;
                buy_total = parseFloat(buy_count) * parseFloat(buy_price);
                commission = commission_ratio*buy_total;
                buy_total_usr = buy_total - commission;
                $('#buy_total').text(buy_total_usr);
                $('#buy_commission').text(commission);
                e.preventDefault();
                e.stopPropagation();
            }
        );
        $('#sell_calculate').click(
            function(e) {
                sell_count = $('#sell_count').val();
                sell_price = $('#sell_price').val();
                commission_ratio = 0.01;
                commission = commission_ratio*sell_count;
                sell_total_usr = parseFloat(sell_count) - commission;
                sell_total = sell_total_usr * parseFloat(sell_price);
                $('#sell_total').text(sell_total);
                $('#sell_commission').text(commission);
                e.preventDefault();
                e.stopPropagation();
            }
        );
    }
);