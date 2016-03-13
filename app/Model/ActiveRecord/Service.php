<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]サービスマスターテーブル用のモデルクラス
 *
 * アクティブレコードなモデルクラス
 * 原則コントローラーから直接呼び出さない。
 *
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category ActiveRecord
 * @package Model
 */
class Service extends ApiModel {
	public $name = "Service";
	public $useTable = 'services';
	// public $primaryKey = 'service_id';
	
	/**
	 * [function]サービス情報取得用のSQL関数
	 *
	 * @access public
	 * @return array $messages SQL実行結果
	 */
	public function getServicesInfo() {
		// SQL文
		$sql = "SELECT
					id,
				    service_code,
				    auth_key,
					ios_cert_path,
					android_api_key,
				    email,
				    name
				FROM services
				WHERE
					service_status = 1
				AND del_flag = 0 ";
		// SQL実行
		$messages = $this->query ( $sql );
	
		return $messages;
	}
	
}
