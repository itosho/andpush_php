<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]メッセージマスターテーブル用のモデルクラス
 *
 * アクティブレコードなモデルクラス
 * 原則コントローラーから直接呼び出さない。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category ActiveRecord
 * @package Model
 */
class Message extends ApiModel {
	public $name = "Message";
	public $useTable = 'messages';
	// public $primaryKey = 'message_id';
	/**
	 * [function]メッセージ情報＆サービス情報取得用のSQL関数
	 *
	 * @access public
	 * @return array $messages SQL実行結果
	 */
	public function findOnService() {
		// SQL文
		$sql = "SELECT 
					m.id,
					m.message_title,
					m.message_body, 
					m.send_time, 
					s.id,
					s.ios_cert_file,
					s.android_api_key 
				FROM messages m 
				LEFT OUTER JOIN services s ON m.service_id = s.id
				WHERE 
					m.send_result_code = 2001
				AND m.del_flag = 0 
				AND s.service_status = 1
				AND s.del_flag = 0 
				AND m.send_time IS NOT NULL 
				AND (m.send_time BETWEEN (NOW() - INTERVAL 120 SECOND) AND (NOW() + INTERVAL 120 SECOND))";
		// SQL実行
		$messages = $this->query ( $sql );
		
		return $messages;
	}
}
