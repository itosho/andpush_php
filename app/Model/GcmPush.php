<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]GCM用Push通知モデルクラス
 *
 * GCMのPush通知に関する処理をまとめたモデルクラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Model
 */
class GcmPush extends ApiModel {
	public $name = "GcmPush";
	public $useTable = false;
	/**
	 * [function]GCMPush通知
	 *
	 * @access public
	 * @param integer $serviceId
	 *        	サービスID
	 * @param string $apiKey
	 *        	APIキー（GCM認証用）
     * @param string $messageTitle
     *          メッセージタイトル
	 * @param string $messageBody
	 *        	メッセージ本文
	 * @param array $androidPushList
	 *        	Push対象端末リスト
	 * @return array $gcmResult GCMのPush結果をまとめたもの
	 */
	public function send($serviceId, $apiKey, $messageTitle, $messageBody, $androidPushList) {
		try {
			
			// 1000件ずつ分割送信する
			$chunkList = array_chunk ( $androidPushList, 1000 );
			$sendCount = count ( $chunkList );
			$responseList = array ();
			$successCount = 0;
			$failureCount = 0;
			$successFlag = false;
			
			/* --------GCM送信処理START-------- */
			// 認証キー設定
			$header = array (
					'Content-Type:application/json;charset=UTF-8',
					'Authorization:key=' . $apiKey
			);			
			
			foreach ( $chunkList as $chunkDeviceList ) {
				
				// Registration ID設定
				$registrationIdList = array ();
				foreach ( $chunkDeviceList as $device ) {
					$registrationIdList [] = $device ['MessageDevice'] ['push_token'];
				}
				
				// 送信内容設定
				$postList = array (
						"collapse_key" => "andpush_" . $serviceId, // 通知エリアでグループ化するためのキー
						"delay_while_idle" => true, // 端末がオフラインの場合、遅延して通知するか
						"time_to_live" => 864000, // 10日間：3600 * 24 * 10
						"data" => array (
                                "title"   => $messageTitle,
								"message" => $messageBody 
						),
						"registration_ids" => $registrationIdList 
				);
				$this->logMsg ['title'] = '[GCM]送信対象（エンコード前）';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $postList;
				$this->log ( $this->logMsg, LOG_PUSH );
				
				$post = json_encode ( $postList );
				
				// curlで送信する（簡単だから）
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				// curl_setopt ( $ch, CURLOPT_HEADER, TRUE );
				curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
				curl_setopt ( $ch, CURLOPT_POST, TRUE );
				curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
				curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 );
                $rawResponse = null;
                $rawResponse = curl_exec ( $ch );
				curl_close ( $ch );
				
				// レスポンス確認
                // $rawResponse = array();
				$rawResponse = json_decode ( $rawResponse, TRUE );
				$this->logMsg ['title'] = '[GCM]送信結果（デコード後）';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $rawResponse;
				$this->log ( $this->logMsg, LOG_PUSH );
				
				// 成功
				if ($rawResponse ['failure'] == 0 && $rawResponse ['canonical_ids'] == 0) {
					$successCount ++;
				}
				
				// 失敗
				if (! isset ( $rawResponse ['success'] )) {
					$failureCount ++;
				}				
				
				if ($rawResponse ['success'] > 0) {
					$successFlag = true;
				}
				
				$responseList [] = $rawResponse;
				
				if ($sendCount > 1) {
					// sleep ( 0.1 );
				}
			}
			/* --------GCM送信処理END-------- */
			
			if ($sendCount == $successCount) {
				return true;
			}
			
			if ($sendCount == $failureCount) {
				return false;
			}
			
			// レスポンス整理
			$gcmResult = array ();
			if ($successFlag === true) {
                $gcmResult ['process_result'] = RESULT_SUCCESS;
				$gcmResult ['send_result_key'] = 'send_success_subnormality'; // 準成功
			} else {
                $gcmResult ['process_result'] = RESULT_ERROR;
				$gcmResult ['send_result_key'] = 'send_error_provider'; // Push送信失敗
			}
			
			$i = 0;
			foreach ( $responseList as $response ) {
				
				foreach ( $response ['results'] as $result ) {
					$gcmResult ['device_result_list'] ["$i"] ['user_id'] = $androidPushList ["$i"] ['MessageDevice'] ['user_id'];
					$gcmResult ['device_result_list'] ["$i"] ['push_token'] = $androidPushList ["$i"] ['MessageDevice'] ['push_token'];
					if (isset ( $result ['message_id'] )) { // カノニカルID
						if (isset ( $result ['registration_id'] )) {
							$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_REGISTRATION_ID . SEND_DETAIL_SEPARATER;
							$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] .= $result ['registration_id'];
						} else { // 成功
							$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_SUCCESS;
						}
					} elseif (isset ( $result ['error'] )) { // エラー
						$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_ERROR . SEND_DETAIL_SEPARATER;
						$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] .= $result ['error'];
					} else { // 不明
						$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_ERROR . SEND_DETAIL_SEPARATER;
						$gcmResult ['device_result_list'] ["$i"] ['send_result_detail'] .= 'unknown';
					}
					$i ++;
				}
			}
			
			return $gcmResult;

		} catch ( Exception $e ) {
			$this->logMsg ['title'] = '[GCM]例外エラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] = $e->getMessage ();
			$this->log ( $this->logMsg, LOG_ERR );
			$this->sendAlert ( $this->logMsg );
			return false;
		}
	}
}