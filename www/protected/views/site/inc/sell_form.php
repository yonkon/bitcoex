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
<div id="sell_form" class="col-xs-12">
    <form action="<?php cecho( $app->createUrl('order/create') ); ?>" method="post">
    <input type="hidden" name="src_wallet" value="<?php  if (!empty($wallets['BTC']) ) cecho( $wallets['BTC']->id ); ?>">
    <input type="hidden" name="dst_wallet" value="<?php  if (!empty($wallets['USD']) ) cecho( $wallets['USD']->id ); ?>">
    <h4><?php echo Yii::t('general', 'Продать BTC');?></h4>

    <div class="col-xs-12 order-h mb1e">
        <div class="col-xs-6 np wallet-rest btc">
            <span class=""><?php echo Yii::t('general', 'Ваши средства:');?></span>
            <p>
                <a href=""><span class="available"><?php  if (!empty($wallets['BTC']) ) cecho( $wallets['BTC']->available ); ?></span> BTC</a>
            </p>
            <p class="small">
              <a href="" ><span class="money"><?php if (!empty($wallets['BTC']) ) cecho( $wallets['BTC']->money); ?></span> BTC</a>
            </p>


        </div>
        <div class="col-xs-6">
            <span>Max/Min цена:</span>
            <p><b><span class="price-max"><?php  if (!empty($max_buy_order) ) cecho( $max_buy_order->price ); ?></span>/<span class="price-min"><?php  if (!empty($min_buy_order) ) cecho( $min_buy_order->price ); ?></span> USD</b></p>
        </div>
    </div>
    <table class="col-xs-12 order-t">
        <tbody>
        <tr>
            <td class="w150p">Количество BTC:</td>
            <td class="w150p"><input id="sell_count" name="summ" class="w110p" maxlength="25" type="text" value="0"></td>
        </tr>
        <tr class="">
            <td>Цена за BTC:</td>
            <td><input id="sell_price" name="price" class="w110p" type="text" maxlength="7" value="<?php  if (!empty($max_buy_order) ) cecho( $max_buy_order->price ); ?>"> USD</td>
        </tr>
        <tr>
            <td>Всего:</td>
            <td><b id="sell_total">0</b><b>USD</b></td>
        </tr>
        <tr>
            <td>Комиссия:</td>
            <td><b id="sell_commission">0</b> <b>BTC</b></td>
        </tr>
        <tr>
            <td colspan="2" class="order-t-l">
                <div>Нажмите <b>подсчитать</b>, чтобы рассчитать сумму в соответствии с ордерами.</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="" id="sell_calculate" class="button" style="float: left;">Подсчитать</a>

                <div class=""></div>
                <a href="#"
                   id="sell_process"
                   class="button"
                   style="float: right;"
                   data-min-price="<?php if (!empty($min_buy_order) ) cecho($min_buy_order->price); ?>"
                   data-max_price="<?php if (!empty($max_sell_order) )cecho( $max_sell_order->price); ?>">
                    Продать BTC
                </a>

            </td>
        </tr>
        </tbody>
    </table>
        </form>
</div>
