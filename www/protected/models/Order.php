<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $user
 * @property integer $src_wallet
 * @property integer $src_wallet_type
 * @property double $summ
 * @property double $price
 * @property integer $dst_wallet
 * @property integer $dst_wallet_type
 * @property double $rest
 * @property integer $date
 * @property integer $status
 */
class Order extends CActiveRecord
{
	const STATUS_NEW = 0;
	const STATUS_CLOSED = 1;

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
        $currentOrders = $currentOrders->findAllBySql(
            "SELECT * FROM {$this->tableName()}
WHERE dst_wallet_type={$this->src_wallet_type} AND
price={$this->price} AND
status={$this->status}
ORDER BY date ASC");
        if (empty($currentOrders)) {
            return false;
        }
        foreach ($currentOrders as $ord) {
            if ($this->rest == 0 ) {
                $this->status = Order::STATUS_CLOSED;
                $this->save();
                break;
            }
            assert($this->rest > 0, "Assertion failed: Order ID#{$this->id} has a negative rest value");
            if ($ord->rest == 0) {
                continue;
            }

            //Две транзакции:
            //Передача средств текущего заказа на кошелек найденного заказа
            // и передача в обратном направлении
            $forward_ta = new Transaction();
            $reverse_ta = new Transaction();
            $transaction_date = date('Y-m-d H:i:s', time() );
            //Если предложение покрывает полностью сумму текущего заказа, то закрываем текущий заказ
            if ($ord->restCurrencyEquivalent() >= $this->restCurrencyEquivalent()) {
                $thisOrder = $this;
                $thatOrder = $ord;
            } else {
                //Если предложение не покрывает полностью сумму текущего заказа, то закрываем предложение
                $thisOrder = $ord;
                $thatOrder = $this;
            }

            $forward_ta->order = $thisOrder->id;

            $forward_ta->src_price = $thisOrder->price;
            $forward_ta->dst_price = $thisOrder->price;

            if ($thisOrder->isUSDSell()) {
                $forward_ta->src_count = $thisOrder->restCurrencyEquivalent();
                $forward_ta->dst_count = $thisOrder->restCurrencyEquivalent(); //TODO make transacttion go. Check for correct conversion count
            } else {
                $forward_ta->src_count = $thisOrder->restCryptoEquivalent();
                $forward_ta->dst_count = $thisOrder->restCryptoEquivalent();
            }

            $forward_ta->src_wallet = $thisOrder->src_wallet;
            $forward_ta->dst_wallet = $thatOrder->dst_wallet;
            $forward_ta->date = $transaction_date;

            $reverse_ta->order = $thatOrder->id;
            $reverse_ta->src_price = $thatOrder->price;
            $reverse_ta->dst_price = $thatOrder->price;

            assert($forward_ta->src_price == $reverse_ta->dst_price &&
                $forward_ta->dst_price == $reverse_ta->src_price &&
                $forward_ta->src_price == $reverse_ta->src_price, 'Price equality assertion failed');

            if ($thisOrder->isUSDSell()) {
                $reverse_ta->src_count = $thisOrder->restCryptoEquivalent(); //TODO make transacttion go. Check for correct conversion count
                $reverse_ta->dst_count = $thisOrder->restCryptoEquivalent();
            } else {
                $reverse_ta->src_count = $thisOrder->restCurrencyEquivalent();
                $reverse_ta->dst_count = $thisOrder->restCurrencyEquivalent();
            }

            $reverse_ta->src_wallet = $thatOrder->src_wallet;
            $reverse_ta->dst_wallet = $thisOrder->dst_wallet;
            $reverse_ta->date = $transaction_date;


            $reverse_result = $reverse_ta->validate();
            $forward_result = $forward_ta->validate();

            if ($forward_result && $reverse_result) {
                $forward_result = $forward_ta->save();
                $reverse_result = $reverse_ta->save();
            }
        }
        return true;
	}

    public function restCurrencyEquivalent(){
        if ($this->isBTCSell()) {
            return $this->rest * $this->price;
        } else {
            return $this->rest;
        }
    }

    public function restCryptoEquivalent(){
        if ($this->isBTCSell()) {
            return $this->rest;
        } else {
            return $this->rest / $this->price;
        }
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
        if (empty ($this->src_wallet_type) && ~empty ($this->src_wallet)) {
            $src_wallet = Wallet::model()->findByPk($this->src_wallet);
            $this->src_wallet_type = $src_wallet->type;
        }
        if (empty ($this->dst_wallet_type) && ~empty ($this->dst_wallet)) {
            $dst_wallet = Wallet::model()->findByPk($this->dst_wallet);
            $this->dst_wallet_type = $dst_wallet->type;
        }
    }

}
