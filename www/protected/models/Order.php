<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $user
 * @property integer $src_wallet
 * @property Wallet $srcWallet
 * @property integer $src_wallet_type
 * @property double $summ
 * @property double $price
 * @property integer $dst_wallet
 * @property Wallet $dstWallet
 * @property integer $dst_wallet_type
 * @property double $rest
 * @property integer $date
 * @property integer $status
 */
class Order extends CActiveRecord
{
	const STATUS_NEW = 0;
	const STATUS_CLOSED = 1;
  const STATUS_CANCELED = 2;

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user, src_wallet, summ, price, dst_wallet, rest, status', 'required'),
			array('id, user, src_wallet, src_wallet_type, dst_wallet, dst_wallet_type, date, status', 'numerical', 'integerOnly'=>true),
			array('summ, price, rest', 'numerical'),
			array('summ, price, rest', 'compare', 'operator'=>'>=', 'compareValue'=>0),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user, src_wallet, src_wallet_type, summ, price, dst_wallet, dst_wallet_type, rest, date, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
        return array(
            'srcWallet' => array(self::BELONGS_TO, 'Wallet', 'src_wallet'),
            'dstWallet' => array(self::BELONGS_TO, 'Wallet', 'dst_wallet'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => 'User',
			'src_wallet' => 'Src Wallet',
			'src_wallet_type' => 'Src Wallet Type',
			'summ' => 'Summ',
			'price' => 'Price',
			'dst_wallet' => 'Dst Wallet',
			'dst_wallet_type' => 'Dst Wallet Tpe',
			'rest' => 'Rest',
			'date' => 'Date',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user',$this->user);
		$criteria->compare('src_wallet',$this->src_wallet);
		$criteria->compare('src_wallet_type',$this->src_wallet_type);
		$criteria->compare('summ',$this->summ);
		$criteria->compare('price',$this->price);
		$criteria->compare('dst_wallet',$this->dst_wallet);
		$criteria->compare('dst_wallet_type',$this->dst_wallet_type);
		$criteria->compare('rest',$this->rest);
		$criteria->compare('date',$this->date);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function processCurrentBids()
	{
        $currentOrders = Order::model();
        $operation = "=";
        if ($this->isBTCBuy()) {
            $operation = "<=";
        } elseif($this->isBTCSell()) {
            $operation = ">=";
        }
    $status_new = Order::STATUS_NEW;
        $currentOrders = $currentOrders->findAllBySql(
            "SELECT * FROM {$this->tableName()}
WHERE dst_wallet_type={$this->src_wallet_type} AND
price {$operation} {$this->price} AND
status = {$status_new} AND
user != {$this->user}
ORDER BY date ASC");
        if (empty($currentOrders)) {
            return !$this->hasErrors();
        }
        foreach ($currentOrders as $ord) {
            if ($this->rest == 0 ) {
                $this->status = Order::STATUS_CLOSED;
                $this->save();
                break;
            }
            if(!($this->rest > 0)) { throw new Exception("Assertion failed: Order ID#{$this->id} has a negative rest value"); }
            if ($ord->rest == 0) {
                continue;
            }

            //Две транзакции:
            //Передача средств текущего заказа на кошелек найденного заказа
            // и передача в обратном направлении
            $forward_ta = new Transaction();
            $reverse_ta = new Transaction();
            $transaction_date = time();
            /**
             * @var Order $minorOrder - closing order
             * @var Order $majorOrder - matched order for closing order
             * @var Order $ord - one of found orders
             */
            if ($ord->isBTCBuy()) {
                //Если предложение покрывает полностью сумму текущего заказа, то закрываем текущий заказ
                if ($ord->restCryptoEquivalent() >= $this->restCryptoEquivalent()) {
                    $minorOrder = $this;
                    $majorOrder = $ord;
                } else {
                    //Если предложение не покрывает полностью сумму текущего заказа, то закрываем предложение
                    $minorOrder = $ord;
                    $majorOrder = $this;
                }
            }
            if ($ord->isBTCSell()) {
                //Если предложение покрывает полностью сумму текущего заказа, то закрываем текущий заказ
                if ($ord->restCryptoEquivalent() >= $this->restCryptoEquivalent()) {
                    $minorOrder = $this;
                    $majorOrder = $ord;
                } else {
                    //Если предложение не покрывает полностью сумму текущего заказа, то закрываем предложение
                    $minorOrder = $ord;
                    $majorOrder = $this;
                }
            }


            $forward_ta->order = $minorOrder->id;
            $reverse_ta->order = $majorOrder->id;

            $forward_ta->src_price = $majorOrder->price;
            $forward_ta->dst_price = $minorOrder->price;
            $reverse_ta->src_price = $minorOrder->price;
            $reverse_ta->dst_price = $majorOrder->price;

            $forward_ta->src_wallet = $majorOrder->src_wallet;
            $forward_ta->dst_wallet = $minorOrder->dst_wallet;
            $reverse_ta->src_wallet = $minorOrder->src_wallet;
            $reverse_ta->dst_wallet = $majorOrder->dst_wallet;

            $reverse_ta->date = $transaction_date;
            $forward_ta->date = $transaction_date;

            if ($minorOrder->isBTCSell()) {
                //todo check
                $forward_ta->src_count = $majorOrder->getCurrencyEquivalent($minorOrder->restCryptoEquivalent());
                $forward_ta->dst_count = $minorOrder->restCurrencyEquivalent();
                $reverse_ta->src_count = $minorOrder->restCryptoEquivalent();
                $reverse_ta->dst_count = $minorOrder->restCryptoEquivalent();
            } else if($minorOrder->isBTCBuy()) {
                $forward_ta->src_count = $minorOrder->restCryptoEquivalent();
                $forward_ta->dst_count = $minorOrder->restCryptoEquivalent();
                $reverse_ta->src_count = $minorOrder->restCurrencyEquivalent();
                $reverse_ta->dst_count = $majorOrder->getCurrencyEquivalent($minorOrder->restCryptoEquivalent());
            }

            if(!($forward_ta->src_price == $reverse_ta->dst_price &&
                $forward_ta->dst_price == $reverse_ta->src_price)) {
                throw new Exception('Price equality assertion failed');
            }

            $reverse_result = $reverse_ta->validate();
            $forward_result = $forward_ta->validate();

            if ($forward_result && $reverse_result) {
                $forward_result = $forward_ta->save();
                $reverse_result = $reverse_ta->save();
                if ($forward_result && $reverse_result) {
                    //TODO check order rest change
                    $majorOrder->rest -= $minorOrder->rest;
                    if($majorOrder->rest == 0) {
                        $majorOrder->status = Order::STATUS_CLOSED;
                    }
                    if (!$majorOrder->save()) {
                        $this->addError('rest', "Order ID#{$majorOrder->id}(major) could not be saved");
                    }

                    $minorOrder->rest = 0;
                    $minorOrder->status = Order::STATUS_CLOSED;
                    if (!$minorOrder->save()) {
                        $this->addError('rest', "Order ID#{$minorOrder->id}(minor) could not be saved");
                    }
                }
            }
        }
        return !$this->hasErrors();
	}

    public function summCurrencyEquivalent() {
        return $this->summ*$this->price;
    }

    public function summCryptoEquivalent() {
        return $this->summ;
    }
    public function restCurrencyEquivalent(){
        return $this->rest * $this->price;
    }

    public function restCryptoEquivalent(){
        return $this->rest;
    }

    public function isBTCSell(){
        return in_array($this->src_wallet_type, Wallet::$WALLET_CURRENCY_BTC);
    }

    public function isBTCBuy(){
        return in_array($this->dst_wallet_type, Wallet::$WALLET_CURRENCY_BTC);
    }

    public function isUSDSell(){
        return in_array($this->src_wallet_type, Wallet::$WALLET_CURRENCY_USD);
    }

    public function isUSDBuy(){
        return in_array($this->dst_wallet_type, Wallet::$WALLET_CURRENCY_USD);
    }

    public function setAttributes($values,$safeOnly=true) {
        parent::setAttributes($values,$safeOnly=true);

        if (empty ($this->src_wallet_type) && !empty ($this->src_wallet)) {
            $src_wallet = Wallet::model()->findByPk($this->src_wallet);
            $this->src_wallet_type = $src_wallet->type;
        }
        $reservedSuccess = $this->reserveAvailableMoney();
//        assert($reservedSuccess);
        if (empty ($this->dst_wallet_type) && !empty ($this->dst_wallet)) {
            $dst_wallet = Wallet::model()->findByPk($this->dst_wallet);
            $this->dst_wallet_type = $dst_wallet->type;
        }
    }

    /**
     * @param Wallet $src_wallet
     * @return bool if available amount changed
     */
    private function reserveAvailableMoney($src_wallet = null) {
        if (empty($scr_wallet)) {
            if (empty ($this->srcWallet)) {
                return false;
            } else {
                $src_wallet = $this->srcWallet;
            }
        }
        if ($this->isBTCSell()) {
            $decreaseAmount = $this->summCryptoEquivalent();
        } else {
            $decreaseAmount = $this->summCurrencyEquivalent();
        }
        if ($src_wallet->available < $decreaseAmount) {
            $this->addError('available', 'Not enough money available');
            return false;
        } else {
            $src_wallet->available -= $decreaseAmount;
            return $src_wallet->save();
        }


    }

    public function validate($attributes=null, $clearErrors=true) {
        parent::validate($attributes, $clearErrors);
        if ($this->price <= 0) {
            $this->addError('price', "Must be positive price value");
        }
        if ($this->summ <= 0) {
            $this->addError('summ', "Must be positive summ value");
        }
        if ($this->isBTCBuy()) {
            if ($this->srcWallet->money < $this->restCurrencyEquivalent() || $this->srcWallet->available < 0 ) {
                $this->addError('src_wallet', Yii::t('order', 'Not enough money for order'));
            }
        } else {
            if ($this->srcWallet->money < $this->restCryptoEquivalent() || $this->srcWallet->available < 0) {
                $this->addError('src_wallet', Yii::t('order', 'Not enough money for order'));
            }
        }
        return !$this->hasErrors();
    }

    private function getCurrencyEquivalent($summ)
    {
        return $summ*$this->price;
    }

//    protected function beforeValidate()
//    {
//        $cur_date = $this->date;
//        if (empty($cur_date)) {
//            $cur_date = time();
//        } else {
//            if (!is_numeric($cur_date)) {
//                $cur_date = strtotime($cur_date);
//            }
//        }
//        $this->date = $cur_date;
//        return parent::beforeSave();
//    }

//    protected function beforeSave()
//    {
//        $this->date = date('Y-m-d H:i:s', $this->date);
//        return parent::beforeSave();
//    }
//
//    protected function afterSave() {
//        $this->date = strtotime($this->date);
//    }

}
