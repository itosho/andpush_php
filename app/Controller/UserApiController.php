<?php
App::uses('ApiController', 'Controller');

/**
 * [class]ユーザー系APIコントローラークラス
 *
 * ユーザーに関するAPIをまとめたコントローラークラス。
 * エンドポイント単位でメソッドを定義する。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category User
 * @package Controller
 * @property UserApi $UserApi
 */
class UserApiController extends ApiController
{
    public $name = 'UserApi';
    public $uses = array(
        'UserApi'
    );

    /**
     * [function]ユーザー情報取得APIコントローラー関数
     *
     * 指定したユーザーの属性情報と端末情報を取得する。
     *
     * @access public
     */
    public function index()
    {
        try {
            // バリデーション
            $isValid = $this->UserApi->getParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[ユーザー情報取得API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->UserApi->getUserInfo($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[ユーザー情報取得API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->UserApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]ユーザー属性情報登録APIコントローラー関数
     *
     * 指定したユーザーの属性情報を登録する。
     * 既に存在する場合は更新する。
     *
     * @access public
     */
    public function property()
    {
        try {
            // バリデーション
            $isValid = $this->UserApi->propertyParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[ユーザー属性情報登録API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->UserApi->setProperty($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[ユーザー属性情報登録API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->UserApi->sendAlert($this->logMsg);

            $this->commonError(500, 'server_error');
        }
    }

    /**
     * [function]ユーザー情報削除APIコントローラー関数
     *
     * ユーザー情報を削除する。
     * ユーザー情報が設定されている端末情報も削除される。
     *
     * @access public
     */
    public function destroy()
    {
        try {
            // バリデーション
            $isValid = $this->UserApi->destroyParamCheck($this->request->data);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[ユーザー情報削除API]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->data;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }

            // メイン処理
            $this->result = $this->UserApi->destroyUser($this->servicer, $this->request->data);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[ユーザー情報削除API]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->UserApi->sendAlert($this->logMsg);

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