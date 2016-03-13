<?php
class UserApiControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
			'app.service',
			'app.device',
			'app.message_device'
	);
	
    public function setUp() {
        parent::setUp();
        
        $_SERVER['HTTP_X_PUSH_CODE'] = "testcode01";
        $_SERVER['HTTP_X_PUSH_KEY'] = "testkey01";
    }

    /**
     * test user property(ユーザー属性情報登録API)
     * 
     */
    public function testUserProperty() {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'create'
    	);
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    	$data['properties']['sex'] = "男";
    	$data['properties']['age'] = 28;
    	$data['properties']['address'] = "東京都";
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
    
    /**
     * test user property again (ユーザー属性情報登録API)
     *
     * @depends testUserProperty
     */
    public function testUserPropertyAgain($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'update'
    	);
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    	$data['properties']['sex'] = "男";
    	$data['properties']['age'] = 28;
    	$data['properties']['address'] = "東京都秋葉原";
    	$data['properties']['tel'] = "08088888888";
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'save_type'), hash::get($expected, 'save_type'));
    
    	return $result;
    }
        
    /**
     * test entry device (端末情報登録API)
     * 
     * 端末情報登録APIを利用して、端末を登録する
     * 
     * @depends testUserPropertyAgain
     */
    public function testEntryDevice($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'create'
    	);
    
    	$data= array();
    	$data['push_target'] = "ios";
    	$data['push_token'] = "testtoken03";
    	$data['user_id'] = "testuser11";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    
    	return $result;
    }
    
    /**
     * test entry device (端末情報登録API)
     *
     * 端末情報登録APIを利用して、同じユーザは二つ端末を登録する
     * @depends testEntryDevice
     */
    public function testEntryDeviceSameUser($result) {
    
    	$expected = Array ( 'process_result' => 1,
    			'save_type' => 'update'
    	);
    
    	$data= array();
    	$data['push_target'] = "android";
    	$data['push_token'] = "testtoken05";
    	$data['user_id'] = "testuser11";
    
    	$json = $this->testAction("/v2/device/entry", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    
    	return $result;
    }
    
    /**
     * test get user (ユーザー情報取得API)
     * 
     * @depends testEntryDeviceSameUser
     */
    public function testGetUser($result) {

        $expected = Array ( 'process_result' => 1,
        		'properties' => Array ( 'sex' => '男',
        				'age' => 28,
        				'address' => '東京都秋葉原',
        				'tel' => '08088888888') ,
        		'device_list' => Array ( Array ('push_target' => 'ios',
        				'push_token' => 'testtoken03',
        				'device_status' => NULL),
        				Array ('push_target' => 'android',
        				'push_token' => 'testtoken05',
        				'device_status' => NULL))
        		
    			 );

        $data= array();
        $data['user_id'] = "testuser11";

        $json = $this->testAction("/v2/user", array('data' => $data, 'method'=>'POST' ));
        	
        $result = json_decode($json, true);

        $this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'properties'), hash::get($expected, 'properties'));
        $this->assertEqual(hash::get((array)$result, 'device_list'), hash::get($expected, 'device_list'));

        return $result;

    }

    /**
     * test destroy user (ユーザー情報削除API)
     * 
     * ユーザーIDが設定されている端末台数2件
     * @depends testGetUser
     */
    public function testDestroyUser() {
    
    	$expected = Array ( 'process_result' => 1, 
    			'device_count' => 2
    	);
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    
    	$json = $this->testAction("/v2/user/destroy", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'device_count'), hash::get($expected, 'device_count'));
    
    	return $result;
    }
    
    
    /**
     * test user property error(ユーザー属性情報登録API)
     *
     */
    public function testUserPropertyException01() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "";
    	$data['properties']['sex'] = "男";
    	$data['properties']['age'] = 28;
    	$data['properties']['address'] = "東京都";
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    }
      
    /**
     * test user property error(ユーザー属性情報登録API)
     *
     */
    public function testUserPropertyException02() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    	$data['properties'] = null;

    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test user property error(ユーザー属性情報登録API)
     *
     */
    public function testUserPropertyException03() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    	$data['properties']['sex'] = "男";
    	$data['properties']['age'] = 28;
    	$data['properties']['#$/abc123'] = "東京都";
    
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test user property error(ユーザー属性情報登録API)
     *
     */
    public function testUserPropertyException04() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "testuser11";
    	$data['properties']['a'] = "A";
    	$data['properties']['b'] = "B";
    	$data['properties']['c'] = "C";
    	$data['properties']['d'] = "D";
    	$data['properties']['e'] = "E";
    	$data['properties']['f'] = "F";
    	$data['properties']['g'] = "G";
    	$data['properties']['h'] = "H";
    	$data['properties']['i'] = "I";
    	$data['properties']['j'] = "J";
    	$data['properties']['k'] = "K";
    	$data['properties']['l'] = "L";
    	$data['properties']['m'] = "M";
    	$data['properties']['n'] = "N";
    	$data['properties']['o'] = "O";
    	$data['properties']['p'] = "P";
    	$data['properties']['q'] = "Q";
    	$data['properties']['r'] = "R";
    	$data['properties']['s'] = "S";
    	$data['properties']['t'] = "T";
    	$data['properties']['u'] = "U";
    
    
    	$json = $this->testAction("/v2/user/property", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
     
    /**
     * test get user error(ユーザー情報取得API)
     */
    public function testGetUserException01() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "";
    
    	$json = $this->testAction("/v2/user", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test get user error(ユーザー情報取得API)
     */
    public function testGetUserException02() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4004',
    					'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['user_id'] = "testuser99999";
    
    	$json = $this->testAction("/v2/user", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test destroy user error(ユーザー情報削除API)
     */
    public function testDestroyUserException01() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['user_id'] = "";
    
    	$json = $this->testAction("/v2/user/destroy", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }

    /**
     * test destroy user error(ユーザー情報削除API)
     */
    public function testDestroyUserException02() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4004',
    					'msg' => '指定されたデータが存在しません。'));
    
    	$data= array();
    	$data['user_id'] = "testuser99999";
    
    	$json = $this->testAction("/v2/user/destroy", array('data' => $data, 'method'=>'POST' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
}