<?php
class MessageApiControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
			'app.service',
			'app.device',
			'app.message',
			'app.message_device'
			
	);
	
    public function setUp() {
        parent::setUp();
        
        // service_id取得用
        $_SERVER['HTTP_X_PUSH_CODE'] = "testcode01";
        $_SERVER['HTTP_X_PUSH_KEY'] = "testkey01";
    }

    /**
     * test push token(Pushメッセージ送信API（トークン）)
     * 
     * Pushメッセージ送信API（トークン）を利用して、メッセージデータを作成する
     */
    public function testPushToken() {
    
    	$expected = Array ( 'process_result' => 1,
    			'send_time' => '2015-07-08 15:00:00',
        		'send_result' => Array ( 'code' => '2001',
        				'msg' => 'Pushメッセージ送信予約処理が成功しました。') ,
    			'message_id' => 1
    			 );
    			
    	$data = Array ( 'message_title' => 'andpush更新',
    			'message_body' => 'andpush更新があります。',
    			'send_time' => '2015-07-08 15:00:00',
    			'device_list' => Array ( Array ( 'push_target' => 'ios', 'push_token' => 'device_token_test_001', 'user_id' => 1),
    							Array ( 'push_target' => 'android', 'push_token' => 'registration_id_test_001', 'user_id' => null) ) );
    
    	$json = $this->testAction("/v2/push/token", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
     	$this->assertEqual(hash::get((array)$result, 'send_result.code'),  hash::get($expected, 'send_result.code'));
     	$this->assertEqual(hash::get((array)$result, 'send_result.msg'),  hash::get($expected, 'send_result.msg'));
    
    	return $result;
    
    }
    
    /**
     * test get message(メッセージ送信情報取得API)
     * 
     * @depends testPushToken
     */
    public function testGetMessage($result) {

    	if($result ['process_result'] == 1) {
    		$send_result_code = $result ['send_result'] ['code'];
    		$send_result_msg = $result ['send_result'] ['msg'];
    	} else {
    		$send_result_code = $result ['error'] ['code'];
    		$send_result_msg = $result ['error'] ['msg'];
    	}
    	
        $expected = Array ( 'process_result' => 1, 
        		'message_title' => 'andpush更新',
        		'message_body' => 'andpush更新があります。',
        		'send_time' => '2015-07-08 15:00:00',
        		'send_result' => Array ( 'code' => $send_result_code,
        				'msg' => $send_result_msg,
        				'device_result_list' => Array ( Array ( 'push_target' => 'ios', 'push_token' => 'device_token_test_001', 'user_id' => 1, 'send_result_detail' => null), 
        						                        Array ( 'push_target' => 'android', 'push_token' => 'registration_id_test_001', 'user_id' => null, 'send_result_detail' => null) ) ) );

        $data= array();
        $data['message_id'] = $result['message_id'];

        $json = $this->testAction("/v2/message", array('data' => $data, 'method'=>'POST' ));
        	
        $result = json_decode($json, true);

        $this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'message_title'), hash::get($expected, 'message_title'));
        $this->assertEqual(hash::get((array)$result, 'message_body'), hash::get($expected, 'message_body'));
        $this->assertEqual(hash::get((array)$result, 'send_time'), hash::get($expected, 'send_time'));
        $this->assertEqual(hash::get((array)$result, 'send_result.code'),  hash::get($expected, 'send_result.code'));
        $this->assertEqual(hash::get((array)$result, 'send_result.msg'),  hash::get($expected, 'send_result.msg'));
        $this->assertEqual(hash::get((array)$result, 'send_result.device_result_list'),  hash::get($expected, 'send_result.device_result_list'));
        
        return $result;

    }
    
    /**
     * test destroy message(メッセージ削除API)
     * 
     * @depends testPushToken
     */
    public function testDestroyMessage($result) {
    
    	if($result ['process_result'] == 1) {
    		$send_result_code = $result ['send_result'] ['code'];
    	} else {
    		$send_result_code = $result ['error'] ['code'];
    	}
    	if($send_result_code === NULL || $send_result_code == '' || $send_result_code == '1001' || $send_result_code == '2001') {
    		$expected = Array ( 'process_result' => 1 ,'delete_type' => 'unsent' );
    	} else {
    		$expected = Array ( 'process_result' => 1 ,'delete_type' => 'sent' );
    	}
    	
    	$data= array();
    	$data['message_id'] = $result['message_id'];
    
    	$json = $this->testAction("/v2/message/destroy", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);

    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'delete_type'), hash::get($expected, 'delete_type'));
    
    	return $result;
    
    }
    
    /**
     * test get message error(メッセージ送信情報取得API)
     */
    public function testGetMessageException() {
    
    	$expected = Array ( 'process_result' => 0,
    			            'error' => Array ( 'code' => '4004',
    					                       'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['message_id'] = 99999;
    
    	$json = $this->testAction("/v2/message", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test destroy message error(メッセージ削除API)
     */
    public function testDestroyMessageException() {
    
    	$expected = Array ( 'process_result' => 0,
    			            'error' => Array ( 'code' => '4004',
    					                       'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['message_id'] = 99999;
    
    	$json = $this->testAction("/v2/message/destroy", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
}