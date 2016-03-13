<?php
class DeviceApiControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
			'app.service',
			'app.device',
			'app.open_log'
	
	);
	
    public function setUp() {
        parent::setUp();
        
        $_SERVER['HTTP_X_PUSH_CODE'] = "testcode01";
        $_SERVER['HTTP_X_PUSH_KEY'] = "testkey01";
    }

	/**
     * test entry device (端末情報登録API)
     */
    public function testEntryDevice() {

        $expected = Array ( 'process_result' => 1,
    			'save_type' => 'create'
    			 );

        $data= array();
        $data['push_target'] = "ios";
        $data['push_token'] = "testtoken03";
        $data['user_id'] = "testuser01";

        $json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
        	
        $result = json_decode($json, true);

        $this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));

        return $result;
    }
    
    
    /**
     * test entry device again (端末情報登録API)
     * 
     * @depends testEntryDevice
     */
    public function testEntryDeviceAgain($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'update'
    	);
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken03";
    	$data['user_id'] = "testuser01";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
    
    /**
     * test entry device (端末情報登録API)
     *
     * 同じユーザは二つ端末を登録する
     * @depends testEntryDevice
     */
    public function testEntryDeviceSameUser($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'update'
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken05";
    	$data['user_id'] = "testuser01";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    
    	return $result;
    }
    
    /**
     * test entry device(端末情報登録API)
     *
     * ユーザーIDを設定しない
     */
    public function testEntryDeviceNoUserId() {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'create'
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken04";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
    
    /**
     * test entry device again (端末情報登録API)
     *
     * ユーザーIDを設定しない
     * 
     * @depends testEntryDeviceNoUserId
     */
    public function testEntryDeviceNoUserIdAgain($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'update'
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken04";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
    
    /**
     * test Open (開封情報登録API)
     */
    public function testOpen() {
    
    	$expected = Array ( 'process_result' => 1
    	);
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken03";
    	$data['user_id'] = "testuser01";
    
    	$json = $this->testAction("/v2/device/open", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    
    	return $result;
    }
    
    /**
     * test user property(ユーザー属性情報登録API)
     * 
     * ユーザー属性情報登録APIを利用して、ユーザー属性情報を作成する
     * 
     * @depends testEntryDevice
     */
    public function testUserProperty($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'create'
    	);
    
    	$data= array();
    	$data['user_id'] = "testuser01";
    	$data['properties']['sex'] = "男";
    	$data['properties']['age'] = 28;
    	$data['properties']['address'] = "東京都";
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
//        $this->assertEqual(hash::get((array)$result, save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
    
    /**
     * test get device (端末情報取得API)
     * 
     * @depends testUserProperty
     */
    public function testGetDevice($result) {

        $expected = Array ( 'process_result' => 1,
    			'device_status' => null,
        		'user_id' => 'testuser01',
        		'properties' => Array ( 'sex' => '男',
        				'age' => 28,
        				'address' => '東京都') 
    			 );

        $data= array();
        $data['push_target'] = "ios";
        $data['push_token'] = "testtoken03";

        $json = $this->testAction("/v2/device", array('data' => $data, 'method'=>'POST' ));
        	
        $result = json_decode($json, true);

        $this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'device_status'), hash::get($expected, 'device_status'));
        $this->assertEqual(hash::get((array)$result, 'user_id'), hash::get($expected, 'user_id'));
        $this->assertEqual(hash::get((array)$result, 'properties.sex'), hash::get($expected, 'properties.sex'));
        $this->assertEqual(hash::get((array)$result, 'properties.age'), hash::get($expected, 'properties.age'));
        $this->assertEqual(hash::get((array)$result, 'properties.address'), hash::get($expected, 'properties.address'));

        return $result;

    }
    
    /**
     * test get device(端末情報取得API)
     *
     * ユーザー情報を設定しない
     * @depends testEntryDeviceNoUserId
     */
    public function testGetDeviceNoUserId($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'device_status' => null,
    			'user_id' => null,
    			'properties' => null
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken04";
    
    	$json = $this->testAction("/v2/device", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'device_status'), hash::get($expected, 'device_status'));
    	$this->assertEqual(hash::get((array)$result, 'user_id'), hash::get($expected, 'user_id'));
    	$this->assertEqual(hash::get((array)$result, 'properties'), hash::get($expected, 'properties'));
    
    	return $result;
    
    }
    
  
    /**
     * test clear device (端末情報クリアAPI)
     * 
     * 削除された端末に紐付いたユーザーIDが設定されている端末がある（台数1件以上）
     * @depends testGetDevice
     */
    public function testClearDevice() {
    
    	$expected = Array ( 'process_result' => 1, 
    			'user_device_count' => 1, 
    			'user_id' => 'testuser01'
    	);
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken03";
    
    	$json = $this->testAction("/v2/device/clear", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'user_device_count'), hash::get($expected, 'user_device_count'));
    	$this->assertEqual(hash::get((array)$result, 'user_id'), hash::get($expected, 'user_id'));
    
    	return $result;
    }
    
    /**
     * test clear device (端末情報クリアAPI)
     *
     * ユーザーIDが設定されていない
     * @depends testGetDevice
     */
    public function testClearDeviceNoUserId() {
    
    	$expected = Array ( 'process_result' => 1,
    			'user_device_count' => 0,
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken04";
    
    	$json = $this->testAction("/v2/device/clear", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'user_device_count'), hash::get($expected, 'user_device_count'));
    	$this->assertEqual(isset($result['user_id']), false);
    
    	return $result;
    }
    
    /**
     * test entry device error(端末情報登録API)
     */
    public function testEntryDeviceException() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['push_target'] = "iphone";
    	$data['push_token'] = "testtoken99999";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test test Open error(開封情報登録API)
     */
    public function testOpenException() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "";
    
    	$json = $this->testAction("/v2/device/open", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test get device error(端末情報取得API)
     */
    public function testGetDeviceException() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4004',
    					'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken99999";
    
    	$json = $this->testAction("/v2/device", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test clear device error(端末情報クリアAPI)
     */
    public function testClearDeviceException() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4004',
    					'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken99999";
    
    	$json = $this->testAction("/v2/device/clear", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
}