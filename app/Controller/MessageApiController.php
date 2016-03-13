<?php
App::uses('ApiController', 'Controller');

/**
 * [class]メッセージ系APIコントローラークラス
 *
 * メッセージに関するAPIをまとめたコントローラークラス。
 * エンドポイント単位でメソッドを定義する。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Message
 * @package Controller
 * @property MessageApi $MessageApi
 */
class MessageApiController extends ApiController
{
    public $name = 'MessageApi';
    public $uses = array(
        'MessageApi'
    );

    /**
     * [function]メッセージ情報取得APIコントローラー関数
     *
     * メッセージIDをもとにメッセージ情報とPush通知の処理結果を取得する。
     *
     * @access public
     */
    public function index()
    {
        try {
            // バリデーション
            $isValid = $this->MessageApi->getParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[メッセージ情報取得API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->MessageApi->getMessageInfo($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[メッセージ情報取得API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->MessageApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]メッセージ情報削除APIコントローラー関数
     *
     * メッセージIDをもとにメッセージ情報を削除する。
     * 送信済みのメッセージも削除可能。
     *
     * @access public
     */
    public function destroy()
    {
        try {
            // バリデーション
            $isValid = $this->MessageApi->destroyParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[メッセージ情報削除API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->MessageApi->destroyMessage($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[メッセージ情報削除API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->MessageApi->sendAlert($this->logMsg);

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
            $this->commonError(400, 'Bad Request');
        }
        parent::beforeFilter();
    }
}