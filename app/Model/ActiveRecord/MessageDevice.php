<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]メッセージ端末テーブル用のモデルクラス
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
class MessageDevice extends ApiModel {
	public $name = "MessageDevice";
	public $useTable = 'message_devices';
	// public $primaryKey = 'send_id';
	/**
	 * [function]バルク（マルチプル）インサート用の関数
	 *
	 * @access public
	 * @param integer $messageId
	 *        	メッセージID
	 * @param array $params
	 *        	リクエストパラメーター
	 * @return boolean $result true=成功 / false=失敗
	 */
	public function bulkInsert($messageId, $params) {
		
		// 登録対象フィールド
		$fields = array (
				'message_id',
				'push_target',
				'push_token',
                'user_id',
				'send_result_detail',
				'created',
				'modified' 
		);
		// バルクインサート用クエリ設定
		$holder = '(' . implode ( ',', array_fill ( 0, count ( $fields ), '?' ) ) . ')';
		$holders = implode ( ',', array_fill ( 0, count ( $params ['device_list'] ), $holder ) );
		
		// 擬似バインドパラメーター設定
		$bindParams = array ();
		foreach ( $params ['device_list'] as $device ) {
			$bindParams [] = $messageId; // メッセージID
			$bindParams [] = $device ['push_target']; // push対象
			$bindParams [] = $device ['push_token']; // pushトークン
            if (isset ( $device ['user_id'] )) {
                $bindParams [] = $device ['user_id']; // ユーザーID
            } else {
                $bindParams [] = NULL; // ユーザーID
            }
			$bindParams [] = NULL; // 送信結果詳細
			$bindParams [] = date ( 'Y-m-d H:i:s' ); // 登録日時
			$bindParams [] = date ( 'Y-m-d H:i:s' ); // 更新日時
		}
		
		$fields = implode ( ',', $fields );
		$sql = "INSERT INTO message_devices ({$fields}) VALUES {$holders}";
		// SQL実行
		$insCount = $this->query ( $sql, $bindParams );
		if ($insCount === false) {
			// エラー処理
			return false;
		}
		if ($this->getAffectedRows () != count ( $params ['device_list'] )) {
			// エラー処理（登録件数が想定件数と異なる場合）
			return false;
		}
		return true;
	}
	
	/**
	 * [function]メッセージ送信回数＆送信端末台数取得用のSQL関数
	 *
	 * @access public
	 * @param string $service_id サービスID
     * @param string $statistic_date 統計日
	 * @return array $messagesDevice SQL実行結果
	 */
	public function getMessagesDevice($service_id, $statistic_date) {
		
		$statistic_date_from = date('Y-m-d 00:00:00', strtotime($statistic_date));
		
		$statistic_date_to = date('Y-m-d 23:59:59', strtotime($statistic_date));
		
		// SQL文
		$sql = "SELECT
				    CASE WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '05:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='00:00:00'THEN 'count_push_message_06'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '11:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='06:00:00'THEN 'count_push_message_12'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '17:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='12:00:00'THEN 'count_push_message_18'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '23:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='18:00:00'THEN 'count_push_message_24'
    			    END AS count_push_message_key,
				    CASE WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '05:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='00:00:00'THEN 'count_push_device_06'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '11:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='06:00:00'THEN 'count_push_device_12'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '17:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='12:00:00'THEN 'count_push_device_18'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '23:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='18:00:00'THEN 'count_push_device_24'
    			    END AS count_push_device_key,
					COUNT(distinct m.id) count_push_message,
				    COUNT(md.id) count_push_device
				FROM message_devices md
				INNER JOIN messages m ON m.id = md.message_id
				WHERE
					m.service_id = ". $service_id ."
				AND m.del_flag = 0
				AND md.del_flag = 0
				AND m.modified IS NOT NULL
				AND (m.modified BETWEEN '". $statistic_date_from ."' AND '". $statistic_date_to ."' )
				GROUP BY 
					CASE WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '05:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='00:00:00'THEN 'count_push_06'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '11:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='06:00:00'THEN 'count_push_12'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '17:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='12:00:00'THEN 'count_push_18'
                         WHEN DATE_FORMAT(m.modified, '%H:%i:%S')  <= '23:59:59' AND DATE_FORMAT(m.modified, '%H:%i:%S') >='18:00:00'THEN 'count_push_24'
    			    END		
						";
		
		// SQL実行
		$messagesDevice = $this->query ( $sql );
	
		return $messagesDevice;
	}
}
