<?php
App::uses('ApiModel', 'Model');
App::uses('Device', 'Model/ActiveRecord');
App::uses('OpenLog', 'Model/ActiveRecord');

/**
 * [class]端末系APIモデルクラス
 *
 * 端末に関するAPIをまとめたモデルクラス。
 * DeviceApiControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Device
 * @package Model
 */
class DeviceApi extends ApiModel
{
    public $name = "DeviceApi";
    public $useTable = false;
    private $device;
    private $openLog;

    /**
     * [function]コンストラクタ関数
     *
     * 端末系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        $this->device = new Device ();
        $this->openLog = new OpenLog ();
    }

    /**
     * [function]パラメーターチェック関数（端末情報登録API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function entryParamCheck($params)
    {
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（開封情報登録API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function openParamCheck($params)
    {
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（端末情報クリアAPI）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function clearParamCheck($params)
    {
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（端末情報取得API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function getParamCheck($params)
    {
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }


    /**
     * [function]端末情報登録/更新用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function entryDevice($servicer, $params)
    {
        // 端末情報取得
        $device = $this->_findDevice($servicer, $params);

        $saveData = array();

        if (empty($device)) { // 新規登録時
            $saveType = 'create'; // レスポンスで利用
        } else { // 更新時
            // 更新時のみ主キーを登録データに追加する
            $saveData['id'] = $device ['Device']['id'];
            $saveType = 'update'; // レスポンスで利用
        }

        // 登録データ設定
        if (isset($params['user_id'])) {
            $saveData['user_id'] = $params['user_id'];
        }
        $saveData['service_id'] = $servicer ['id'];
        $saveData['push_target'] = $params['push_target'];
        $saveData['hash_token'] = sha1($params ['push_token']);
        $saveData['push_token'] = $params ['push_token'];
        $saveData['del_flag'] = 0;

        // 登録（更新）実行
        $saveResult = $this->device->save($saveData, false);

        $result = array();

        if ($saveResult['Device']['id'] > 0) {
            $result['process_result'] = RESULT_SUCCESS;
            $result['save_type'] = $saveType;
        } else {
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'save_error';
        }

        return $result;

    }

    /**
     * [function]開封情報登録関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function openDevice($servicer, $params)
    {
        $saveData = array();

        $saveData['service_id'] = $servicer ['id'];
        $saveData['push_target'] = $params['push_target'];
        $saveData['hash_token'] = sha1($params ['push_token']);
        $saveData['push_token'] = $params ['push_token'];
        if (isset($params['message_id'])) {
            $saveData['message_id'] = $params['message_id'];
        }
        if (isset($params['user_id'])) {
            $saveData['user_id'] = $params['user_id'];
        }

        $saveResult = $this->openLog->save($saveData, false);

        $result = array();

        if ($saveResult['OpenLog']['id'] > 0) {
            $result['process_result'] = RESULT_SUCCESS;

        } else {
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'save_error';
        }

        return $result;
    }

    /**
     * [function]端末情報クリア用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function clearDevice($servicer, $params)
    {
        // 端末情報取得
        $device = $this->_findDevice($servicer, $params);

        if (!$device) {
            // ログ記載
            $this->logMsg ['title'] = '[端末情報クリアAPI]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'][] = $params;
            $this->log($this->logMsg, LOG_DEBUG);

            // 結果をまとめる
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }

        $userId = $device['Device']['user_id'];

        if ($userId === null) { // ユーザーID未設定の場合
            $deviceCount = 0;
        } else { // ユーザーID設定済みの場合

            // 当該ユーザーIDが設定された端末数取得
            $conditions = array(
                'service_id' => $servicer ['id'],
                'user_id' => $userId,
                'del_flag' => 0
            );
            $deviceCount = $this->device->find('count', array(
                'conditions' => $conditions
            ));
        }

        // 削除条件
        $conditions = array(
            'service_id' => $servicer ['id'],
            'push_target' => $params ['push_target'],
            'hash_token' => sha1($params ['push_token']),
            'del_flag' => 0
        );
        // 物理削除
        $sqlResult = $this->device->deleteAll($conditions);

        if ($sqlResult === true && $deviceCount == 1) { // MySQL側削除成功＆端末数1件の場合
            // ユーザー属性情報も併せて削除
            // MongoDB接続
            $mongo = new MongoClient(MONGO_SERVER);
            // データベース指定
            $db = $mongo->selectDB(MONGO_DB_NAME);
            // コレクション指定
            $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

            // 削除条件
            $mongoConditions = array('service_id' => $servicer['id'], 'user_id' => $userId, 'del_flag' => 0);

            $mongoCnt = $coll->count($mongoConditions);

            if ($mongoCnt >= 1) {
                $mongoResult = $coll->remove($mongoConditions);
            } else {
                $mongoResult = true;
            }
        }

        if (isset($mongoResult['err'])) {
            $this->logMsg ['title'] = '[端末情報クリアAPI]MongoDB削除エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = array(
                'service_id' => $servicer['id'],
                'user_id' => $userId,
                'result' => $mongoResult
            );
            $this->log($this->logMsg, LOG_DEBUG);
            $this->sendAlert($this->logMsg);
        }

        // 結果をまとめる
        $result = array();
        if ($sqlResult === true) {
            $result['process_result'] = RESULT_SUCCESS;
            if ($deviceCount < 2) {
                $result['user_device_count'] = 0;
            } else {
                $result['user_device_count'] = $deviceCount - 1; // 削除端末を含まない
                $result['user_id'] = $userId;
            }
        } else {
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'delete_error';
        }

        return $result;
    }

    /**
     * [function]端末情報取得用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function getDeviceInfo($servicer, $params)
    {
        $result = array();

        // 端末情報取得
        $device = $this->_findDevice($servicer, $params);

        if (!$device) { // 端末情報が存在しない場合
            // ログ記載
            $this->logMsg ['title'] = '[端末情報取得API]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $params;
            $this->log($this->logMsg, LOG_DEBUG);

            // 結果をまとめる
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }

        $result['process_result'] = RESULT_SUCCESS;
        $result['device_status'] = $device['Device']['last_send_result_detail'];

        // ユーザーID取得
        $userId = $device['Device']['user_id'];

        if ($userId !== null && $userId != '') { // ユーザーIDが設定されている場合

            $result['user_id'] = $userId;

            // MongoDB接続
            $mongo = new MongoClient(MONGO_SERVER);
            // データベース指定
            $db = $mongo->selectDB(MONGO_DB_NAME);
            // コレクション指定
            $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

            $properties = $coll->findOne(
                array('service_id' => $servicer['id'], 'user_id' => $userId, 'del_flag' => 0)
            );

            if (!isset($properties['properties'])) { // 属性情報が設定されていない場合
                $result['properties'] = null;
            } else { // 属性情報が設定されている場合
                $result['properties'] = $properties['properties'];
            }

        } else { // ユーザーIDが設定されていない場合
            $result['user_id'] = null;
            $result['properties'] = null;
        }

        return $result;

    }

    /**
     * [function]端末情報SQL取得用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $device 端末情報をまとめたもの
     */
    private function _findDevice($servicer, $params)
    {

        // 端末情報取得
        $conditions = array(
            'service_id' => $servicer ['id'],
            'push_target' => $params ['push_target'],
            'hash_token' => sha1($params ['push_token']),
            'del_flag' => 0
        );
        $device = $this->device->find('first', array(
            'conditions' => $conditions
        ));

        return $device;
    }

    /**
     * [function]パラメーターチェック用関数（共通）
     *
     * 端末系API共通のバリデーションチェック
     *
     * @access private
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    private function _commonParamCheck($params)
    {
        // 対象
        if (!isset ($params ['push_target'])) {
            return false;
        }
        if (!($params['push_target'] == OS_IOS || $params['push_target'] == OS_ANDROID)) {
            return false;
        }

        // トークン
        if (!isset ($params ['push_token'])) {
            return false;
        }
        if (!Validation::notEmpty($params ['push_token'])) {
            return false;
        }

        // ユーザーID
        if (isset($params['user_id'])) {
            if (!Validation::maxLength($params ['user_id'], 255)) { // 255文字以内
                return false;
            }
        }

        // メッセージID
        if (isset($params['message_id'])) {
            if (!Validation::numeric($params ['message_id'])) { // 数値
                return false;
            }
        }

        return true;
    }
}