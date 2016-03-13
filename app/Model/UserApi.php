<?php
App::uses('ApiModel', 'Model');
App::uses('Device', 'Model/ActiveRecord');

/**
 * [class]ユーザー系APIモデルクラス
 *
 * ユーザーに関するAPIをまとめたモデルクラス。
 * UserApiControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category User
 * @package Model
 */
class UserApi extends ApiModel
{
    public $name = "UserApi";
    public $useTable = false;
    private $device;

    /**
     * [function]コンストラクタ関数
     *
     * ユーザー系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        $this->device = new Device ();
    }

    /**
     * [function]パラメーターチェック関数（ユーザー情報取得API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function getParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（ユーザー属性情報登録API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function propertyParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        // 属性情報
        if (!isset ($params ['properties']) || !is_array($params ['properties'])) {
            return false;
        }

        if (count($params['properties']) > 20 || count($params['properties']) < 1) {
            return false;
        }

        foreach ($params['properties'] as $key => $val) {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $key)) {
                return false;
            }
            if (!Validation::maxLength($val, 255)) { // 255文字以内
                return false;
            }
        }

        return true;
    }

    /**
     * [function]パラメーターチェック関数（ユーザー情報削除API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function destroyParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]ユーザー情報取得関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function getUserInfo($servicer, $params)
    {

        $result = array();

        // ユーザー端末情報取得
        $fields = array('push_target', 'push_token', 'last_send_result_detail AS device_status');

        $conditions = array();
        $conditions ['user_id'] = $params['user_id'];
        $conditions ['del_flag'] = 0;

        $deviceList = $this->device->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        if (!$deviceList) { // ユーザー情報が存在しない場合
            // ログ記載
            $this->logMsg ['title'] = '[ユーザー情報取得API]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $conditions;
            $this->log($this->logMsg, LOG_DEBUG);

            // 存在エラー
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }

        // MongoDB接続
        $mongo = new MongoClient(MONGO_SERVER);
        // データベース指定
        $db = $mongo->selectDB(MONGO_DB_NAME);
        // コレクション指定
        $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

        // ユーザー属性情報取得
        $properties = $coll->findOne(
            array('service_id' => $servicer['id'], 'user_id' => $params['user_id'])
        );

        // 結果をまとめる
        $result['process_result'] = RESULT_SUCCESS;
        if (!isset($properties['properties'])) { // 属性情報がない場合
            $result['properties'] = null;
        } else { // 属性情報がある場合
            $result['properties'] = $properties['properties'];
        }

        /*$result ['device_list'] = array();
        foreach ($deviceList as $device) { // モデル名除去（もっとスマートな方法募集中）　
            $result ['device_list'] [] = $device ['Device'];
        }*/

        $result ['device_list'] = Hash::extract($deviceList, '{n}.Device');

        return $result;
    }

    /**
     * [function]属性情報登録関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function setProperty($servicer, $params)
    {
        // ユーザー端末情報取得
        $fields = array('push_target', 'push_token', 'last_send_result_detail AS device_status');

        $conditions = array();
        $conditions ['user_id'] = $params['user_id'];
        $conditions ['del_flag'] = 0;

        $deviceList = $this->device->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        if (!$deviceList) { // ユーザー情報が存在しない場合
            // ログ記載
            $this->logMsg ['title'] = '[ユーザー属性情報登録API]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $conditions;
            $this->log($this->logMsg, LOG_DEBUG);

            // 存在エラー
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }

        // MongoDB接続
        $mongo = new MongoClient(MONGO_SERVER);
        // データベース指定
        $db = $mongo->selectDB(MONGO_DB_NAME);
        // コレクション指定
        $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

        $cnt = $coll->count(array('service_id' => $servicer['id'], 'user_id' => $params['user_id'], 'del_flag' => 0));

        $systime = new MongoDate();

        if ($cnt >= 1) { // 更新時
            $saveResult = $coll->update(
                array('service_id' => $servicer['id'], 'user_id' => $params['user_id'], 'del_flag' => 0), // WHERE句に相当
                array('$set' => array('properties' => $params['properties'], 'updated_at' => $systime)), // SET句に相当
                array('upsert' => true) // UPDATE or INSERT（存在しなければ挿入）
            );
        } else { // 新規作成時
            $saveResult = $coll->update(
                array('service_id' => $servicer['id'], 'user_id' => $params['user_id'], 'del_flag' => 0), // WHERE句に相当
                array(
                    '$set' => array(
                        'properties' => $params['properties'],
                        'created_at' => $systime,
                        'updated_at' => $systime,
                        'del_flag' => 0
                    )
                ), // SET句に相当
                array('upsert' => true) // UPDATE or INSERT（存在しなければ挿入）
            );
        }

        // 結果をまとめる
        $result = array();

        if ($saveResult) {
            $result['process_result'] = RESULT_SUCCESS;
            if ($cnt >= 1) {
                $result['save_type'] = 'update';
            } else {
                $result['save_type'] = 'create';
            }

        } else {
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'save_error';
        }

        return $result;
    }

    /**
     * [function]ユーザー情報物理削除関数
     *
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function destroyUser($servicer, $params)
    {
        // 端末マスタ削除（物理削除）
        $conditions = array();
        $conditions ['service_id'] = $servicer['id'];
        $conditions ['user_id'] = $params['user_id'];

        $sqlResult = $this->device->deleteAll($conditions);
        $sqlCnt = $this->device->getAffectedRows();

        if ($sqlCnt < 1) { // ユーザー情報が存在しない場合
            // ログ記載
            $this->logMsg ['title'] = '[ユーザー情報削除API]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $conditions;
            $this->log($this->logMsg, LOG_DEBUG);

            // 存在エラー
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }

        // MongoDB接続
        $mongo = new MongoClient(MONGO_SERVER);
        // データベース指定
        $db = $mongo->selectDB(MONGO_DB_NAME);
        // コレクション指定
        $coll = $db->selectCollection(MONGO_COL_USER_PROPERTIES);

        $mongoCnt = $coll->count(array('service_id' => $servicer['id'], 'user_id' => $params['user_id']));

        if ($mongoCnt >= 1) {
            $mongoResult = $coll->remove(
                array('service_id' => $servicer['id'], 'user_id' => $params['user_id'])
            );
        }

        if (isset($mongoResult['err'])) {
            $this->logMsg ['title'] = '[ユーザー情報削除API]MongoDB削除エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = array(
                'service_id' => $servicer['id'],
                'user_id' => $params['user_id'],
                'result' => $mongoResult
            );
            $this->log($this->logMsg, LOG_DEBUG);
            $this->sendAlert($this->logMsg);
        }

        // 結果をまとめる
        $result = array();
        if ($sqlResult === true) {
            $result['process_result'] = RESULT_SUCCESS;
            $result['device_count'] = $sqlCnt;
        } else {
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'delete_error';
        }

        return $result;
    }

    /**
     * [function]リクエストパラメーターチェック関数（共通）
     *
     * ユーザー系API共通のバリデーションチェック
     *
     * @access private
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    private function _commonParamCheck($params)
    {
        // ユーザーID
        if (!isset($params['user_id'])) { // 必須チェック
            return false;
        }
        if (!Validation::maxLength($params ['user_id'], 255)) { // 255文字以内
            return false;
        }

        // 端末情報存在チェック
        /*$conditions = array (
            'service_id' => $servicer ['service_id'],
            'user_id' => $params ['user_id']
        );
        $device = $this->device->find ( 'first', array (
            'conditions' => $conditions
        ) );

        if (empty($device)) {
            return false;
        }*/

        return true;
    }
}