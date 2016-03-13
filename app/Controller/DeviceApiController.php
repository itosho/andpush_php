<?php
App::uses('ApiController', 'Controller');

/**
 * [class]端末系APIコントローラークラス
 *
 * 端末に関するAPIをまとめたコントローラークラス。
 * エンドポイント単位でメソッドを定義する。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Device
 * @package Controller
 * @property DeviceApi $DeviceApi
 */
class DeviceApiController extends ApiController
{
    public $name = 'DeviceApi';
    public $uses = array(
        'DeviceApi',
        'AuthApi'
    );

    /**
     * [function]端末情報取得APIコントローラー関数
     *
     * 指定したPushトークンの端末情報を取得する。
     * ユーザー情報が紐付いている場合は属性情報も取得する。
     *
     * @access public
     */
    public function index()
    {
        try {
            // バリデーション
            $isValid = $this->DeviceApi->getParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[端末情報取得API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->DeviceApi->getDeviceInfo($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[端末情報取得API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->DeviceApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]端末情報登録APIコントローラー関数
     *
     * 端末から送信されたPushトークンを登録する。
     * 既に存在する場合は更新する。
     *
     * @access public
     */
    public function entry()
    {
        try {
            // バリデーション
            $isValid = $this->DeviceApi->entryParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[端末情報登録API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->DeviceApi->entryDevice($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[端末情報登録API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->DeviceApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]開封情報登録APIコントローラー関数
     *
     * 端末から送信された開封（起動）情報を登録する。
     *
     * @access public
     */
    public function open()
    {
        try {
            // バリデーション
            $isValid = $this->DeviceApi->openParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[開封情報登録API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->DeviceApi->openDevice($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[開封情報登録API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->DeviceApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]端末情報クリアAPIコントローラー関数
     *
     * 端末情報を削除する。
     * 複数端末登録されている場合、ユーザー情報は削除しない。
     *
     * @access public
     */
    public function clear()
    {
        try {
            // バリデーション
            $isValid = $this->DeviceApi->clearParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[端末情報クリアAPI]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->DeviceApi->clearDevice($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[端末情報クリアAPI]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->DeviceApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]共通チェック処理用のラッパー関数
     *
     * 認証処理（親クラス）及びリクエストメソッドの確認を行う。
     *
     * @access public
     */
    public function beforeFilter()
    {
        // リクエストメソッド確認（POST以外はエラー）
        if (!$this->request->isPost()) {
            $this->commonError(400, 'method_error');
        }
        parent::beforeFilter();
    }
}