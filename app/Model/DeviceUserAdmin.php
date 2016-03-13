<?php
App::uses('AdminModel', 'Model');
App::uses('Message', 'Model/ActiveRecord');
App::uses('MessageDevice', 'Model/ActiveRecord');
App::uses('Device', 'Model/ActiveRecord');
App::uses('PropertyLabel', 'Model/ActiveRecord');
App::uses('Push', 'Model');

/**
 * [class]端末ユーザー管理系モデルクラス
 *
 * CMSの端末ユーザー管理に関する処理をまとめたモデルクラス。
 * DeviceUserControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category DeviceUser
 * @package Model
 */
class DeviceUserAdmin extends AdminModel
{
    public $name = "DeviceUser";
    public $useTable = 'devices';
    private $message;
    private $messageDevice;
    private $device;
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
        $this->message = new Message ();
        $this->messageDevice = new MessageDevice ();
        $this->device = new Device ();
        $this->propertyLabel = new PropertyLabel();
    }

    /**
     * [function]端末ユーザー情報（単体）取得関数
     *
     * サービス単位で端末ユーザー情報を取得する。
     * 属性情報も併せて取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @param string $deviceId
     *            端末ID
     * @return array $item メッセージ情報
     */
    public function readItem($serviceId, $deviceId)
    {
        // ①端末マスタ情報取得
        $mConditions = array(
            'service_id' => $serviceId,
            'id' => $deviceId,
            'del_flag' => 0
        );
        // SQL実行
        $mResult = $this->device->find('first', array(
            'conditions' => $mConditions
        ));

        if (empty($mResult)) {
            return array();
        }

        $item = Hash::extract($mResult, 'Device');

        // ②Push送信履歴取得
        $hFields = array(
            'message_id',
            'modified',
            'send_result_detail'
        );

        $hConditions = array(
            'push_target' => $item['push_target'],
            'push_token' => $item['push_token'],
            'del_flag' => 0
        );
        // SQL実行
        $hResult = $this->messageDevice->find('all', array(
            'fields'     => $hFields,
            'conditions' => $hConditions,
            'limit'      => 10,
            'order'      => array('modified DESC')
        ));
        // モデル名除去
        $item['message_list'] = Hash::extract($hResult, '{n}.MessageDevice');

        if ($item['user_id'] === null) {
            return $item;
        }

        // ③マルチデバイス確認
        $mdConditions = array(
            'service_id' => $serviceId,
            'user_id' => $item['user_id'],
            'id !=' => $deviceId,
            'del_flag' => 0
        );
        // SQL実行
        $mdResult = $this->device->find('all', array(
            'conditions' => $mdConditions
        ));

        if (!empty($mdResult)) {
            // モデル名除去
            $item['device_list'] = Hash::extract($mdResult, '{n}.Device');
        }

        // ④属性情報取得
        // MongoDB接続
        $mongo = new MongoClient(MONGO_SERVER);
        // データベース指定
        $db = $mongo->selectDB(MONGO_DB_NAME);
        // コレクション指定
        $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

        // ユーザー属性情報取得
        $properties = $coll->findOne(
            array('service_id' => $serviceId, 'user_id' => $item['user_id'])
        );

        // 結果をまとめる
        if (!isset($properties['properties'])) { // 属性情報がない場合
            // $item['properties'] = null;
        } else { // 属性情報がある場合
            $item['properties'] = $properties['properties'];
        }

        return $item;
    }

    /**
     * [function]メッセージ情報（単体）取得関数
     *
     * サービス単位でメッセージ情報を取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @return array $item 属性情報
     */
    public function readPropertyLabels($serviceId)
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
}