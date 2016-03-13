<?php

class StatisticApiControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
			'app.service',
			'app.statistic_daily'
	);
	
    public function setUp() {
        parent::setUp();
        
        $_SERVER['HTTP_X_PUSH_CODE'] = "testcode01";
        $_SERVER['HTTP_X_PUSH_KEY'] = "testkey01";
    }
    
    public function tearDown() { 
    	parent::tearDown();
    }
    

    /**
     * test statistic/daily 
     */
    public function testDaily() {
    
    	$day = date('Y-m-d');
    	$expected = Array ( 'process_result' => 1,
    			'dailies'  => array( $day => array( 'count_device' => 100,
    		'count_push_message_06' => 100,
    		'count_push_device_06' => 110,
    		'count_open_06' => 120,
    		'count_push_message_12' => 130,
    		'count_push_device_12' => 140,
    		'count_open_12' => 150,
    		'count_push_message_18' => 160,
    		'count_push_device_18' => 170,
    		'count_open_18' => 180,
    		'count_push_message_24' => 190,
    		'count_push_device_24' => 200,
    		'count_open_24' => 210
    			               ) )
    	);
    
    	$data= array();
    
    	$json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'dailies'), hash::get($expected, 'dailies'));

    	return $result;
    
    }
    
    /**
     * test statistic/daily From
     */
    public function testDailyFrom() {

         $expected = Array ( 'process_result' => 1,
    			'dailies'  => array( '2015-07-01' => array( 'count_device' => 10,
            'count_push_message_06' => 10,
            'count_push_device_06' => 11,
            'count_open_06' => 12,
        	'count_push_message_12' => 13,
        	'count_push_device_12' => 14,
        	'count_open_12' => 15,
        	'count_push_message_18' => 16,
        	'count_push_device_18' => 17,
        	'count_open_18' => 18,
        	'count_push_message_24' => 19,
        	'count_push_device_24' => 20,
        	'count_open_24' => 21
    			               ) )
    	);
         
        $data= array();
        $data['from'] = "2015-07-01";

        $json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
        	
        $result = json_decode($json, true);

        $this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
        $this->assertEqual(hash::get((array)$result, 'dailies'), hash::get($expected, 'dailies'));

        return $result;

    }
    
    /**
     * test statistic/daily From
     */
    public function testDailyTo() {
    
    	$day = date('Y-m-d');
    	$expected = Array ( 'process_result' => 1,
    			'dailies'  => array( $day => array( 'count_device' => 100,
    		'count_push_message_06' => 100,
    		'count_push_device_06' => 110,
    		'count_open_06' => 120,
    		'count_push_message_12' => 130,
    		'count_push_device_12' => 140,
    		'count_open_12' => 150,
    		'count_push_message_18' => 160,
    		'count_push_device_18' => 170,
    		'count_open_18' => 180,
    		'count_push_message_24' => 190,
    		'count_push_device_24' => 200,
    		'count_open_24' => 210
    			               ) )
    	);
    
    	$data= array();
    	$data['to'] = $day;
    
    	$json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
    	 
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'dailies'), hash::get($expected, 'dailies'));
    	
    	return $result;
    
    }
    
    /**
     * test statistic/daily From To
     */
    public function testDailyFromTo() {
    
    	 $expected = Array ( 'process_result' => 1,
    			'dailies'  => array( '2015-07-01' => array( 'count_device' => 10,
            'count_push_message_06' => 10,
            'count_push_device_06' => 11,
            'count_open_06' => 12,
        	'count_push_message_12' => 13,
        	'count_push_device_12' => 14,
        	'count_open_12' => 15,
        	'count_push_message_18' => 16,
        	'count_push_device_18' => 17,
        	'count_open_18' => 18,
        	'count_push_message_24' => 19,
        	'count_push_device_24' => 20,
        	'count_open_24' => 21
    			               ) ,
    			'2015-07-02' => array( 'count_device' => 20,
            'count_push_message_06' => 20,
            'count_push_device_06' => 21,
            'count_open_06' => 22,
        	'count_push_message_12' => 23,
        	'count_push_device_12' => 24,
        	'count_open_12' => 25,
        	'count_push_message_18' => 26,
        	'count_push_device_18' => 27,
        	'count_open_18' => 28,
        	'count_push_message_24' => 29,
        	'count_push_device_24' => 30,
        	'count_open_24' => 31
    			               ) ,
    			'2015-07-03' => array( 'count_device' => 30,
    		'count_push_message_06' => 30,
    		'count_push_device_06' => 31,
    		'count_open_06' => 32,
    		'count_push_message_12' => 33,
    		'count_push_device_12' => 34,
    		'count_open_12' => 35,
    		'count_push_message_18' => 36,
    		'count_push_device_18' => 37,
    		'count_open_18' => 38,
    		'count_push_message_24' => 39,
    		'count_push_device_24' => 40,
    		'count_open_24' => 41
    			               ) )
    	);

    	$data= array();
    	$data['from'] = "2015-07-01";
    	$data['to'] = "2015-07-03";
    
    	$json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'dailies'), hash::get($expected, 'dailies'));
    	
    	return $result;
    
    }
    
    /**
     * test statistic/daily From > To error
     */
    public function testDailyFromToException01() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['from'] = "2015-07-03";
    	$data['to'] = "2015-07-02";
    
    	$json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
    /**
     * test statistic/daily To - From > 31  error
     */
    public function testDailyFromToException02() {
    
    	$expected = Array ( 'process_result' => 0,
    			'error' => Array ( 'code' => '4002',
    					'msg' => 'リクエストパラメーターが不正です。'));
    
    	$data= array();
    	$data['from'] = "2015-07-01";
    	$data['to'] = "2015-08-01";
    
    	$json = $this->testAction("/v2/statistic/daily", array('data' => $data, 'method'=>'GET' ));
    
    	$result = json_decode($json, true);
    
    	$this->assertEqual(hash::get((array)$result, 'process_result'), hash::get($expected, 'process_result'));
    	$this->assertEqual(hash::get((array)$result, 'error.code'),  hash::get($expected, 'error.code'));
    	$this->assertEqual(hash::get((array)$result, 'error.msg'),  hash::get($expected, 'error.msg'));
    
    	return $result;
    
    }
    
}