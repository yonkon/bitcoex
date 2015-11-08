<?php

/**
 * @var Order[] $userOrders
 */
?>
<section class="fixed-header table-bg">
<table class="table">
    <thead>
    <tr class="table-bg">
        <th><?php echo Yii::t('order', 'ID');?><div><?php echo Yii::t('order', 'ID');?></div></th>
        <th><?php echo Yii::t('order', 'Date');?><div><?php echo Yii::t('order', 'Date');?></div></th>
        <th><?php echo Yii::t('order', 'Type');?><div><?php echo Yii::t('order', 'Type');?></div></th>
        <th><?php echo Yii::t('order', 'Price');?><div><?php echo Yii::t('order', 'Price');?></div></th>
        <th><?php echo Yii::t('order', 'Sum');?><div><?php echo Yii::t('order', 'Sum');?></div></th>
        <th><?php echo Yii::t('order', 'Rest');?><div><?php echo Yii::t('order', 'Rest');?></div></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($userOrders as $userOrder) { ?>
        <tr data-oid="<?php echo $userOrder->id;?>">
            <td><?php echo $userOrder->id;?></td>
            <td><?php echo date('y/m/d H:i:s',$userOrder->date);?></td>
            <td><?php if ($userOrder->isBTCBuy()) {?>
                    <b class="order-buy">
                    <?php echo Yii::t('order', 'Buy'); ?>
                    </b>
                <?php } else { ?>
                    <b class="order-sell">
                    <?php echo Yii::t('order','Sell'); ?>
                    </b>
                <?php } ?></td>
            <td><?php echo $userOrder->price;?></td>
            <td><?php echo $userOrder->summ;?></td>
            <td><?php echo $userOrder->rest;?></td>
        </tr>

    <?php } ?>
    </tbody>
</table>
</section>

<script type="text/javascript">
  $(document).ready(function () {
    var bindUserOrdersActions = function () {
      $('#userOrders tbody tr').contextmenu(function (e) {
        e.preventDefault();
        e.stopPropagation();
        contextmenuUserOrders(e);
      });
    };
    bindUserOrdersActions();
    var contextmenuUserOrders = function (event) {
      $('#userOrders .hover').removeClass('hover'); //убираем подсветку всех строк таблицы
      var $currentRow = $(event.currentTarget);
      $currentRow.addClass('hover'); //подсвечиваем текущую строку
      var contextMenu = $('.user-orders-context-menu');
      if (!contextMenu.length) {
        contextMenu = $('<div class="user-orders-context-menu"></div>');
        $('#userOrders').append(contextMenu);
        $(contextMenu).append($('<a class="user-orders-context-menu-option"><?php echo Yii::t('order', 'Remove'); ?></a>'));
      }
      $(contextMenu).offset({ top: event.pageY});
      $(contextMenu).unbind('click').bind('click', function () {
        var oid = $currentRow.data('oid');
        var $this = $(this); //Context menu
        $.ajax({
          url: '/order/cancel/'+oid,
          data: {ajax : true},
          method: 'post',
          success : function(data) {
            processAjaxSuccess(data, function(data){
              $currentRow.remove();
              $this.remove();
            });
          }
        });
      });
    };

  });
</script>
