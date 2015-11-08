var COMISSION_RATIO_BUY = 0.00;
var COMISSION_RATIO_SELL = 0.00;

function processAjaxSuccess(data, cb, callOnError) {
    try {
        data = JSON.parse(data);
        if (data.status == "OK") {
            if (typeof cb != 'function') {
                window.location.reload();
            } else {
                cb(data);
            }
        } else {
            if (typeof callOnError != 'undefined' && callOnError) {
                if(typeof cb == 'function') {
                    cb(data);
                }
            }
            var errorText = "";
            if (data.errors.order) {
                for(var index in data.errors.order) {
                    if (data.errors.order.hasOwnProperty(index)) {
                        var attr = data.errors.order[index];
                        errorText += index + ": " + attr + "\n" ;
                    }
                }
            }
            if (errorText.length) {
                alert(errorText);
            } else {
                errorText = "Площадка дала ответ со статусом \"" + data.status + "\", но деталей ошибки не последовало. Скорее всего у площадки начались \"эти дни\". Просим извинения и подорждать некоторое время";
                alert(errorText);
            }
        }
    } catch(e) {
        if(typeof cb == 'function') {
            if (typeof callOnError != 'undefined' && callOnError) {
                cb(data);
            }
        }
        alert("Ошибка: невозможно разобрать ответ сервера, походу сервер пьян и ему пора идти домой!");
    }
}

$(document).ready(

    function(){

        function processBuySell(type, action) {
            if (action != 'calculate' && action != 'process') {
                throw new EventException('Invalid operation action specified');
            }
            if(type == 'buy') {
                var commission_ratio = COMISSION_RATIO_BUY;
            } else if (type == 'sell'){
                var commission_ratio = COMISSION_RATIO_SELL;
            } else {
                throw new EventException('Invalid operation type specified');
            }
            var id_prefix ='#'+type;
            var count = $(id_prefix + '_count').val();
            var price = $(id_prefix + '_price').val();
            var total = parseFloat(count) * parseFloat(price);
            var commission = commission_ratio * total;
            var total_usr = total - commission;
            $(id_prefix + '_total').text(total_usr);
            $(id_prefix + '_commission').text(commission);
            if (action == 'process') {
                var $current_form = $(id_prefix + '_form form');
                var form_data = $current_form.serializeArray();
                form_data['user'] = uid;
                //$buy_form_form.submit();
                $.ajax({
                    url : $current_form.attr('action'),
                    type : 'post',
                    data : form_data
                }).success(function(data){
                    processAjaxSuccess(data);
                });
            }
        }

        var uid = $('#user_id').val();
        $('#buy_calculate').click(
            function(e) {
                processBuySell('buy', 'calculate');
                e.preventDefault();
                e.stopPropagation();
            }
        );

        $('#buy_process').click(
            function(e) {
               processBuySell('buy', 'process');
                e.preventDefault();
                e.stopPropagation();
            }
        );


        $('#sell_calculate').click(
            function(e) {
                processBuySell('sell', 'calculate');
                e.preventDefault();
                e.stopPropagation();
            }
        );
        $('#sell_process').click(
            function(e) {
                processBuySell('sell', 'process');
                e.preventDefault();
                e.stopPropagation();
            }
        );

        $('canvas, #jqplot').dblclick(function(evt) {
            if (window.getSelection)
                window.getSelection().removeAllRanges();
            else if (document.selection)
                document.selection.empty();
        });
    }
);
