<?php
App::uses ( 'AppModel', 'Model' );
App::uses ( 'CakeEmail', 'Network/Email' );
/**
 * [class]API系モデル親クラス
 *
 * 各API系のモデルクラスが継承する。
 * トランザクション等の共通処理をまとめたクラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Api
 * @package Model
 */
class ApiModel extends AppModel {
	public $name = "Api";
    // サービス情報（認証後取得する）
    protected $servicer = array ();
	// ログメッセージ
	protected $logMsg = array ();
	/**
	 * [function]トランザクション開始用の関数
	 *
	 * @access protected
	 */
	protected function begin() {
		$dataSource = $this->getDataSource ();
		$dataSource->begin ( $this );
	}
	/**
	 * [function]トランザクションコミット用の関数
	 *
	 * @access protected
	 */
	protected function commit() {
		$dataSource = $this->getDataSource ();
		$dataSource->commit ( $this );
	}
	/**
	 * [function]トランザクションロールバック用の関数
	 *
	 * @access protected
	 */
	protected function rollback() {
		$dataSource = $this->getDataSource ();
		$dataSource->rollback ( $this );
	}
	/**
	 * [function]アラートメール送信用の関数
	 *
	 * @access public
	 */
	public function sendAlert($logMsg) {
		$email = new CakeEmail ();
		$from = 'alert@' . $_SERVER['HTTP_HOST'];
		$email->from ( $from );
		$email->to ( 'andpush@example.com' );
		$title = $logMsg ['title'];
		if (! is_string ( $logMsg ['body'] )) {
			$body = print_r ( $logMsg ['body'], true );
		} else {
			$body = $logMsg ['body'];
		}
		// メール送信
		$email->subject ( $title );
		$email->send ( $body );
	}
}
