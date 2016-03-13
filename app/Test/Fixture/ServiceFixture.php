<?php
/**
 * ServiceFixture
 *
 */
class ServiceFixture extends CakeTestFixture {

    /**
     * Table name
     *
     * @var string
     */
    public $name = 'Service';

    /**
     * Table import
     *
     * @var array
     */
    public $import = array('table' => 'services');
    
    /**
     * Records
     *
     * @var array
     */
    public $records = array(
    	
        array(
        	'id'=> 1,
            'service_code' => 'testcode01',
            'auth_key' => '7b3e0023c83f70d7fc11e0e6a86028207340a5ef',
            'ios_cert_path' => 'test',
            'android_api_key' => 'test',
            'email' => 'test@test.jp',
            'name' => 'test',
        	'service_status' => 1,
            'created' => '2015-07-02 00:02:35',
            'modified' => '2015-07-02 00:02:35',
        	'del_flag' => 0
        )
    );
    
}
