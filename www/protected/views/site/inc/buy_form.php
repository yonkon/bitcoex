<?php
/**
 *  @var CWebApplication $app 
 *  @var Wallet[] $wallets
 *  @var Order $min_sell_order
 *  @var Order $max_sell_order
 *  @var Order $min_buy_order
 *  @var Order $max_buy_order
 */
$app = Yii::app();

?>
<div id="buy_form" class="border1 col-xs-12 row">
    <form action="<?php cecho( $app->createUrl('order/create')); ?>" method="post">
        <input type="hidden" name="src_wallet" value="<?php if (!empty($wallets['USD']) ) cecho( $wallets['USD']->id); ?>">
        <input type="hidden" name="dst_wallet" value="<?php if (!empty($wallets['BTC']) )cecho( $wallets['BTC']->id); ?>">
        <h5>Купить BTC</h5>
        <div class="col-xs-12 order-h mb1e">
            <div class="col-xs-6 np">
                <span class="">Ваши средства:</span>

                <p class=""><a href=""><?php if (!empty($wallets['USD']) ) cecho( $wallets['USD']->money); ?> USD</a></p>
            </div>
            <div class="col-xs-6">
                <span>Min/Max  цена:</span>

                <p><b><?php if (!empty($min_sell_order) ) cecho($min_sell_order->price); ?>/<?php if (!empty($max_sell_order) )cecho( $max_sell_order->price); ?> USD</b></p>
            </div>
        </div>
        <table class="col-xs-12 order-t">
            <tbody>
            <tr>
                <td class="w150p">Количество BTC:</td>
                <td class="w150p"><input id="buy_count" name="summ" class="w110p" maxlength="25" type="text" value="0"></td>
            </tr>
            <tr class="">
                <td>Цена за BTC:</td>
                <td><input id="buy_price" name="price" class="w110p" type="text" maxlength="7" value="<?php if (!empty($min_sell_order) ) cecho( $min_sell_order->price); ?>"> USD</td>
            </tr>
            <tr>
                <td>Всего:</td>
                <td><b id="buy_total">0</b> <b>USD</b></td>
            </tr>
            <tr>
                <td>Комиссия:</td>
                <td><b id="buy_commission">0</b> <b>USD</b></td>
            </tr>
            <tr>
                <td colspan="2" class="order-t-l">
                    <div>Нажмите <b>подсчитать</b>, чтобы рассчитать сумму в соответствии с ордерами.</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="" id="buy_calculate" class="button" style="float: left;">Подсчитать</a>

                    <div class=""></div>
                    <a href="" id="buy_process" class="button" style="float: right;">Купить BTC</a>

                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
