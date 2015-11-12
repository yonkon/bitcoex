<?php
/**
 * Site index
 * @var $this               SiteController
 * @var $app                CWebApplication
 * @var $user               User
 * @var $wallets            Wallet[]
 * @var $min_buy_order      Order
 * @var $max_buy_order      Order
 * @var $min_sell_order     Order
 * @var $max_sell_order     Order
 * @var $last_order         Order
 * @var $orders             Order[]
 * @var $transactions       Transaction[]
 * @var $userOrders         Order[]
 * @var $transactionGroups  array
 */
$app = Yii::app();

//HEAD SCRIPTS
$app->clientScript->registerScriptFile("/js/jquery.jplot/jquery.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/excanvas.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/jquery.jqplot.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.dateAxisRenderer.min.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.categoryAxisRenderer.min.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.ohlcRenderer.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.categoryAxisRenderer.min.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.highlighter.min.js");
$app->clientScript->registerScriptFile("/js/jquery.jplot/plugins/jqplot.cursor.min.js");

//CSS
$app->clientScript->registerCssFile('/js/jquery.jplot/jquery.jqplot.css');

//BODY CLOSE SCRIPTS
//$app->clientScript->registerScriptFile("/js/test_plot.js", CClientScript::POS_END);
$app->clientScript->registerScriptFile("/js/orders_ajax.js", CClientScript::POS_END);
$drawPlotInline = $this->renderPartial('inc/js/indexDrawPlotInline', array('transactionGroups' => $transactionGroups), true);
$app->clientScript->registerScript('indexDrawPlotInline', $drawPlotInline, CClientScript::POS_END);


?>
<div class="col-md-8 col-xs-12">
  <div class="col-xs-12 border1 mb1e">
    <span><b>Новости</b></span>

    <p class="m1e"><b>19/08/2015</b> <span>Путин хуйло</span></p>

    <p class="m1e"><b>19/08/2015</b> <span>ла-ла-ла</span></p>
  </div>
  <div class="col-xs-12 border1 mb1e pt1e">
    <ul>
      <li class="price-li price-li-active"><a class="price-a price-a-active">USD</a></li>
      <li class="price-li"><a class="price-a">USD</a></li>
    </ul>
    <div class="col-xs-12 np diagramma">
      <div id="jqplot"></div>
    </div>

  </div>
  <div class="col-sm-6 col-xs-12 mb1e border1 np">
    <?php if (!$app->user->isGuest)
      $this->renderPartial('inc/buy_form',
        array('min_sell_order' => $min_sell_order,
          'max_sell_order' => $max_sell_order,
          'min_buy_order' => $min_buy_order,
          'max_buy_order' => $max_buy_order,
          'wallets' => $wallets)); ?>
  </div>
  <div class="col-sm-6 col-xs-12 mb1e border1 np">
    <?php if (!$app->user->isGuest)
      $this->renderPartial('inc/sell_form', array('min_sell_order' => $min_sell_order,
        'max_sell_order' => $max_sell_order,
        'min_buy_order' => $min_buy_order,
        'max_buy_order' => $max_buy_order,
        'wallets' => $wallets)); ?>

  </div>

  <div class="clearfix">&nbsp;</div>

  <div class="col-xs-12 col-sm-6 mb1e np">
    <div class="border1 col-xs-12">
      <h4>Ордера на продажу BTC</h4>

      <p class="flr">Всего <?php echo $orders['total']['sell']; ?> BTC</p>

      <div style="overflow:auto; max-height: 500px;" class="col-xs-12 np">
        <table id="sellOrdersTable" class="table" style="width: 100%">
          <thead>
          <tr class="table-bg">
            <th>Цена</th>
            <th>BTC</th>
            <th>USD</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($orders['sell'] as $no => $order) { ?>
            <tr
              <?php if ($order->user == $app->user->id) { ?> style="color: gainsboro; "<?php } ?>
              data-oid="<?php echo $order->id ;?>"
               >
              <td class="price"><?php echo $order->price; ?></td>
              <td class="btc"><?php echo $order->rest; ?></td>
              <td class="usd"><?php echo $order->restCurrencyEquivalent(); ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 mb1e np">
    <div class="border1 col-xs-12">
      <h4>Ордера на покупку BTC</h4>

      <p class="flr">Всего <?php echo $orders['total']['buy']; ?> BTC</p>

      <div style="overflow:auto; max-height: 500px;" class="col-xs-12 np">
        <table id="buyOrdersTable" class="table" style="width: 100%">
          <thead>
          <tr class="table-bg">
            <th>Цена</th>
            <th>BTC</th>
            <th>USD</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($orders['buy'] as $no => $order) { ?>
            <tr
              <?php if ($order->user == $app->user->id) { ?> style="color: gainsboro; "<?php } ?>
              data-oid="<?php echo $order->id ;?>"
              >
              <td class="price"><?php echo $order->price; ?></td>
              <td class="btc"><?php echo $order->rest; ?></td>
              <td class="usd"><?php echo $order->restCurrencyEquivalent(); ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>


  <div class="col-xs-12 border1 mb1e">
    <h4><?php echo Yii::t('general', 'История сделок:'); ?></h4>
<section class="fixed-header table-bg">
    <div style="overflow:auto; max-height: 300px;">
      <table class="table" style="width: 100%">
        <thead>
        <tr class="table-bg">
          <th><?php echo ''; Yii::t('general', 'Дата'); ?><div><?php echo Yii::t('general', 'Дата'); ?></div></th>
          <th><?php echo ''; Yii::t('general', 'Тип'); ?><div><?php echo Yii::t('general', 'Тип'); ?></div></th>
          <th><?php echo '';  Yii::t('general', 'Цена'); ?><div><?php echo Yii::t('general', 'Цена'); ?></div></th>
          <th><?php echo '';  Yii::t('general', 'Кол-во (BTC)'); ?><div><?php echo Yii::t('general', 'Кол-во (BTC)'); ?></div></th>
          <th><?php echo '';  Yii::t('general', 'Всего (USD)'); ?><div><?php echo Yii::t('general', 'Всего (USD)'); ?></div></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $tr_id => $transaction) { ?>
          <tr>
            <td><span><?php echo date('d/m H:i:s', $transaction->date); ?></span></td>
            <td>
              <b style="color:<?php if ($transaction->isBTCBuy()) {
                ?>green<?php
              } else {
                ?>red<?php }
              ?>">
                <?php echo Yii::t('general', $transaction->isBTCBuy()?'Покупка':'Продажа');?>
              </b>
            </td>
            <td><?php echo $transaction->src_price; ?></td>
            <td><?php echo $transaction->srcBTCEquivalent(); ?> BTC</td>
            <td><?php echo $transaction->srcUSDEquivalent(); ?> USD</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
</section>
  </div>


</div>
<div class="col-md-4 col-xs-12">
<?php if (!$app->user->isGuest) {
  ?><div id="userOrders" class="col-xs-12 mb1e">
    <h4><?php echo Yii::t('general', 'My open orders'); ?></h4>
    <?php $this->renderPartial('inc/userOrdersTable', array('userOrders' => $userOrders), false); ?>
  </div>
<?php } ?>
</div>




