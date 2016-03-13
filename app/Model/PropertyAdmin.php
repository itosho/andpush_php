<?php
App::uses('AdminModel', 'Model');
App::uses('PropertyLabel', 'Model/ActiveRecord');

/**
 * [class]属性情報管理系モデルクラス
 *
 * CMSの属性情報管理に関する処理をまとめたモデルクラス。
 * PropertyControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Property
 * @package Model
 */
class PropertyAdmin extends AdminModel
{
    public $name = "PropertyAdmin";
    public $useTable = 'property_labels';
    private $propertyLabel;

    /**
     * [function]コンストラクタ関数
     *
     * メッセージ管理で利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->propertyLabel = new PropertyLabel();
    }

    /**
     * [function]入力項目チェック用関数
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function inputCheck($params)
    {
        $errMsg = array();

        foreach($params['Property'] as $property) {

            if ($property['key_name'] != '') {

                if (!preg_match("/^[a-zA-Z0-9]+$/", $property['key_name'])) {
                    $errMsg[0] = "キー名は英数字で入力してください。";
                }

                if (!Validation::maxLength($property['key_name'], 50)) {
                    $errMsg[1] = "キー名は50文字以内で入力してください。";
                }

                if ($property['label_name'] == '') {
                    $errMsg[2] = "キー名を入力した場合はラベル名も入力してください。";
                }
            }

            if ($property['label_name'] != '') {

                if ($property['key_name'] == '') {
                    $errMsg[3] = "ラベル名を入力した場合はキー名も入力してください。";
                }

                if (!Validation::maxLength($property['label_name'], 50)) {
                    $errMsg[4] = "ラベル名は50文字以内で入力してください。";
                }
            }

        }

        return $errMsg;
    }

    /**
     * [function]プロパティラベル取得関数
     *
     * サービス単位でプロパティラベル情報を取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @return array $item 属性情報
     */
    public function readItem($serviceId)
    {
        // 条件（サービス単位で）
        $conditions = array(
            'service_id' => $serviceId,
            'del_flag' => 0
        );
        // SQL実行
        $result = $this->propertyLabel->find('all', array(
            'conditions' => $conditions
        ));

        $item = Hash::extract($result, '{n}.PropertyLabel');

        return $item;
    }

    /**
     * [function]Pushメッセージ更新処理用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @param array $properties
     *            属性情報
     * @return boolean $result 処理結果
     */
    public function updateProperty($serviceId, $properties)
    {
        $this->begin(); // トランザクション開始
        $delConditions = array('service_id' => $serviceId);
        $delResult = $this->propertyLabel->deleteAll($delConditions);

        if ($delResult === false) { // 失敗したとき
            $this->rollback();
            return false;
        }

        $saveData = array();
        $i = 0;
        foreach($properties as $property) {

            if (!empty($property['key_name']) && !empty($property['label_name'])) {
                $saveData[$i]['service_id'] = $serviceId;
                $saveData[$i]['key_name'] = $property['key_name'];
                $saveData[$i]['label_name'] = $property['label_name'];
                // $saveData[$i]['created'] = now();
                // $saveData[$i]['modified'] = now();
                // $saveData[$i]['del_flag'] = 0;
                $i++;
            }
        }

        if (empty($saveData)) {
            $this->commit(); // コミット
            return true;
        }

        $saveResult = $this->propertyLabel->saveAll($saveData);

        if ($saveResult === false) { // 失敗したとき
            $this->rollback();
            return false;
        }

        $this->commit(); // コミット
        return true;
    }
}