<?php
App::uses ( 'ApiController', 'Controller' );

/**
 * [class]Pushメッセージ系APIコントローラークラス
 *
 * Pushメッセージに関するAPIをまとめたコントローラークラス。
 * エンドポイント単位でメソッドを定義する。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Controller
 * @property PushApi $PushApi
 */
class PushApiController extends ApiController {
    public $name = 'MessageApi';
    public $uses = array (
        'PushApi',
        'AuthApi'
    );
    /**
     * [function]トークンベースPushメッセージ通知APIコントローラー関数
     *
     * Pushトークンベースに通知を行う。
     * Push通知系APIの基本となる。
     * 即時送信の場合は、実際にPush通知処理を行う。
     * 予約送信の場合は、メッセージの登録処理のみを行う。
     *
     * @access public
     */
    public function token() {
        try {
            // バリデーション
            $isValid = $this->PushApi->tokenParamCheck ( $this->request->data );
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[Push通知API-トークン]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log ( $this->logMsg, LOG_DEBUG );

                // パラメーターエラー
                $this->commonError ( 400, 'param_error' );
            }

            // メイン処理
            if (isset ( $this->request->data ['send_time'] ) && $this->request->data ['send_time'] != '') {
                // 予約送信
                $this->result = self::_reservedSend ();
            } else {
                // 即時送信
                $this->result = self::_realTimeSend ();
            }
            // レスポンス
            $this->commonResponse ();

        } catch ( Exception $e ) {
            $this->logMsg ['title'] = '[Push通知API-トークン]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage ();
            $this->log ( $this->logMsg, LOG_ERR );
            $this->PushApi->sendAlert ( $this->logMsg );
            // $this->log ( $e->getMessage (), LOG_ERR );

            $this->commonError ( 500, 'server_error' );
        }
    }

    /**
     * [function]ユーザーベースメッセージ通知APIコントローラー関数
     *
     * ユーザーIDから端末情報取得後、通常のPush通知処理を行う。
     * 即時送信の場合は、実際にPush通知処理を行う。
     * 予約送信の場合は、メッセージの登録処理のみを行う。
     *
     * @access public
     */
    public function user() {
        try {
            // バリデーション
            $isValid = $this->PushApi->userParamCheck ( $this->request->data );
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[Push通知API-ユーザー]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log ( $this->logMsg, LOG_DEBUG );

                // パラメーターエラー
                $this->commonError ( 400, 'param_error' );
            }

            // 端末情報取得
            $deviceList = $this->PushApi->getDeviceList($this->servicer, $this->request->data);

            if (empty($deviceList)) { // 端末情報がない場合
                $this->commonError ( 404, 'push_list_error' );
            }

            unset($this->request->data['user_list']);
            $this->request->data['device_list'] = $deviceList;

            // メイン処理
            if (isset ( $this->request->data ['send_time'] ) && $this->request->data ['send_time'] != '') {
                // 予約送信
                $this->result = self::_reservedSend ();
            } else {
                // 即時送信
                $this->result = self::_realTimeSend ();
            }
            // レスポンス
            $this->commonResponse ();
        } catch ( Exception $e ) {
            $this->logMsg ['title'] = '[Push通知API-ユーザー]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage ();
            $this->log ( $this->logMsg, LOG_ERR );
            $this->PushApi->sendAlert ( $this->logMsg );
            // $this->log ( $e->getMessage (), LOG_ERR );

            $this->commonError ( 500, 'server_error' );
        }
    }

    /**
     * [function]Push通知API（属性情報）のコントローラー関数
     *
     * 条件から端末情報取得後、通常のPush通知処理を行う。
     * 即時送信の場合は、実際にPush通知処理を行う。
     * 予約送信の場合は、メッセージの登録処理のみを行う。
     *
     * @access public
     */
    public function property() {
        try {
            // バリデーション
            $isValid = $this->PushApi->propertyParamCheck ( $this->request->data );
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[Push通知API-属性]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log ( $this->logMsg, LOG_DEBUG );

                // パラメーターエラー
                $this->commonError ( 400, 'param_error' );
            }

            // 端末情報取得
            $deviceList = $this->PushApi->getDeviceList($this->servicer, $this->request->data);

            if (empty($deviceList)) { // 端末情報がない場合
                $this->commonError ( 404, 'push_list_error' );
            }

            unset($this->request->data['user_list']);
            $this->request->data['device_list'] = $deviceList;

            // メイン処理
            if (isset ( $this->request->data ['send_time'] ) && $this->request->data ['send_time'] != '') {
                // 予約送信
                $this->result = self::_reservedSend ();
            } else {
                // 即時送信
                $this->result = self::_realTimeSend ();
            }
            // レスポンス
            $this->commonResponse ();
        } catch ( Exception $e ) {
            $this->logMsg ['title'] = '[Push通知API-属性]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage ();
            $this->log ( $this->logMsg, LOG_ERR );
            $this->PushApi->sendAlert ( $this->logMsg );
            // $this->log ( $e->getMessage (), LOG_ERR );

            $this->commonError ( 500, 'server_error' );
        }
    }

    /**
     * [function]全端末Push通知APIのコントローラー関数
     *
     * 全端末情報取得後、通常のPush通知処理を行う。
     * 即時送信の場合は、実際にPush通知処理を行う。
     * 予約送信の場合は、メッセージの登録処理のみを行う。
     *
     * @access public
     */
    public function all() {
        try {
            // バリデーション
            $isValid = $this->PushApi->allParamCheck ( $this->request->data );
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[Push通知API-全端末]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log ( $this->logMsg, LOG_DEBUG );

                // パラメーターエラー
                $this->commonError ( 400, 'param_error' );
            }

            // 端末情報取得
            $deviceList = $this->PushApi->getDeviceList($this->servicer, $this->request->data);

            if (empty($deviceList)) { // 端末情報がない場合
                $this->commonError ( 404, 'push_list_error' );
            }

            $this->request->data['device_list'] = $deviceList;

            // メイン処理
            if (isset ( $this->request->data ['send_time'] ) && $this->request->data ['send_time'] != '') {
                // 予約送信
                $this->result = self::_reservedSend ();
            } else {
                // 即時送信
                $this->result = self::_realTimeSend ();
            }
            // レスポンス
            $this->commonResponse ();
        } catch ( Exception $e ) {
            $this->logMsg ['title'] = '[Push通知API-全端末]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage ();
            $this->log ( $this->logMsg, LOG_ERR );
            $this->PushApi->sendAlert ( $this->logMsg );
            // $this->log ( $e->getMessage (), LOG_ERR );

            $this->commonError ( 500, 'server_error' );
        }
    }

    /**
     * [function]即時送信処理用の関数
     *
     * @access private
     * @return array $result レスポンス結果をまとめた情報
     */
    private function _realTimeSend() {
        // メッセージ登録処理
        $messageId = $this->PushApi->registMessage ( $this->servicer, $this->request->data );
        if (! ($messageId > 0)) { // エラー

            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'send_error_andpush';

        } else { // 途中まで成功

            if (isset($this->request->data ['message_title']) && $this->request->data ['message_title'] != '') {
                $messageTitle = $this->request->data ['message_title'];
            } else {
                $messageTitle = $this->servicer['service_name'];
            }

            // Push通知処理
            $pushResult = $this->PushApi->pushMessage ( $this->servicer, $messageId, $messageTitle, $this->request->data ['message_body'] );

            $result = $pushResult;
        }
        return $result;
    }
    /**
     * [function]予約送信処理用の関数
     *
     * @access private
     * @return array $result レスポンス結果をまとめた情報
     */
    private function _reservedSend() {
        // メッセージ登録処理
        $messageId = $this->PushApi->registMessage ( $this->servicer, $this->request->data );
        if (! ($messageId > 0)) { // エラー
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 500;
            $result['const_key'] = 'send_error_reserved';
        } else { // 成功
            $result ['process_result'] = RESULT_SUCCESS;
            $result ['send_result']['code'] = Configure::read("send_success_reserved.code");
            $result ['send_result']['msg'] = Configure::read("send_success_reserved.msg");
            $result ['message_id'] = $messageId;
        }
        return $result;
    }
    /**
     * [function]共通チェック処理用のラッパー関数
     *
     * 認証処理（親クラス）及びリクエストメソッドの確認を行う。
     *
     * @access public
     */
    public function beforeFilter() {
        // リクエストメソッド確認（POST以外はエラー）
        if (! $this->request->isPost ()) {
            $this->commonError ( 400, 'Bad Request' );
        }
        parent::beforeFilter ();
    }
}