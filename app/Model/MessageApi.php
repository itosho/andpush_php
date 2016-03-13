<?php
App::uses('ApiModel', 'Model');
App::uses('Message', 'Model/ActiveRecord');
App::uses('MessageDevice', 'Model/ActiveRecord');
App::uses('Push', 'Model');

/**
 * [class]メッセージ系APIモデルクラス
 *
 * メッセージに関するAPIをまとめたモデルクラス。
 * MessageApiControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Message
 * @package Model
 */
class MessageApi extends ApiModel
{
    public $name = "MessageApi";
    public $useTable = 'messages';
    private $message;
    private $messageDevice;

    /**
     * [function]コンストラクタ関数
     *
     * Message系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->message = new Message ();
        $this->messageDevice = new MessageDevice ();
    }

    /**
     * [function]パラメーターチェック用関数（メッセージ情報取得API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function getParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }

    /**
     * [function]パラメーターチェック用関数（メッセージ情報削除API）
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function destroyParamCheck($params)
    {
        // 共通チェック
        if ($this->_commonParamCheck($params) === false) {
            return false;
        }

        return true;
    }
    
    
    /**
     * [function]メッセージ情報取得用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return array $result 取得結果をまとめたもの
     */
    public function getMessageInfo($servicer, $params)
    {
        // メッセージ情報取得
        $messageResult = $this->_findDevice($servicer, $params);

        if (empty ($messageResult)) { // メッセージ情報が存在しない場合
            // ログ記載
			$this->logMsg ['title'] = '[メッセージ情報取得API]存在チェックエラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] = $params;
			$this->log ( $this->logMsg, LOG_DEBUG );
			
			// 結果をまとめる
        	$result = array();
			$result['process_result'] = RESULT_ERROR;
			$result['status_code'] = 404;
			$result['const_key'] = 'exist_error';
			return $result;
        }

        // メッセージデバイス結果取得
        $fields = array(
            'push_target',
            'push_token',
        	'user_id',
            'send_result_detail'
        );
        $conditions = array(
            'message_id' => $params ['message_id'],
            'del_flag' => 0
        );
        $deviceResultList = $this->messageDevice->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));

        // 結果をまとめる
        $result = array();
        $result['process_result'] = RESULT_SUCCESS;
        $result['message_title'] = $messageResult ['Message'] ['message_title'];
        $result['message_body'] = $messageResult ['Message'] ['message_body'];
        if ($messageResult ['Message'] ['send_time'] != NULL) {
            $result['send_time'] = $messageResult ['Message'] ['send_time'];
        } else {
            $result['send_time'] = $messageResult ['Message'] ['modified'];
        }

        $result['send_result']['code'] = $messageResult ['Message'] ['send_result_code'];
        if($messageResult ['Message'] ['send_result_code']){
        	$send_result_key = 'send_reslut_code_' . $messageResult ['Message'] ['send_result_code'];
        	$result['send_result']['msg'] = Configure::read($send_result_key);
        } else {
        	$result['send_result']['msg'] = NULL;
        }
        
        /*$result['device_result_list'] = array();
        foreach ($deviceResultList as $deviceResult) { // モデル名除去（もっとスマートな方法募集中）　
            $result['send_result']['device_result_list'] [] = $deviceResult ['MessageDevice'];
        }*/

        $result ['device_result_list'] = Hash::extract($deviceResultList, '{n}.MessageDevice');

        return $result;
    }
    
    /**
     * [function]メッセージ論理削除用の関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return array $result 取得結果をまとめたもの
     */
    public function destroyMessage($servicer, $params)
    {
        // メッセージ情報取得
        $messageResult = $this->_findDevice($servicer, $params);

        if (empty ($messageResult)) { // メッセージ情報が存在しない場合
            // ログ記載
            $this->logMsg ['title'] = '[メッセージ情報削除API]存在チェックエラー';
            $this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
            $this->logMsg ['body'] = $params;
            $this->log ( $this->logMsg, LOG_DEBUG );

            // 結果をまとめる
            $result = array();
            $result['process_result'] = RESULT_ERROR;
            $result['status_code'] = 404;
            $result['const_key'] = 'exist_error';
            return $result;
        }
    
    	$this->begin(); // トランザクション開始
    	
    	// メッセージマスタ更新
    	$updData = array(
    			'id' => $params ['message_id'],
    			'service_id' => $servicer ['id'],
    			'del_flag' => 1
    	);
    	$updFields = array(
    			'del_flag'
    	);
    	$updResult = $this->message->save($updData, false, $updFields);
    	
    	if ($updResult === false) {
    		
    		$this->rollback();
    		
    		$this->logMsg ['title'] = '[メッセージ情報削除API]メッセージテーブル更新エラー';
    		$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
    		$this->logMsg ['body'] = $updData;
    		$this->log ( $this->logMsg, LOG_DEBUG );
    		$this->sendAlert ( $this->logMsg );
    		
    		// 結果をまとめる
    		$result = array();
    		$result['process_result'] = RESULT_ERROR;
    		$result['status_code'] = 500;
    		$result['const_key'] = 'save_error';
    		return $result;
    	}
    	
    	// メッセージ送信対象端末テーブル更新
    	$conditions = array(
    			'message_id' => $params ['message_id']
    	);
    	$updFields = array(
    			'del_flag' => 1
    	);
    	$updResult = $this->messageDevice->updateAll($updFields, $conditions);
    	 
    	if ($updResult === false) {
    	
    		$this->rollback();
    	
    		$this->logMsg ['title'] = '[メッセージ削除情報API]メッセージ端末テーブル更新エラー';
    		$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
    		$this->logMsg ['body'] = $conditions;
    		$this->log ( $this->logMsg, LOG_DEBUG );
    		$this->sendAlert ( $this->logMsg );
    	
    		// 結果をまとめる
    		$result = array();
    		$result['process_result'] = RESULT_ERROR;
    		$result['status_code'] = 500;
    		$result['const_key'] = 'save_error';
    		return $result;
    	}
    	
    	// コミット
    	$this->commit(); 
    	
    	// 結果をまとめる
    	$result = array();
    	$result['process_result'] = RESULT_SUCCESS;
    	$send_result_code = $messageResult ['Message'] ['send_result_code'];
    	if($send_result_code === NULL || $send_result_code == '' || $send_result_code == '1001' || $send_result_code == '2001') {
    		$result['delete_type'] = 'unsent';
    	} else {
    		$result['delete_type'] = 'sent';
    	}
    	
    	return $result;
    }

    /**
     * [function]メッセージ情報SQL取得用関数
     *
     * @access public
     * @param array $servicer
     *            サービス情報
     * @param array $params
     *            リクエストパラメーター
     * @return arrya $message 端末情報をまとめたもの
     */
    private function _findDevice($servicer, $params)
    {
        // メッセージマスタ結果取得
        $conditions = array(
            'id' => $params ['message_id'],
            'service_id' => $servicer ['id'],
            'del_flag' => 0
        );
        $message = $this->message->find('first', array(
            'conditions' => $conditions
        ));

        return $message;
    }

    /**
     * [function]リクエストパラメーターチェック関数（共通）
     *
     * メッセージ系API共通のバリデーションチェック
     *
     * @access private
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    private function _commonParamCheck($params)
    {
        // メッセージID
        if (!isset ($params ['message_id'])) { // 必須チェック
            return false;
        }
        if (!Validation::numeric($params ['message_id'])) { // 数値チェック
            return false;
        }
        return true;
    }
}