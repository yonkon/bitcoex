<?php

/**
 * This is the model class for table "{{wallet}}".
 *
 * The followings are the available columns in table '{{wallet}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property double $money
 * @property double $available
 */
class Wallet extends CActiveRecord
{
	const WALLET_TYPE_WMZ = 1;
	const WALLET_TYPE_BTC = 2;
	public static $WALLET_CURRENCY_USD = array(Wallet::WALLET_TYPE_WMZ);
	public static $WALLET_CURRENCY_BTC = array(Wallet::WALLET_TYPE_BTC);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wallet}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type', 'required'),
			array('user_id, type', 'numerical', 'integerOnly'=>true),
			array('money', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, type, money', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'type' => 'Type',
			'money' => 'Money',
			'available' => 'Available',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('money',$this->money);
		$criteria->compare('available',$this->available);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wallet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function isBTCWallet() {
        return in_array($this->type, Wallet::$WALLET_CURRENCY_BTC);
    }
	public function isUSDWallet() {
        return in_array($this->type, Wallet::$WALLET_CURRENCY_USD);
    }
}
