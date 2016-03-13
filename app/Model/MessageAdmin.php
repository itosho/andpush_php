<?php
App::uses('AdminModel', 'Model');
App::uses('Message', 'Model/ActiveRecord');
App::uses('MessageDevice', 'Model/ActiveRecord');
App::uses('Device', 'Model/ActiveRecord');
App::uses('Push', 'Model');

/**
 * [class]メッセージ管理系モデルクラス
 *
 * CMSのメッセージ管理に関する処理をまとめたモデルクラス。
 * MessageControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Message
 * @package Model
 */
class MessageAdmin extends AdminModel
{
    public $name = "MessageAdmin";
    public $useTable = 'messages';
    private $message;
    private $messageDevice;
    private $device;

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

        // メッセージタイトル
        if (isset($params['Message']['message_title'])) {

            if (!Validation::maxLength($params['Message']['message_title'], 30)) {
                $errMsg[] = "タイトルは30文字以内で入力してください。";
            }
        }

        // メッセージ本文
        if (!isset ($params['Message']['message_body'])) {
            $errMsg[] = "メッセージ内容は必ず入力してください。";
        }
        if (!Validation::notEmpty($params['Message']['message_body'])) {
            $errMsg[] = "メッセージ内容は必ず入力してください。";
        }
        if (!Validation::maxLength($params['Message']['message_body'], 140)) { // 140文字以内（APNS / GCM考慮）
            $errMsg[] = "メッセージ内容は140文字以内で入力してください。";
        }

        // 送信方法
        if ($params['Message']['send_type'] != "1" && $params['Message']['send_type'] != "0") {
            $errMsg[] = "送信方法が正しくありません。";
        }

        // 送信時間
        if ($params['Message']['send_type'] == "1" && isset ($params['Message']['send_time'])) { // 予約送信時

            $year = Hash::get($params, 'Message.send_time.year');
            $month = Hash::get($params, 'Message.send_time.month');
            $day = Hash::get($params, 'Message.send_time.day');
            $hour = Hash::get($params, 'Message.send_time.hour');
            $min = Hash::get($params, 'Message.send_time.min');
            $sendTime = "$year-$month-$day $hour:$min:00";

            if (!Validation::datetime($sendTime)) {
                $errMsg[] = "送信時間の形式が正しくありません。";
            }
            // 未来日時判定
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($sendTime)) {
                $errMsg[] = "送信時間は未来の時間を設定してください。";
            }
            // 10分単位判定
            $shellTime = date('is', strtotime($sendTime));
            $shellTime = substr($shellTime, 1, 3);
            if ($shellTime != '000') {
                $errMsg[] = "指定した送信時間は設定することが出来ません。";
            }
        }

        return $errMsg;
    }

    /**
     * [function]入力項目チェック用関数（Ajax用）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function inputSearchSegmentCheck($params)
    {
        $errMsg = array();

        if (count($params) > 20) {
            $errMsg[] = "属性情報が20項目以上設定されています。";
        }

        if (empty($params)) {
            $errMsg[] = "属性情報が設定されていません。";
        }

        foreach ($params as $key => $val) {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $key)) {
                $errMsg[] = "属性キーが不正です。";
            }
            if (!Validation::maxLength($val, 255)) { // 255文字以内
                $errMsg[] = "属性値は255文字以内に設定してください。";
            }
        }

        return $errMsg;
    }

    /**
     * [function]メッセージ情報（単体）取得関数
     *
     * サービス単位でメッセージ情報を取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @param string $messageId
     *            メッセージID
     * @return array $item メッセージ情報
     */
    public function readItem($serviceId, $messageId)
    {
        // 条件（サービス単位で）
        $mConditions = array(
            'service_id' => $serviceId,
            'id' => $messageId,
            'del_flag' => 0
        );
        // SQL実行
        $result = $this->message->find('first', array(
            'conditions' => $mConditions
        ));

        if (empty($result)) {
            return array();
        }

        // 条件
        $dConditions = array(
            'message_id' => $messageId,
            'del_flag' => 0
        );
        // SQL実行
        $count = $this->messageDevice->find('count', array(
            'conditions' => $dConditions
        ));

        // モデル名除去
        $item = Hash::extract($result, 'Message');
        //　件数追加
        $item['count'] = $count;

        return $item;
    }

    /**
     * [function]端末情報取得用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @param array $deviceConditions
     *            端末抽出条件
     * @return array $deviceList 端末情報リスト
     */
    public function getDeviceList($serviceId, $deviceConditions)
    {
        // 取得フィールド
        $fields = array(
            'push_target',
            'push_token',
            'user_id'
        );

        if (!empty($deviceConditions)) {
            // @TODO
        }

        // 条件
        $conditions = array(
            'service_id' => $serviceId,
            'del_flag' => 0
        );

        // SQL実行
        $result = $this->device->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        // モデル名除去
        $deviceList = Hash::extract($result, '{n}.Device');
        return $deviceList;
    }

    /**
     * [function]端末台数取得用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @return integer  $count 端末台数
     */
    public function getDeviceCount($serviceId)
    {
        // 条件
        $conditions = array(
            'service_id' => $serviceId,
            'del_flag' => 0
        );

        // SQL実行
        $count = $this->device->find('count', array(
            'conditions' => $conditions
        ));

        return $count;
    }

    /**
     * [function]端末情報取得用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @param array $deviceConditions
     *            端末抽出条件
     * @return array $deviceList 端末情報リスト
     */
    public function getDeviceListByProperties($serviceId, $deviceConditions)
    {
        // 取得フィールド
        $fields = array(
            'push_target',
            'push_token',
            'user_id'
        );

        // DB接続
        $mongo = new MongoClient();
        // データベース指定
        $db = $mongo->selectDB("expush_db");
        // コレクション指定
        $coll = $db->selectCollection("user_properties");

        $query = array();
        $userIdList = array();

        foreach ($deviceConditions as $key => $val) {
            if ($val == '') {
                continue;
            }
            $query['properties.' . $key] = $val;
        }

        if (!empty($query)) {
            $query['service_id'] = $serviceId;
            // $query['del_flag'] = 0;
            $cursor = $coll->find($query, array('user_id'));

            $i = 0;
            foreach ($cursor as $doc) {
                $userIdList[$i] = $doc['user_id'];
                $i++;
            }

            if (!empty($userIdList)) { // ユーザーIDリストがある場合
                $conditions = array(
                    'service_id' => $serviceId,
                    'del_flag' => 0,
                    'AND' => array(
                        'user_id' => $userIdList
                    )
                );
            } else {
                return array();
            }

        } else {
            $conditions = array(
                'service_id' => $serviceId,
                'del_flag' => 0
            );
        }


        // SQL実行
        $result = $this->device->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        // モデル名除去
        $deviceList = Hash::extract($result, '{n}.Device');

        return $deviceList;
    }

    /**
     * [function]Pushメッセージ登録処理用の関数
     *
     * @access public
     * @param array $serviceId
     *            サービスID
     * @param array $pushContents
     *            Push情報
     * @param array $deviceList
     *            端末リスト
     * @return integer $messageId メッセージID（エラー時は0）
     */
    public function registMessage($serviceId, $pushContents, $deviceList)
    {
        $this->begin(); // トランザクション開始
        $saveData = array();
        $saveData ['service_id'] = $serviceId; // サービスID
        if (isset($pushContents['message_title'])) {
            $saveData ['message_title'] = $pushContents ['message_title']; // メッセージタイトル
        }

        $saveData ['message_body'] = $pushContents ['message_body']; // メッセージ本文
        if (isset ($pushContents ['send_time']) && $pushContents ['send_time'] != '') {
            $saveData ['send_time'] = $pushContents ['send_time']; // 送信時間
        }
        $saveData ['send_result_code'] = Configure::read("send_success_reserved.code"); // 送信結果コード（未送信≒予約成功）

        // メッセージマスタ登録
        $saveResult = $this->message->save($saveData, false);

        $messageId = Hash::get($saveResult, 'Message.id');
        if (!($messageId > 0)) { // 失敗したとき
            $this->rollback();
            return 0;
        }
        $params = array();
        $params['device_list'] = $deviceList;
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
     * [function]Pushメッセージ更新処理用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @param array $pushContents
     *            Push情報
     * @param array $deviceList
     *            端末リスト
     * @param integer $messageId
     * @return integer $messageId メッセージID（エラー時は0）
     */
    public function updateMessage($serviceId, $pushContents, $deviceList, $messageId)
    {
        $this->begin(); // トランザクション開始
        $saveData = array();
        $saveData ['id'] = $messageId; // メッセージID（主キー）
        $saveData ['service_id'] = $serviceId; // サービスID
        if (isset($pushContents['message_title'])) {
            $saveData ['message_title'] = $pushContents ['message_title']; // メッセージタイトル
        }

        $saveData ['message_body'] = $pushContents ['message_body']; // メッセージ本文
        if (isset ($pushContents ['send_time']) && $pushContents ['send_time'] != '') {
            $saveData ['send_time'] = $pushContents ['send_time']; // 送信時間
        }
        $saveData ['send_result_code'] = Configure::read("send_success_reserved.code"); // 送信結果コード（未送信≒予約成功）

        // メッセージマスタ登録
        $saveResult = $this->message->save($saveData, false);

        if ($saveResult === false) { // 失敗したとき
            $this->rollback();
            return 0;
        }

        // メッセージデバイス削除
        $conditions = array('message_id' => $messageId);
        $deleteResult = $this->messageDevice->deleteAll($conditions);

        if ($deleteResult === false) { // 失敗したとき
            $this->rollback();
            return 0;
        }

        $params = array();
        $params['device_list'] = $deviceList;
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
     * @param array $services
     *            サービスID
     * @param integer $messageId
     *            メッセージID
     * @param array $pushContents
     *            push情報
     *
     * @return integer $pushResult Push処理結果をまとめたもの
     */
    public function pushMessage($services, $messageId, $pushContents)
    {
        // Push処理
        $push = new Push ();
        $pushResult = $push->send($services, $messageId, $pushContents['message_title'], $pushContents['message_body']);

        $sendResult = Configure::read($pushResult['send_result_key']);

        unset($pushResult['send_result_key']);
        $pushResult['send_result']['code'] = $sendResult ['code'];
        $pushResult['send_result']['msg'] = $sendResult ['msg'];

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
            $this->log($this->logMsg, LOG_DEBUG);
            $this->sendAlert($this->logMsg);
        }

        return $pushResult;
    }

    /**
     * [function]メッセージ情報削除用の関数
     *
     * @access public
     * @param  integer $serviceId サービスID
     * @param  string $messageId メッセージID
     *
     * @return boolean true / false
     *
     */
    public function deleteItem($serviceId, $messageId)
    {
        $this->begin(); // トランザクション開始

        // メッセージマスタ更新
        $updData = array(
            'id' => $messageId,
            'service_id' => $serviceId,
            'del_flag' => 1
        );
        $updFields = array(
            'del_flag'
        );
        $mResult = $this->message->save($updData, false, $updFields);

        if ($mResult === false || $this->getAffectedRows() != 1) {
            $this->rollback();
            return false;
        }

        // メッセージ送信対象端末テーブル更新
        $conditions = array(
            'message_id' => $messageId
        );
        $updFields = array(
            'del_flag' => 1
        );
        $dResult = $this->messageDevice->updateAll($updFields, $conditions);

        if ($dResult === false) {
            $this->rollback();
            return false;
        }

        $this->commit(); // コミット
        return true;
    }
}