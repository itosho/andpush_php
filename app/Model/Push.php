<?php
App::uses ( 'ApiModel', 'Model' );
App::uses ( 'ApnsPush', 'Model' );
App::uses ( 'GcmPush', 'Model' );
App::uses ( 'Message', 'Model/ActiveRecord' );
App::uses ( 'MessageDevice', 'Model/ActiveRecord' );
App::uses ( 'Device', 'Model/ActiveRecord' );

/**
 * [class]Push通知モデルクラス
 *
 * Push通知に関する処理をまとめたモデルクラス。
 * シェルファイルかモデルクラスファイルから呼び出される。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Model
 */
class Push extends ApiModel {
	public $name = "Push";
	public $useTable = false;
	private $message;
	private $messageDevice;
	private $device;
	/**
	 * [function]コンストラクタ関数
	 *
	 * Push通知処理で利用するテーブルのモデルクラスをインスタンス化する。
	 *
	 * @access public
	 */
	public function __construct() {
		$this->message = new Message ();
		$this->messageDevice = new MessageDevice ();
		$this->device = new Device ();
	}
	/**
	 * [function]Push通知（メイン処理）
	 *
	 * @access public
	 * @param array $servicer
	 *        	サービス情報
	 * @param integer $messageId
	 *        	Push対象のメッセージID
     * @param string $messageTitle
     *          メッセージタイトル
	 * @param string $messageBody
	 *        	メッセージ本文
	 * @return array $result APNSとGCMのPush結果をマージしたたもの
	 */
	public function send($servicer, $messageId, $messageTitle, $messageBody) {
		try {
						
			$serviceId = $servicer ['id'];
			$certFile = $servicer ['ios_cert_file'];
			$iosResult = self::sendIos ( $serviceId, $certFile, $messageId, $messageTitle, $messageBody );
			
			$apiKey = $servicer ['android_api_key'];
			$androidResult = self::sendAndroid ( $serviceId, $apiKey, $messageId, $messageTitle, $messageBody );
			
			// APNSとGCMの結果をもとにレスポンスを整形する
			// iosだけ
			if (! empty ( $iosResult ) && empty ( $androidResult )) {
				$sendResult = $iosResult;
			}
			// Androidだけ
			if (empty ( $iosResult ) && ! empty ( $androidResult )) {
				$sendResult = $androidResult;
			}
			// 両方あり（これが大変！）
			if (! empty ( $iosResult ) && ! empty ( $androidResult )) {
				$totalResult = $iosResult ['process_result'] + $androidResult ['process_result'];
                if ($totalResult == 2) {
                    $sendResult ['process_result'] = RESULT_SUCCESS;

                    if ($iosResult ['send_result_key'] == 'send_success_normality' &&
                        $androidResult['send_result_key'] == 'send_success_normality') {

                        $sendResult['send_result_key'] = 'send_success_normality';

                    } else {
                        $sendResult['send_result_key'] = 'send_success_subnormality';
                    }


                } else {
                    $sendResult ['process_result'] = RESULT_ERROR;

                    if ($iosResult ['send_result_key'] == 'send_error_provider' ||
                        $androidResult['send_result_key'] == 'send_error_provider') {

                        $sendResult['send_result_key'] = 'send_error_provider';

                    } else {
                        $sendResult['send_result_key'] = 'send_error_andpush';
                    }
                }

			}
			// 両方なし（異常！）
			if (empty ( $iosResult ) && empty ( $androidResult )) {
				$this->logMsg ['title'] = '[Push通知]異常結果';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = 'iOS及びAndroid両方レスポンスなし！';
				$this->log ( $this->logMsg, LOG_PUSH );
				
				$sendResult = array ();
                $sendResult ['process_result'] = RESULT_ERROR;
                $sendResult ['send_result_key'] = 'send_error_andpush'; // Push失敗
			}
			return $sendResult;
		} catch ( Exception $e ) {
			$this->logMsg ['title'] = '[Push通知]例外エラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] = $e->getMessage ();
			$this->log ( $this->logMsg, LOG_ERR );
			$this->sendAlert ( $this->logMsg );
			
			$sendResult = array ();
            $sendResult ['process_result'] = RESULT_ERROR;
			$sendResult ['send_result_key'] = 'send_error_andpush'; // Push失敗
			return $sendResult;
		}
	}
	/**
	 * [function]iOSPush通知
	 *
	 * @access private
	 * @param integer $serviceId
	 *        	サービスID
	 * @param string $certFile
	 *        	SSL証明書（APNS認証用）
	 * @param integer $messageId
	 *        	メッセージID
     * @param string $messageTitle
     *          メッセージタイトル
	 * @param string $messageBody
	 *        	メッセージ本文
	 * @return array $iosResult iOSのPush結果をまとめたもの
	 */
	private function sendIos($serviceId, $certFile, $messageId, $messageTitle, $messageBody) {
		// iOS対象端末取得
		$iosPushList = $this->getPushList ( $messageId, OS_IOS );
		
		if (empty ( $iosPushList )) {
			return array (); // 0件の場合は空でレスポンスする
		}
		
		// APNS送信
		$apns = new ApnsPush ();
		$apnsResult = $apns->send ( $certFile, $messageTitle, $messageBody, $iosPushList );
		if ($apnsResult === true || $apnsResult === false) {
			// 一括設定用送信結果を設定する
			$sendResultDetail = ($apnsResult === true) ? SEND_DETAIL_SUCCESS : SEND_DETAIL_ERROR;
			$this->begin (); // トランザクション開始
			$fields = array (); // updateAllの時は引用符に注意する！
			$fields ['send_result_detail'] = "'" . $sendResultDetail . "'";
			$fields ['modified'] = "'" . date ( 'Y-m-d H:i:s' ) . "'";
			$conditions = array ();
			$conditions ['message_id'] = $messageId;
			// メッセージ端末テーブル一括更新
			$updResult = $this->messageDevice->updateAll ( $fields, $conditions );
			if ($updResult === false) {
				$this->logMsg ['title'] = '[Push通知]メッセージ端末テーブル一括更新エラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $messageId;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			// 端末マスタ一括置き換え
			$replaceResult = $this->device->bulkReplace ( $serviceId, OS_IOS, $iosPushList, $sendResultDetail );
			if ($replaceResult === false) {
				$this->logMsg ['title'] = '[Push通知]端末マスタ一括置き換えエラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $iosPushList;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			$this->commit (); // コミット（例外エラーが出ない場合はコミットする）
		} else {
			$this->begin (); // トランザクション開始
			// 一旦全部成功にする
			$sendResultDetail = SEND_DETAIL_SUCCESS;
			$fields = array (); // updateAllの時は引用符に注意する！
			$fields ['send_result_detail'] = "'" . $sendResultDetail . "'";
			$fields ['modified'] = "'" . date ( 'Y-m-d H:i:s' ) . "'";
			$conditions = array ();
			$conditions ['message_id'] = $messageId;
			// メッセージ端末テーブル一括更新
			$updResult = $this->messageDevice->updateAll ( $fields, $conditions );
			if ($updResult === false) {
				$this->logMsg ['title'] = '[Push通知]メッセージ端末テーブル一括更新エラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $messageId;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			// 端末マスタ一括置き換え
			$replaceResult = $this->device->bulkReplace ( $serviceId, OS_IOS, $iosPushList, $sendResultDetail );
			if ($replaceResult === false) {
				$this->logMsg ['title'] = '[Push通知]端末マスタ一括置き換えエラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $iosPushList;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			
			foreach ( $apnsResult ['device_error_list'] as $deviceError ) {
				// メッセージ端末テーブル更新
				// 更新フィールド
				$fields = array (); // updateAllの時は引用符に注意する！
				$fields ['send_result_detail'] = "'" . $deviceError ['send_result_detail'] . "'";
				$fields ['modified'] = "'" . date ( 'Y-m-d H:i:s' ) . "'";
				// 更新条件
				$conditions = array ();
				$conditions ['message_id'] = $messageId;
				$conditions ['push_token'] = $deviceError ['push_token'];
				$updResult = $this->messageDevice->updateAll ( $fields, $conditions );
				if ($updResult === false) {
					$this->logMsg ['title'] = '[Push通知]メッセージ端末テーブル更新エラー';
					$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
					$this->logMsg ['body'] = $deviceError ['push_token'];
					$this->log ( $this->logMsg, LOG_DEBUG );
					$this->sendAlert ( $this->logMsg );
				}
			}
			// 端末マスタ一括置き換え
			$replaceResult = $this->device->bulkReplace ( $serviceId, OS_IOS, $apnsResult ['device_error_list'], NULL, FALSE );
			if ($replaceResult === false) {
				$this->logMsg ['title'] = '[Push通知]端末マスタ一括置き換えエラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $apnsResult ['device_error_list'];
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			$this->commit (); // コミット（例外エラーが出ない場合はコミットする）
		}
		// レスポンスまとめる
		$iosResult = $this->setResult ( $apnsResult );
		
		return $iosResult;
	}
	
	/**
	 * [function]AndroidPush通知
	 *
	 * @access private
	 * @param integer $serviceId
	 *        	サービスID
	 * @param string $apiKey
	 *        	APIキー（GCM認証用）
	 * @param integer $messageId
	 *        	メッセージID
     * @param string $messageTitle
     *          メッセージタイトル
	 * @param string $messageBody
	 *        	メッセージ本文
	 * @return array $androidResult AndroidのPush結果をまとめたもの
	 */
	private function sendAndroid($serviceId, $apiKey, $messageId, $messageTitle, $messageBody) {
		// Android対象端末取得
		$androidPushList = $this->getPushList ( $messageId, OS_ANDROID );
		
		if (empty ( $androidPushList )) {
			return array (); // 0件の場合は空でレスポンスする
		}
		
		// GCM送信
		$gcm = new GcmPush ();
		$gcmResult = $gcm->send ( $serviceId, $apiKey, $messageTitle, $messageBody, $androidPushList );
		
		if ($gcmResult === true || $gcmResult === false) {
			// 一括設定用送信結果を設定する
			$sendResultDetail = ($gcmResult === true) ? SEND_DETAIL_SUCCESS : SEND_DETAIL_ERROR;
			$this->begin (); // トランザクション開始
			$fields = array (); // updateAllの時は引用符に注意する！
			$fields ['send_result_detail'] = "'" . $sendResultDetail . "'";
			$fields ['modified'] = "'" . date ( 'Y-m-d H:i:s' ) . "'";
			$conditions = array ();
			$conditions ['message_id'] = $messageId;
			// メッセージ端末テーブル一括更新
			$updResult = $this->messageDevice->updateAll ( $fields, $conditions );
			if ($updResult === false) {
				$this->logMsg ['title'] = '[Push通知]メッセージ端末テーブル一括更新エラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $messageId;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			// 端末マスタ一括置き換え
			$replaceResult = $this->device->bulkReplace ( $serviceId, OS_ANDROID, $androidPushList, $sendResultDetail );
			if ($replaceResult === false) {
				$this->logMsg ['title'] = '[Push通知]端末マスタ一括置き換えエラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $androidPushList;
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			$this->commit (); // コミット（例外エラーが出ない場合はコミットする）
		} else {
			$this->begin (); // トランザクション開始
			foreach ( $gcmResult ['device_result_list'] as $deviceResult ) {
				// メッセージ端末テーブル更新
				// 更新フィールド
				$fields = array (); // updateAllの時は引用符に注意する！
				$fields ['send_result_detail'] = "'" . $deviceResult ['send_result_detail'] . "'";
				$fields ['modified'] = "'" . date ( 'Y-m-d H:i:s' ) . "'";
				// 更新条件
				$conditions = array ();
				$conditions ['message_id'] = $messageId;
				$conditions ['push_token'] = $deviceResult ['push_token'];
				$updResult = $this->messageDevice->updateAll ( $fields, $conditions );
				if ($updResult === false) {
					$this->logMsg ['title'] = '[Push通知]メッセージ端末テーブル更新エラー';
					$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
					$this->logMsg ['body'] = $deviceResult ['push_token'];
					$this->log ( $this->logMsg, LOG_DEBUG );
					$this->sendAlert ( $this->logMsg );
				}
			}
			// 端末マスタ一括置き換え
			$replaceResult = $this->device->bulkReplace ( $serviceId, OS_ANDROID, $gcmResult ['device_result_list'] );
			if ($replaceResult === false) {
				$this->logMsg ['title'] = '[Push通知]端末マスタ一括置き換えエラー';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $gcmResult ['device_result_list'];
				$this->log ( $this->logMsg, LOG_DEBUG );
				$this->sendAlert ( $this->logMsg );
			}
			$this->commit (); // コミット（例外エラーが出ない場合はコミットする）
		}
		// レスポンスまとめる
		$androidResult = $this->setResult ( $gcmResult );
		
		return $androidResult;
	}
	/**
	 * [function]Push通知対象端末取得
	 *
	 * @access private
	 * @param integer $messageId
	 *        	メッセージID
	 * @param string $os
	 *        	対象OS
	 * @return array $pushList Push通知対象端末リスト
	 */
	private function getPushList($messageId, $os) {
		// 対象端末取得
		$fields = array (
				'user_id',
				'push_token' 
		);
		$conditions = array (
				'message_id' => $messageId,
				'push_target' => $os,
				'del_flag' => 0 
		);
		$pushList = $this->messageDevice->find ( 'all', array (
				'fields' => $fields,
				'conditions' => $conditions 
		) );
		
		return $pushList;
	}
	/**
	 * [function]Push通知結果整理
	 *
	 * @access private
	 * @param array $providerResult
	 *        	APNS/GCM結果
	 * @return array $result レスポンス用に整理した結果
	 */
	private function setResult($providerResult) {
		// レスポンスまとめる
		$result = array ();
		if ($providerResult === true) {
            $result ['process_result'] = RESULT_SUCCESS;
			$result ['send_result_key'] = 'send_success_normality'; // Push成功
		} elseif ($providerResult === false) {
            $result ['process_result'] = RESULT_ERROR;
			$result ['send_result_key'] = 'send_error_provider'; // Push失敗
		} else {
            $result ['process_result'] = $providerResult ['process_result'];
			$result ['send_result_key'] = $providerResult ['send_result_key'];
		}
		return $result;
	}
}