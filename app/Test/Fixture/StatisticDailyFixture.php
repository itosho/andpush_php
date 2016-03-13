<?php
/**
 * StatisticDailyFixture
 *
 */
class StatisticDailyFixture extends CakeTestFixture {
	
    /**
     * Table name
     *
     * @var string
     */
    public $name = 'StatisticDaily';

    /**
     * Table import
     *
     * @var array
     */
    public $import = array('table' => 'statistic_dailies');
    
    /**
     * Records
     *
     * @var array
     */
    public $records = array(
    	
        array(
        	'id'=> 1,
            'statistic_date' => '2015-07-01',
            'service_id' => 1,
            'count_device' => 10,
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
        	'count_open_24' => 21,
            'created' => '2015-07-02 00:02:35',
            'modified' => '2015-07-02 00:02:35',
        	'del_flag' => 0
        ),
        array(
        	'id'=> 2,
            'statistic_date' => '2015-07-02',
            'service_id' => 1,
            'count_device' => 20,
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
        	'count_open_24' => 31,
            'created' => '2015-07-03 00:02:35',
            'modified' => '2015-07-03 00:02:35',
        	'del_flag' => 0
        ),
    	array(
    		'id'=> 3,
    		'statistic_date' => '2015-07-03',
    		'service_id' => 1,
    		'count_device' => 30,
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
    		'count_open_24' => 41,
    		'created' => '2015-07-04 00:02:35',
    		'modified' => '2015-07-04 00:02:35',
    		'del_flag' => 0
    	)
    );
    
    public function init() {
    	$this->records = array(
    			array(
    		'id'=> 4,
    		'statistic_date' => date('Y-m-d'),
    		'service_id' => 1,
    		'count_device' => 100,
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
    		'count_open_24' => 210,
    		'created' => '2015-07-11 00:02:35',
    		'modified' => '2015-07-11 00:02:35',
    		'del_flag' => 0
    	),
    	);
    	parent::init();
    }
}
