<?php

/**
 * @var Order[] $userOrders
 */
?>
<section class="fixed-header table-bg">
  <table class="table">
    <thead>
    <tr class="table-bg">
      <th><?php echo ''; Yii::t('order', 'ID'); ?>
        <div><?php echo Yii::t('order', 'ID'); ?></div>
      </th>
      <th><?php echo ''; Yii::t('order', 'Date'); ?>
        <div><?php echo Yii::t('order', 'Date'); ?></div>
      </th>
      <th><?php echo ''; Yii::t('order', 'Type'); ?>
        <div><?php echo Yii::t('order', 'Type'); ?></div>
      </th>
      <th><?php echo ''; Yii::t('order', 'Price'); ?>
        <div><?php echo Yii::t('order', 'Price'); ?></div>
      </th>
      <th><?php echo ''; Yii::t('order', 'Sum'); ?>
        <div><?php echo Yii::t('order', 'Sum'); ?></div>
      </th>
      <th><?php echo ''; Yii::t('order', 'Rest'); ?>
        <div><?php echo Yii::t('order', 'Rest'); ?></div>
      </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($userOrders as $userOrder) { ?>
      <tr data-oid="<?php echo $userOrder->id; ?>">
        <td><?php echo $userOrder->id; ?></td>
        <td><?php echo date('y/m/d H:i:s', $userOrder->date); ?></td>
        <td><?php if ($userOrder->isBTCBuy()) { ?>
            <b class="order-buy">
              <?php echo Yii::t('order', 'Buy'); ?>
            </b>
          <?php } else { ?>
            <b class="order-sell">
              <?php echo Yii::t('order', 'Sell'); ?>
            </b>
          <?php } ?></td>
        <td><?php echo $userOrder->price; ?></td>
        <td><?php echo $userOrder->summ; ?></td>
        <td><?php echo $userOrder->rest; ?></td>
      </tr>

    <?php } ?>
    </tbody>
  </table>
</section>

<script type="text/javascript">
  $(document).ready(function () {
    function modalDialog(messageHeader, messageText, buttons, onCloseCb) {
      var modalHtml = '<div class="modal show">\
              <div class="modal-dialog modal-sm">\
              <div class="modal-content">\
              <div class="modal-header">\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
          <h4 class="modal-title center">' + messageHeader + '</h4>\
          </div>';
      if (typeof messageText != 'undefined' && messageText) {
        modalHtml += '<div class="modal-body"><p>' + messageText + '</p></div>';
      }
      modalHtml += '<div class="modal-footer center">';
      modalHtml += '</div>\
          </div>\
          </div>\
          </div>';
      $modal = $(modalHtml);
      $modalFooter = $modal.find('.modal-footer');
      $.each(buttons, function (name, clickCb) {
        var $btn = $('<button type="button" class="btn btn-default" data-dismiss="modal">' + name + '</button>');
        $btn.click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          clickCb(arguments);
          $modal.addClass('fade').remove();
        });
        $modalFooter.append($btn);
      });

      $modal.appendTo(document.body);
      $modal.find('.close').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $modal.addClass('fade').remove();
        if (typeof onCloseCb == 'function') {
          onCloseCb(e);
        }
      });
    }

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
      $(contextMenu).offset({top: event.pageY});
      $(contextMenu).unbind('click').bind('click', function () {
        var oid = $currentRow.data('oid');
        var $this = $(this); //Context menu
        modalDialog('<?php echo Yii::t('order', 'Remove order?'); ?>', null, {
          '<?php echo Yii::t('general', 'No'); ?>': function () {
            $this.remove();
          },
          '<?php echo Yii::t('general', 'Yes'); ?>': function () {
            $.ajax({
              url: '<?php echo Yii::app()->createUrl('/order/cancel/');?>/' + oid,
              data: {ajax: true},
              method: 'post',
              success: function (data) {
                processAjaxSuccess(data, function (data) {
                  $currentRow.remove();
                  $this.remove();
                });
              }
            });
          }
        }, function(){$this.remove();});

      });
    };

  });
</script>
