<?php

/**
 * This is the model class for table "{{transaction}}".
 *
 * The followings are the available columns in table '{{transaction}}':
 * @property integer $id
 * @property integer $src_wallet
 * @property double $src_count
 * @property double $src_price
 * @property integer $dst_wallet
 * @property double $dst_count
 * @property double $dst_price
 * @property string $date
 * @property integer $order
 */
class Transaction extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{transaction}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('src_wallet, src_count, src_price, dst_wallet, dst_count, dst_price, date', 'required'),
			array('src_wallet, dst_wallet, order', 'numerical', 'integerOnly'=>true),
			array('src_count, src_price, dst_count, dst_price', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, src_wallet, src_count, src_price, dst_wallet, dst_count, dst_price, date, order', 'safe', 'on'=>'search'),
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
			'src_wallet' => 'Src Wallet',
			'src_count' => 'Src Count',
			'src_price' => 'Src Price',
			'dst_wallet' => 'Dst Wallet',
			'dst_count' => 'Dst Count',
			'dst_price' => 'Dst Price',
			'date' => 'Date',
			'order' => 'Order',
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
		$criteria->compare('src_wallet',$this->src_wallet);
		$criteria->compare('src_count',$this->src_count);
		$criteria->compare('src_price',$this->src_price);
		$criteria->compare('dst_wallet',$this->dst_wallet);
		$criteria->compare('dst_count',$this->dst_count);
		$criteria->compare('dst_price',$this->dst_price);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transaction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     *@return Wallet
     */
    public function beforeSave() {
        parent::beforeSave();
        $srcWallet = Wallet::model()->findByPk($this->src_wallet);
        assert(!empty($srcWallet));//  "srcWallet ID# {$this->src_wallet} not found");

        $dstWallet = Wallet::model()->findByPk($this->dst_wallet);
        assert(!empty($dstWallet));//  "dstWallet ID# {$this->dst_wallet} not found");

        $srcWallet->money -= $this->src_count;
//        $srcWallet->available -= $this->src_count;
        assert($srcWallet->money >= 0 && $srcWallet->available >= 0);//  "srcWallet Assertion failed: negative money");


        $dstWallet->money += $this->dst_count;
        $dstWallet->available += $this->dst_count;
        if ($dstWallet->available > $dstWallet->money) {
            $dstWallet->available = $dstWallet->money;
        }
        assert($dstWallet->money >= 0 && $dstWallet->available >= 0);//  "dstWallet Assertion failed: negative money");
		if ($srcWallet->save()&& $dstWallet->save() ) {
            return !$this->hasErrors();
            //return true;
        }

        return !$this->hasErrors();

    }

    /**
     *@return Wallet
     */
    public function getSrcWallet() {
        return  Wallet::model()->findByPk($this->src_wallet);

    }
    public function getDstWallet() {
        return  Wallet::model()->findByPk($this->dst_wallet);
    }

	public function isBTCSell() {
        $wallet = $this->getSrcWallet();
		return $wallet->isBTCWallet();
	}

    public function isBTCBuy() {
        $wallet = $this->getSrcWallet();
        return $wallet->isUSDWallet();
    }

	public function getSellBTCEquivalent() {
        if ($this->isBTCSell()) {
            return $this->srcBTCEquivalent();
        }
        if ($this->isBTCBuy()) {
            return $this->dstBTCEquivalent();
        }
    }



    public function srcBTCEquivalent() {
        if ($this->isBTCSell()) {
            return $this->src_count;
        } else {
            return $this->src_count/$this->src_price;
        }
    }

    public function srcUSDEquivalent() {
        if ($this->isBTCBuy()) {
            return $this->src_count;
        } else {
            return $this->src_count*$this->src_price;
        }
    }

    public function dstBTCEquivalent() {
        if ($this->isBTCSell()) {
            return $this->dst_count;
        } else {
            return $this->dst_count/$this->src_price;
        }
    }

    public function dstUSDEquivalent() {
        if ($this->isBTCBuy()) {
            return $this->src_count;
        } else {
            return $this->src_count*$this->src_price;
        }
    }

}
