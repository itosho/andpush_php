<?php
App::uses ( 'AppController', 'Controller' );
/**
 * [class]API系コントローラー親クラス
 *
 * 各API系のコントローラークラスが継承する。
 * レスポンス送信等の共通処理をまとめたクラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Api
 * @package Controller
 * @property AuthApi $AuthApi
 */
class ApiController extends AppController {
	public $autoRender = false;
	public $autoLayout = false;
	public $name = 'Api';
	public $uses = array (
			'AuthApi' 
	);
	// ※パフォーマンス考慮してコントローラー側でレスポンス送信することにした
	// public $components = array ( 'RequestHandler' );
	
	// サービス情報（認証後取得する）
	protected $servicer = array ();
	// レスポンスデータ（JSON形式）
	protected $result = array ();
	// ログメッセージ
	protected $logMsg = array ();

    // マージする親クラス変更（AppControllerはマージ対象から外れる）
    protected $_mergeParent = 'ApiController';
	
	/**
	 * [function]共通（基本）エラーレスポンス送信用の関数
	 *
	 * @access protected
	 * @param integer $statusCode
	 *        	ステータスコード
	 * @param string $constKey
	 *        	定数キー
	 * @return void
	 */
	protected function commonError($statusCode=500, $constKey='unknown_error') {
        // レスポンス設定
        $this->result = array();
        $this->result['process_result'] = RESULT_ERROR;
        // 定数からエラー情報を取得
        $error = Configure::read($constKey);
        $this->result['error']['code'] = $error['code'];
        $this->result['error']['msg'] = $error['msg'];
        $jsonBody = json_encode ( $this->result, JSON_UNESCAPED_UNICODE );

        // エラーレスポンス返却
        $this->response->statusCode ( $statusCode );
        $this->response->type ( 'application/json' );
        $this->response->charset ( 'utf-8' );
		$this->response->body ( $jsonBody );
		$this->response->send ();
		exit ();
	}
	
	/**
	 * [function]レスポンス送信用の共通関数
	 *
	 * @access protected
	 * @return void
	 */
	protected function commonResponse() {

        //　エラーの場合
        if ($this->result['process_result'] == RESULT_ERROR && isset($this->result['status_code']) && isset($this->result['const_key'])) {
            self::commonError($this->result['status_code'], $this->result['const_key']);
        }

		$this->response->statusCode ( 200 );
		$this->response->type ( 'application/json' );
		$this->response->charset ( 'utf-8' );
		$jsonBody = json_encode ( $this->result, JSON_UNESCAPED_UNICODE );
		$this->response->body ( $jsonBody );
		$this->response->send ();
		exit ();
	}
	/**
	 * [function]共通チェック処理用のラッパー関数
	 *
	 * 拡張ヘッダーを利用した認証処理を行う。
	 * 認証成功後、サービス情報をプロパティにセットする。
	 *
	 * @access public
	 */
	public function beforeFilter() {
		// 拡張ヘッダー取得
		$pushCode = $this->request->header ( 'X_Push_Code' );
		$pushKey = $this->request->header ( 'X_Push_Key' );
		
		// 存在チェック
		if (! $pushCode || ! $pushKey) {
			// ログ記載
			$this->logMsg ['title'] = '[API認証]物理チェックエラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] [] = $pushCode;
			$this->logMsg ['body'] [] = $pushKey;
			$this->log ( $this->logMsg, LOG_DEBUG );
			
			self::commonError ( 401, 'auth_error' );
		}
		// 認証チェック
		$result = $this->AuthApi->authCheck ( $pushCode, $pushKey );
		if (empty ( $result )) {
			// ログ記載
			$this->logMsg ['title'] = '[API認証]論理チェックエラー' ;
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] [] = $pushCode;
			$this->logMsg ['body'] [] = $pushKey;
			$this->log ( $this->logMsg, LOG_DEBUG );
			
			self::commonError ( 401, 'auth_error' );
		}
		// 403系エラーチェック（将来的にあるかも。いまんとこなし。）
		
		// サービス情報セット（各APIで利用する）
		$this->servicer = $result ['Service'];
	}
}
