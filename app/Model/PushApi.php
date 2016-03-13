<?php
App::uses('ApiModel', 'Model');
App::uses('Message', 'Model/ActiveRecord');
App::uses('MessageDevice', 'Model/ActiveRecord');
App::uses('Device', 'Model/ActiveRecord');
App::uses('Push', 'Model');

/**
 * [class]Pushメッセージ系APIモデルクラス
 *
 * Pushメッセージに関するAPIをまとめたモデルクラス。
 * PushApiControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Model
 */
class PushApi extends ApiModel
{
    public $name = "PushApi";
    public $useTable = false;
    private $message;
    private $messageDevice;
    private $device;

    /**
     * [function]コンストラクタ関数
     *
     * Message系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->message = new Message ();
        $this->messageDevice = new MessageDevice ();
        $this->device = new Device ();
    }

    /**
     * [function]パラメーターチェック用関数（トークンベースPushメッセージ通知API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function tokenParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) return false;

        // 端末リスト
        if (!isset ($params ['device_list']) || empty ($params ['device_list'])) {
            return false;
        }

        /* if (count ( $params ['device_list'] ) > 1000) { // 1000件以上の場合（GCM考慮）
           return false;
       }*/

        foreach ($params ['device_list'] as $device) {
            // 対象
            if (!isset ($device ['push_target'])) {
                return false;
            }
            if (!($device['push_target'] == OS_IOS || $device['push_target'] == OS_ANDROID)) {
                return false;
            }

            // トークン
            if (!isset ($device ['push_token'])) {
                return false;
            }
            if (!Validation::notEmpty($device ['push_token'])) {
                return false;
            }

            // ユーザーID
            if (isset($device['user_id'])) {
                if (!Validation::maxLength($device ['user_id'], 255)) { // 255文字以内
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * [function]リクエストパラメーターチェック用の関数（ユーザーベースPushメッセージ通知API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function userParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) return false;

        // ユーザーリスト
        if (!isset ($params ['user_id_list']) || empty ($params ['user_id_list'])) {
            return false;
        }

        foreach ($params ['user_id_list'] as $userId) {
            // ユーザーID
            if (!isset ($userId)) {
                return false;
            }
            if (!Validation::maxLength($userId, 255)) { // 255文字以内
                return false;
            }
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（属性ベース）
     *
     * Push通知API（属性ベース）のリクエストパラメータをチェックする。
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function propertyParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) return false;

        // 条件
        if (!isset ($params ['conditions']) || empty ($params ['conditions'])) {
            return false;
        }

        if (count($params['conditions']) > 20) {
            return false;
        }

        foreach ($params['conditions'] as $key => $val) {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $key)) {
                return false;
            }
            if (! Validation::maxLength ( $val, 255 )) { // 255文字以内
                return false;
            }
        }

        return true;
    }

    /**
     * [function]リクエストパラメーターチェック関数（全端末）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function allParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]Pushメッセージ登録処理用の関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return integer $messageId メッセージID（エラー時は0）
     */
    public function registMessage($servicer, $params)
    {
        $this->begin(); // トランザクション開始
        $saveData = array();
        $saveData ['service_id'] = $servicer ['id']; // サービスID
        if (isset($params['message_title']) && $params['message_title']) {
            $saveData ['message_title'] = $params ['message_title']; // メッセージタイトル
        }

        $saveData ['message_body'] = $params ['message_body']; // メッセージ本文
        if (isset ($params ['send_time']) && $params ['send_time'] != '') {
            $saveData ['send_time'] = $params ['send_time']; // 送信時間
        }
        $saveData ['send_result_code'] = Configure::read("send_success_reserved.code"); // 送信結果コード（未送信≒予約成功）

        $saveResult = $this->message->save($saveData, false);

        $messageId = Hash::get($saveResult, 'Message.id');
        if (!($messageId > 0)) { // 失敗したとき
            $this->rollback();
            return 0;
        }
        // メッセージデバイス登録
        $result = $this->messageDevice->bulkInsert($messageId, $params);

        if ($result === false) { // 失敗したとき
            $this->rollback();
            return 0;
        }

        $this->commit(); // コミット
        return $messageId;
    }

    /**
     * [function]Push通知処理用の関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param integer $messageId
     *            メッセージID
     * @param string $messageTitle
     * @param string $messageBody
     *            メッセージ本文
     *
     * @return integer $pushResult Push処理結果をまとめたもの
     */
    public function pushMessage($servicer, $messageId, $messageTitle, $messageBody)
    {
        // Push処理
        $push = new Push ();
        $pushResult = $push->send($servicer, $messageId, $messageTitle, $messageBody);

        $sendResult = Configure::read($pushResult['send_result_key']);
        unset($pushResult['send_result_key']);

        if ($pushResult['process_result'] == RESULT_SUCCESS) {
            $pushResult['message_id'] = $messageId;
            $pushResult['send_result']['code'] =  $sendResult ['code'];
            $pushResult['send_result']['msg'] =  $sendResult ['msg'];
        } else {
            $pushResult['message_id'] = $messageId;
            $pushResult['error']['code'] =  $sendResult ['code'];
            $pushResult['error']['msg'] =  $sendResult ['msg'];
        }

        // メッセージマスタ更新
        $updData = array(
            'id' => $messageId,
            'send_result_code' => $sendResult ['code']
        );
        $updFields = array(
            'send_result_code'
        );
        $updResult = $this->message->save($updData, false, $updFields);

        if ($updResult === false) {
            $this->logMsg ['title'] = '[Push通知]メッセージテーブル更新エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $updData;
            $this->log ( $this->logMsg, LOG_DEBUG );
            $this->sendAlert ( $this->logMsg );
        }

        return $pushResult;
    }

    /**
     * [function]端末情報取得用の関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return array $result 取得結果をまとめたもの
     */
    public function getDeviceList($servicer, $params)
    {

        if (isset($params['conditions'])) {
            $params['user_id_list'] = $this->getUserIdList($servicer['id'], $params['conditions']);
        }

        // メッセージデバイス結果取得
        $fields = array(
            'push_target',
            'push_token',
            'user_id'
        );

        if (isset($params['user_id_list'])) { // ユーザーIDリストがある場合
            $conditions = array(
                'service_id' => $servicer['id'],
                'del_flag' => 0,
                'AND' => array(
                    'user_id' => $params['user_id_list']
                )
            );
        } else { // 全端末の場合
            $conditions = array(
                'service_id' => $servicer['id'],
                'del_flag' => 0
            );
        }

        $deviceResultList = $this->device->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        // 結果をまとめる
        $deviceList = array();
        foreach ($deviceResultList as $deviceResult) { // モデル名除去（もっとスマートな方法募集中）　
            $deviceList [] = $deviceResult ['Device'];
        }

        return $deviceList;
    }

    /**
     * [function]リクエストパラメーターチェック用の関数（共通）
     *
     * Push系API共通のバリデーションチェック
     *
     * @access private
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    private function _commonParamCheck($params)
    {
        // メッセージタイトル
        if (isset($params ['message_title'])) {
            if (!Validation::maxLength($params ['message_title'], 30)) {
                return false;
            }
        }

        // メッセージ本文
        if (!isset ($params ['message_body'])) {
            return false;
        }
        if (!Validation::notEmpty($params ['message_body'])) {
            return false;
        }
        if (!Validation::maxLength($params ['message_body'], 140)) { // 140文字以内（APNS / GCM考慮）
            return false;
        }
        // 送信時間
        if (isset ($params ['send_time'])) { // 予約送信時
            if (!Validation::datetime($params ['send_time'])) {
                return false;
            }
            // 未来日時判定
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($params ['send_time'])) {
                return false;
            }
            // 10分単位判定
            $shellTime = date('is', strtotime($params ['send_time']));
            $shellTime = substr($shellTime, 1, 3);
            if ($shellTime != '000') {
                return false;
            }
        }

        return true;
    }

    /**
     * [function]ユーザーIDリスト取得関数
     *
     * @access public
     * @param integer $serviceId
     *        	サービスId
     * @param array $conditions
     *        	条件
     * @return arrya $userIdList ユーザーIDリスト
     */
    public function getUserIdList($serviceId, $conditions) {

        // DB接続
        $mongo = new MongoClient();
        // データベース指定
        $db = $mongo->selectDB("expush_db");
        // コレクション指定
        $coll = $db->selectCollection("user_properties");

        $query = array();
        $query['service_id'] = $serviceId;
        // $query['del_flag'] = 0;
        foreach ($conditions as $key => $val) {
            $query['properties.'. $key] = $val;
        }

        $cursor = $coll->find($query, array('user_id'));

        $userIdList = array();
        $i = 0;

        foreach ($cursor as $doc) {
            $userIdList[$i] = $doc['user_id'];
            $i++;
        }

        $this->log($query, LOG_DEBUG);

        return $userIdList;
    }
}