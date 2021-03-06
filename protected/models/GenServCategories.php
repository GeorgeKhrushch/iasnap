<?php

/**
 * This is the model class for table "gen_serv_categories".
 *
 * The followings are the available columns in table 'gen_serv_categories':
 * @property integer $id
 * @property string $name
 * @property string $visability
 * @property string $icon
 *
 * The followings are the available model relations:
 * @property GenCatClasses[] $genCatClasses
 */
class GenServCategories extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gen_serv_categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, visability', 'required'),
			array('name', 'length', 'max'=>60),
			array('visability', 'length', 'max'=>6),
			array('icon', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, visability, icon', 'safe', 'on'=>'search'),
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
			'genCatClasses' => array(self::HAS_MANY, 'GenCatClasses', 'categorie_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'visability' => 'Visability',
			'icon' => 'Icon',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('visability',$this->visability,true);
		$criteria->compare('icon',$this->icon,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GenServCategories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}










    
    public function getOrgMenu() {
         $t=array();
         $m=array();
         $pam='';
       $rows1=GenCatClasses::model()->findAllByAttributes(array('class_id'=>2));
         $menu=array();        
        foreach($rows1 as $row) 
        {
              
             $t=GenCatClasses::model()->getMenuOrg($row['id']);
             $m=$row['id'];
             
            if (isset($_GET['servid'])) {$pam=$_GET['servid'];} 
             $rows3=$this->findAllByPk($t);   
                foreach($rows3 as $row) {
                    if ($row['visability']=='так'){
                 $c=GenServCatClass::model()->getcountService($m);
                $menu=array(
                'label'=>'<div><table><tr><td width=33px><img src='.Yii::app()->baseUrl.'/images/icons/'.$row['icon'].' align=left></td><td><span>'.$row['name'].' <font size=1 color=#808080>('.$c.')</font></span></td></tr></table></div>',
                'url'=>array('/serv/?class=2&&servid='.$m.'#anchor1'),
                'active'=>$pam==$m,
                 'encodeLabel'=>false,
                //'linkOptions' => array('class' => 'listItemLink', 'title' => $row['title'])
            );
        }  }}
        return $menu;
    }
    
    
    
    
    
        public function getGromMenu() {
        $t = array();
        $m = array();
        $pam = '';
        $rows1 = GenCatClasses::model()->findAllByAttributes(array('class_id' => 1));
        $menu = array();
        foreach ($rows1 as $row) {
            $k = '';
            $t = GenCatClasses::model()->getMenuGrom($row['id']);
            $m = $row['id'];
            if (isset($_GET['servid'])) {
                $pam = $_GET['servid'];
            }
            $rows3 = $this->findAllByPk($t);
            foreach ($rows3 as $row) {
                if ($row['visability'] == 'так') {
                    $c = GenServCatClass::model()->getcountService($m);
                    $menu[] = array(
                        'label' => '<div><table><tr><td width=33px><img src=' . Yii::app()->baseUrl . '/images/icons/' . $row['icon'] . ' align=left></td><td><span>' . $row['name'] . ' <font size=1 color=#808080>(' . $c . ')</font></span></td></tr></table></div>',
                        'url' => array('/serv/?class=1&&servid=' . $m . '#anchor1'),
                        'active' => $pam == $m,
                            //'linkOptions' => array('class' => 'listItemLink', 'title' => $row['title'])
                            );
                }
            }
        }
        return $menu;
    }

    public function getOrgMenu1() {
         $t=array();
         $m=array();
         $pam='';
       $rows1=GenCatClasses::model()->findAllByAttributes(array('class_id'=>2));
                 
        foreach($rows1 as $row) 
        {
              
             $t=GenCatClasses::model()->getMenuOrg($row['id']);
             $m=$row['id'];
            if (isset($_GET['servid'])) {$pam=$_GET['servid'];} 
             $rows3=$this->findAllByPk($t);   
                foreach($rows3 as $row) {
                    if ($row['visability']=='так'){
                 $c=GenServCatClass::model()->getcountService($m);
                $menu[]=array(
                'label'=>'<div>'.$row['name'].' <font size=1 color=#808080>('.$c.')</font></div>',
                'url'=>array('/serv/?class=2&&servid='.$m.'#anchor1'),
                'active'=>$pam==$m,
                 'encodeLabel'=>false,
                //'linkOptions' => array('class' => 'listItemLink', 'title' => $row['title'])
            );
        }  }}
        return $menu;
    }
    


 public function getGromMenu1() {
              $t=array();
              $m=array(); $pam='';
       $rows1=GenCatClasses::model()->findAllByAttributes(array('class_id'=>1));  
            
        foreach($rows1 as $row) 
        {
           $k='';
             $t=GenCatClasses::model()->getMenuGrom($row['id']);
             $m=$row['id'];
             if (isset($_GET['servid'])) {$pam=$_GET['servid'];} 
             $rows3=$this->findAllByPk($t);   
                foreach($rows3 as $row) {
                     if ($row['visability']=='так'){
               $c=GenServCatClass::model()->getcountService($m);
                $menu[]=array(
                 'label'=>'<div>'.$row['name'].' <font size=1 color=#808080>('.$c.')</font></div>',
                'url'=>array('/serv/?class=1&&servid='.$m.'#anchor1'),
                'active'=>$pam==$m,
               
                //'linkOptions' => array('class' => 'listItemLink', 'title' => $row['title'])
           ) ;
        }  }}
        return $menu;



}
}