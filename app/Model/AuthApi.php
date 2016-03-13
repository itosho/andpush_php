<?php
App::uses ( 'ApiModel', 'Model' );
App::uses ( 'Service', 'Model/ActiveRecord' );
App::uses ( 'SimplePasswordHasher', 'Controller/Component/Auth' );
/**
 * [class]認証系APIモデルクラス
 *
 * 認証に関するAPIをまとめたモデルクラス。
 * 基本的にはApiControllerクラスから呼ばれることを想定している。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Auth
 * @package Model
 */
class AuthApi extends ApiModel {
	public $name = "AuthApi";
	public $useTable = 'services';
	private $service;
	/**
	 * [function]コンストラクタ関数
	 *
	 * 認証系APIで利用するテーブルのモデルクラスをインスタンス化する。
	 *
	 * @access public
	 */
	public function __construct() {
        parent::__construct();
		$this->service = new Service ();
	}
	/**
	 * [function]拡張ヘッダー認証処理用の関数
	 *
	 * @access public
	 * @param string $serviceCode
	 *        	サービスコード（≒ID）
	 * @param string $authKey
	 *        	認証キー（≒PW）
	 * @return array $result サービス情報（0件の場合は空）
	 */
	public function authCheck($serviceCode, $authKey) {
		
		// 認証キーハッシュ
		$passwordHasher = new SimplePasswordHasher ();
		$hashKey = $passwordHasher->hash ( $authKey );
		$this->log ( $hashKey, LOG_DEBUG );
		
		$fields = array (
				'id',
				'ios_cert_path',
				'android_api_key',
                'service_name'
		);
		
		$conditions = array (
				'service_code' => $serviceCode,
				'auth_key' => $hashKey,
				'service_status' => 1, // 契約中
				'del_flag' => 0 
		);
		$result = $this->service->find ( 'first', array (
				'fields' => $fields,
				'conditions' => $conditions 
		) );
		
		return $result;
	}
}