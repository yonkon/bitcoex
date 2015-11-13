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
            var commission_ratio = 0;
            if(type == 'buy') {
                commission_ratio = COMISSION_RATIO_BUY;
            } else if (type == 'sell'){
                commission_ratio = COMISSION_RATIO_SELL;
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
                var $current_btn = $current_form.find(id_prefix+'_process');
                var min_price = $current_btn.data('min_price');
                var max_price = $current_btn.data('max_price');
                if (min_price != 'undefined' &&
                    parseFloat(min_price)*0.9 > parseFloat(price) &&
                        !confirm('Your price is 10% less than current minimum. Confirm order?')
                ) {
                    return;
                }
                if (max_price != 'undefined' &&
                    parseFloat(max_price)*1.1 < parseFloat(price) &&
                    !confirm('Your price is 10% more than current minimum. Confirm order?')
                ) {
                    return;
                }
                var form_data = $current_form.serializeArray();
                form_data['user'] = uid;
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

        setTimeout(updateOrders, 1000);
    }
);

function updateOrders(date) {
    $.ajax({
        url : 'order/getChanges',
        data : {
            date : date,
            ajax : true
        },
        success : function(response) {
            var res = JSON.parse(response);
            if (typeof res != 'undefined' && typeof res.orders != 'undefined') {
                console.dir(res);
                if (typeof res.orders.sell != 'undefined') {
                    updateSellOrders(res.orders.sell);
                }
                if (typeof res.orders.buy != 'undefined') {
                    updateBuyOrders(res.orders.buy);
                }
                if (typeof res.orders.history != 'undefined') {
                    updateHistory(res.orders.history);
                }
                //if (typeof res.orders.my != 'undefined') {
                //    updateMyOrders(res.orders.my);
                //}
                setTimeout(function(){ updateOrders(res.last_modified);},5000);
                if (res.status == "OK") {
                    console.log('ok');
                }
            }
        }
    });
}

function updateBuySellOrders(orders, type) {
    $.each(orders, function(index, order){
        var id = order.id;
        var price = order.price;
        var btc = order.btc;
        var usd = order.usd;
        var status = order.status; //0 - новый(открытый), 1 - закрыт, 2 - отменен
        var $tr = $('#' +type+ 'OrdersTable tr[data-oid="' + id + '"]');
        if ($tr.length > 0) {
            if (status != 0){
                $tr.remove();
            } else {
                $tr.find('.price').text(price);
                $tr.find('.btc').text(btc);
                $tr.find('.usd').text(usd);
            }
        } else {
            if (status == 0) { //если это открытый ордер
            var $newTr = $('<tr data-oid="' +id+ '"><td class="price">' + price + '</td><td class="btc">' + btc +'</td><td class="usd">' +usd+'</td></tr>');
                $('#' +type+ 'OrdersTable tbody').prepend($newTr);
            }
        }
    });
}
function updateHistory(recent_history) {
    $.each(recent_history, function(index, transaction){
        var id = transaction.id;
        var date = transaction.date;
        var _type = transaction._type;
        var type = transaction.type;
        var price = transaction.price;
        var btc = transaction.btc;
        var usd = transaction.usd;
        var $tr = $('#orderHistory tr[data-tid=' +id+ ']');
        if (!$tr.length) {
            var $newTr = $('<tr data-tid="' +id+ '"><td class="date">' + timestamp2date('d/m H:i:s', date) + '</td><td class="type ' +_type+ '">' + type +'</td><td class="price">' +price+'</td><td class="btc">' +btc+' BTC</td><td class="usd">' +usd+' USD</td></tr>');
            $('#orderHistory tbody').prepend($newTr);
        }
    });
}

function updateSellOrders(orders) {
    return updateBuySellOrders(orders, 'sell');
}

function updateBuyOrders(orders) {
    return updateBuySellOrders(orders, 'buy');
}

function timestamp2date(format, timest) {
    var d = new Date(timest*1000);
    var date = d.getDate();
    date = date > 9 ? date : '0'+date;
    var month = d.getMonth() + 1;
    month = month > 9 ? month : '0'+month;
    var hours = d.getHours();
    hours = hours > 9 ? hours : '0'+hours;
    var minutes = d.getMinutes();
    minutes = minutes > 9 ? minutes : '0'+minutes;
    var seconds = d.getSeconds();
    seconds = seconds > 9 ? seconds : '0'+seconds;

    return format.replace('d', date)
        .replace('m', month)
        .replace('Y', d.getFullYear())
        .replace('H', hours)
        .replace('i', minutes)
        .replace('s', seconds);
}
