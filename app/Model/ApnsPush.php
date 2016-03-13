<?php
App::uses ( 'ApiModel', 'Model' );
App::import ( 'Vendor', 'ApnsPHP/Log/Interface' );
App::import ( 'Vendor', 'ApnsPHP/Log/Embedded' );
App::import ( 'Vendor', 'ApnsPHP/Abstract' );
App::import ( 'Vendor', 'ApnsPHP/Exception' );
App::import ( 'Vendor', 'ApnsPHP/Push' );
App::import ( 'Vendor', 'ApnsPHP/Message' );
App::import ( 'Vendor', 'ApnsPHP_Autoload', array (
		'file' => 'ApnsPHP' . DS . 'Autoload.php' 
) );

App::uses ( 'ApiModel', 'Model' );
/**
 * [class]APNS用Push通知モデルクラス
 *
 * APNSのPush通知に関する処理をまとめたモデルクラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Model
 */
class ApnsPush extends ApiModel {
	public $name = "ApnsPush";
	public $useTable = false;
	/**
	 * [function]ApnsPush通知
	 *
	 * @access public
	 * @param string $certFile
	 *        	SSL証明書（APNS認証用）
     * @param string $messageTitle
     *          メッセージタイトル
	 * @param string $messageBody
	 *        	メッセージ本文
	 * @param array $iosPushList
	 *        	Push対象端末リスト
	 * @return array $apnsResult APNSのPush結果をまとめたもの
	 */
	public function send($certFile, $messageTitle, $messageBody, $iosPushList) {
		try {
			
			// 1000件ずつ分割送信する
			$chunkList = array_chunk ( $iosPushList, 1000 );
			$sendCount = count ( $chunkList );
			$errorList = array ();
			$successCount = 0;
			$failureCount = 0;
			// $successFlag = false;
			
			/* --------APNS送信処理START-------- */
			// 環境設定
			// $env = Configure::read ( 'environment' ); // 環境判別
			/*if (strstr ( $fileName, 'production' )) { // 本番環境
				$pushEnv = ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION;
			} else { // SANDBOX環境
				$pushEnv = ApnsPHP_Abstract::ENVIRONMENT_SANDBOX;
			}*/
			// $filePath = ROOT . DS . 'app/Vendor/ApnsPHP/Certificates' . DS . $fileName;
            $tmpfname = tempnam("/andpush-tmp", "cert-tmp-");
            $handle = fopen($tmpfname, "w");
            fwrite($handle, $certFile);
            fclose($handle);

            $pushEnv = ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION;
			
			// 送信準備
			$push = new ApnsPHP_Push ( $pushEnv, $tmpfname );
			
			// 不要??固定??
			// $rootPath = ROOT . DS . 'app/Vendor/ApnsPHP/File' . DS . 'auth_root.pem';
			// $push->setRootCertificationAuthority ( $rootPath );
			$apnsResult = array ();
			$i = 0;
			
			foreach ( $chunkList as $chunkDeviceList ) {
				
				$this->logMsg ['title'] = '[APNS]送信対象';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$logBody = array ();
				$logBody ['text'] = $messageBody;
				$logBody ['device_tokens'] = $chunkDeviceList;
				$this->logMsg ['body'] = $logBody;
				$this->log ( $this->logMsg, LOG_PUSH );
				
				$push->connect ();
				
				foreach ( $chunkDeviceList as $device ) { // 5000件まではイケるらしい？
					try {
						$ApnsMessage = new ApnsPHP_Message ( $device ['MessageDevice'] ['push_token'] );
						$ApnsMessage->setCustomIdentifier ( "Message-Badge-3" );
						$ApnsMessage->setBadge ( 1 );
                        $ApnsMessage->setCustomProperty('title', $messageTitle);
						$ApnsMessage->setText ( $messageBody );
						$ApnsMessage->setExpiry ( 30 );
						$ApnsMessage->setSound ();
						$push->add ( $ApnsMessage );
						unset ( $ApnsMessage );
					} catch ( ApnsPHP_Message_Exception $e ) {
						$this->logMsg ['title'] = '[APNS]送信前例外エラー';
						$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
						$this->logMsg ['body'] = $e->getMessage ();
						$this->log ( $this->logMsg, LOG_PUSH );
						$this->sendAlert ( $this->logMsg );
						$apnsResult ['device_error_list'] ["$i"] ['push_token'] = $device ['MessageDevice'] ['push_token'];
						$apnsResult ['device_error_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_ERROR . SEND_DETAIL_SEPARATER. 'exception';
						$i ++;
					}
				}
				
				if (isset($apnsResult ['device_error_list']) && (count ( $apnsResult ['device_error_list'] ) == count ( $chunkDeviceList ))) { // そもそも全部ダメ
					$push->disconnect ();
					$failureCount ++;
					break;
				}
				
				// 一括送信
				$push->send ();
				
				// 結果エラー取得
                $rawErrors = null;
				$rawErrors = $push->getErrors ();
				
				$this->logMsg ['title'] = '[APNS]送信エラー結果';
				$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
				$this->logMsg ['body'] = $rawErrors;
				$this->log ( $this->logMsg, LOG_PUSH );
				$push->disconnect ();
				
				if (empty ( $rawErrors )) { // 完全成功
					$successCount ++;
				}
				if (count ( $rawErrors ) == count ( $chunkDeviceList )) { // 完全失敗
					$failureCount ++;
				}
				
				$errorList [] = $rawErrors;
				
				if ($sendCount > 1) {
					// sleep ( 0.1 );
				}
			}
			/* --------APNS送信処理END-------- */
			
			if ($sendCount == $successCount) {
				return true;
			}
			
			if ($sendCount == $failureCount) {
				return false;
			}
			
			// 送信結果のエラーコード確認
			foreach ( $errorList as $errors ) {
				
				foreach ( $errors as $error ) { // Androidと違いエラーだけ
					$apnsMessage = $error ['MESSAGE'];
					$token = $apnsMessage->getRecipient ();
					// $statusCode = $error ['ERRORS'] [0] ['statusCode'];
					
					$apnsResult ['device_error_list'] ["$i"] ['push_token'] = $token;
					if (isset ( $error ['ERRORS'] [0] ['statusMessage'] )) {
						$apnsResult ['device_error_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_ERROR . SEND_DETAIL_SEPARATER;
						$apnsResult ['device_error_list'] ["$i"] ['send_result_detail'] .= $error ['ERRORS'] [0] ['statusMessage'];
					} else { // 不明なエラー
						$apnsResult ['device_error_list'] ["$i"] ['send_result_detail'] = SEND_DETAIL_ERROR . SEND_DETAIL_SEPARATER;
						$apnsResult ['device_error_list'] ["$i"] ['send_result_detail'] .= 'unknown';
					}
					$i ++;
				}
			}

            $apnsResult ['process_result'] = RESULT_SUCCESS;
            $apnsResult ['send_result_key'] = 'send_success_subnormality'; // 準成功

			return $apnsResult;

		} catch ( Exception $e ) {
			$this->logMsg ['title'] = '[APNS]例外エラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] = $e->getMessage ();
			$this->log ( $this->logMsg, LOG_ERR );
			$this->sendAlert ( $this->logMsg );
			return false;
		}
	}
}