<?php
App::uses('ApiController', 'Controller');

/**
 * [class]統計情報系APIコントローラークラス
 *
 * 統計情報に関するAPIをまとめたコントローラークラス。
 * エンドポイント単位でメソッドを定義する。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Statistic
 * @package Controller
 * @property StatisticApi $StatisticApi
 * @property AuthApi $AuthApi
 */
class StatisticApiController extends ApiController
{
    public $name = 'StatisticApi';
    public $uses = array(
        'StatisticApi'
    );

    /**
     * [function]統計情報取得API（日次）コントローラー関数
     *
     * 日次ベースの統計情報を取得する。
     *
     * @access public
     */
    public function daily()
    {
        try {
            // バリデーション
            $isValid = $this->StatisticApi->dailyParamCheck($this->request->query);
            if ($isValid === false) {
                // ログ記載
                $this->logMsg ['title'] = '[統計情報取得API（日次）]パラメーターエラー';
                $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
                $this->logMsg ['body'] = $this->request->query;
                $this->log($this->logMsg, LOG_DEBUG);

                // パラメーターエラー
                $this->commonError(400, 'param_error');
            }
            // メイン処理
            $this->result = $this->StatisticApi->getDaily($this->servicer, $this->request->query);

            // レスポンス
            $this->commonResponse();

        } catch (Exception $e) {
            $this->logMsg ['title'] = '[統計情報取得API（日次）]例外エラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $e->getMessage();
            $this->log($this->logMsg, LOG_ERR);
            $this->StatisticApi->sendAlert($this->logMsg);

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
        // リクエストメソッド確認（GET以外はエラー）
        if (!$this->request->isGet()) {
            $this->commonError(400, 'method_error');
        }
        parent::beforeFilter();
    }
}