<?php

/**
 * @var Order[] $userOrders
 */
?>

<table class="table">
    <thead>
    <tr class="table-bg">
        <th><?php echo Yii::t('order', 'ID');?></th>
        <th><?php echo Yii::t('order', 'Date');?></th>
        <th><?php echo Yii::t('order', 'Type');?></th>
        <th><?php echo Yii::t('order', 'Price');?></th>
        <th><?php echo Yii::t('order', 'Sum');?></th>
        <th><?php echo Yii::t('order', 'Rest');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($userOrders as $userOrder) { ?>
        <tr>
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


