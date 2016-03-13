<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]端末マスタテーブル用のモデルクラス
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
class Device extends ApiModel {
	public $name = "Device";
	public $useTable = 'devices';
	// public $primaryKey = 'device_id';
	/**
	 * [function]バルク（マルチプル）リプレース用の関数
	 *
	 * @access public
	 * @param integer $serviceId
	 *        	サービスID
	 * @param string $pushTarget
	 *        	Push対象OS
	 * @param array $devices
	 *        	登録端末リスト
	 * @param string $sendResultDetail
	 *        	送信結果詳細（一括更新時のみ）
	 * @param boolean $isUserId
	 *        	ユーザーID存在チェック
	 * @return boolean $result true=成功 / false=失敗
	 */
	public function bulkReplace($serviceId, $pushTarget, $devices, $sendResultDetail = NULL, $isUserId = true) {
		// 登録対象フィールド
		if ($isUserId === true) {
			$fields = array (
					'service_id',
					'push_target',
					'hash_token',
					'push_token',
                    'user_id',
					'last_send_result_detail',
					'created',
					'modified' 
			);
		} else {
			$fields = array (
					'service_id',
					'push_target',
					'hash_token',
					'push_token',
                    'last_send_result_detail',
					'created',
					'modified' 
			);
		}
		// バルクインサート用クエリ設定
		$holder = '(' . implode ( ',', array_fill ( 0, count ( $fields ), '?' ) ) . ')';
		$holders = implode ( ',', array_fill ( 0, count ( $devices ), $holder ) );
		
		// 擬似バインドパラメーター設定
		$bindParams = array ();
		foreach ( $devices as $device ) {
			$bindParams [] = $serviceId; // サービスID
			$bindParams [] = $pushTarget; // Push対象

            if (isset($device ['send_result_detail']) &&
                (strpos(SEND_DETAIL_REGISTRATION_ID, $device ['send_result_detail']) !== false)) {
                $details = explode(SEND_DETAIL_SEPARATER, $device ['send_result_detail']);

                if (isset($device[1])) {
                    $device['push_token'] = $details[1];
                }
            }

			if (isset ( $device ['MessageDevice'] )) {
				$bindParams [] = sha1($device ['MessageDevice'] ['push_token']); // ハッシュ済PUSHトークン
			} else {
				$bindParams [] = sha1($device ['push_token']); // ハッシュ済PUSHトークン
			}
			if (isset ( $device ['MessageDevice'] )) {
				$bindParams [] = $device ['MessageDevice'] ['push_token']; // PUSHトークン
			} else {
				$bindParams [] = $device ['push_token']; // PUSHトークン
			}
            if ($isUserId === true) {
                if (isset ( $device ['MessageDevice'] )) {
                    $bindParams [] = $device ['MessageDevice'] ['user_id']; // ユーザーID
                } else {
                    $bindParams [] = $device ['user_id']; // ユーザーID
                }
            }
			if (isset ( $sendResultDetail ) && $sendResultDetail != '') {
				$bindParams [] = $sendResultDetail; // 端末状態
			} else {
				// 'send_result_detail'はGCM送信時に追加したので、'MessageDevice'不要！
				$bindParams [] = $device ['send_result_detail']; // 端末状況
			}
			$bindParams [] = date ( 'Y-m-d H:i:s' ); // 登録日時
			$bindParams [] = date ( 'Y-m-d H:i:s' ); // 更新日時
		}
		
		$fields = implode ( ',', $fields );
		$sql = "REPLACE INTO devices ({$fields}) VALUES {$holders}";
		// SQL実行
		$insCount = $this->query ( $sql, $bindParams );
		if ($insCount === false) {
			// エラー処理
			return false;
		}
		// REPLACE時は判断不可のためコメントアウト
		/*if ($this->getAffectedRows () != count ( $devices )) {
			return false;
		}*/
		return true;
	}
}
