<?php
App::uses ( 'Service', 'Model/ActiveRecord' );
App::uses ( 'OpenLog', 'Model/ActiveRecord' );
App::uses ( 'MessageDevice', 'Model/ActiveRecord' );

/**
 * 統計情報作成(日次)Shell
 * 
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Shell
 * @package app.Console.Command
 * 
 * @property Statistic $Statistic
 * @property MailTask $Mail
 *
 */
class StatisticShell extends AppShell
{

    /**
     * 統計日
     *
     * @var $statistic_date
     */
    private $statistic_date;

    /**
     * 初期化
     */
    private function _init()
    {
    	// 処理日取得
        $param_date = @$this->args[0];
        
        // 統計日設定
        if ($param_date) {
            $this->statistic_date = date('Y-m-d', strtotime($param_date . ' -1 day'));
        } else {
            $this->statistic_date = date('Y-m-d', strtotime('-1 day'));
        }

    }

    /**
     * 使用するModels
     *
     * @var users
     */
    public $uses = array(
        'Statistic'
    );
    
    /**
     * 使用するtasks
     *
     * @var tasks
     */
    public $tasks = array(
    		'Mail'
    );

    /**
     * 日次集計
     */
    public function daily()
    {
        // 初期化
        $this->_init();
        
        // サービスモデルインスタンス化
        $serviceModel = new Service ();

        // 対象サービス取得
        $services = $serviceModel->getServicesInfo(); 
        
        if(!$services) {
        	$this->log ( '【andpush_batch】[統計情報作成(日次)バッチ_error]対象サービス情報はありません。', LOG_SHELL );
        	exit;
        }
        
        // 対象サービス件数
        $servicesCount = count($services);
        $this->log ( "【andpush_batch】[統計情報作成(日次)バッチ_start]実行対象サービス件数：{$servicesCount}", LOG_SHELL );
        
        // メッセージ端末モデルインスタンス化
        $messageDeviceModel = new MessageDevice ();
        
        // 開封ログモデルインスタンス化
        $openLogModel = new OpenLog ();
        
        // 成功件数
        $count_ok = 0;
        // 失敗件数
        $count_ng = 0;
        
        foreach ($services as $service) {
            
        	try {
        		
	            // サービスID
	            $service_id = $service['services']['id'];
	            $this->log ( "【andpush_batch】[統計情報作成(日次)バッチ_info]実行対象サービスID：{$service_id}", LOG_SHELL );
	            
	            // 統計結果配列作成
	            $daily = array();
	            // 統計日
	            $daily['statistic_date'] = $this->statistic_date;
	            // サービスID
	            $daily['service_id'] = $service_id;
	             
	            // 登録端末数取得
	            $countDevice = $this->Statistic->getCountDevice($service_id, $this->statistic_date);
	            // 登録端末数
	            $daily['count_device'] = $countDevice;
	
	            // メッセージ送信回数＆送信端末台数情報取得
	            $messagesDevices = $messageDeviceModel->getMessagesDevice($service_id, $this->statistic_date);

	            // 取得結果をログに出す（テストのため）
	            //$this->log ( $messagesDevices, LOG_SHELL );

	            // Push送信回数初期化（0時-6時）
	            $daily['count_push_message_06'] = 0;
	            // Push送信回数初期化（6時-12時）
	            $daily['count_push_message_12'] = 0;
	            // Push送信回数初期化（12時-18時）
	            $daily['count_push_message_18'] = 0;
	            // Push送信回数初期化（18時-24時）
	            $daily['count_push_message_24'] = 0;
	            
	            // Push送信端末台数初期化（0時-6時）
	            $daily['count_push_device_06'] = 0;
	            // Push送信端末台数初期化（6時-12時）
	            $daily['count_push_device_12'] = 0;
	            // Push送信端末台数初期化（12時-18時）
	            $daily['count_push_device_18'] = 0;
	            // Push送信端末台数初期化（18時-24時）
	            $daily['count_push_device_24'] = 0;
	            
	            /** Push送信端末台数、Push送信回数格納  **/
	            foreach ($messagesDevices as $messagesDevice) {
	                
	            	// key
	            	$count_push_message_key = $messagesDevice[0]['count_push_message_key'];
	            	$count_push_device_key = $messagesDevice[0]['count_push_device_key'];
	            	
	            	// 集計したPush送信回数を格納する
	            	$daily[$count_push_message_key] = $messagesDevice[0]['count_push_message'];
	            	
	            	// 集計したPush送信端末台数を格納する
	            	$daily[$count_push_device_key] = $messagesDevice[0]['count_push_device'];
	            	
	            }
            	
            	// 開封数情報取得
            	$openLogs = $openLogModel->getOpenLogs($service_id, $this->statistic_date);
            	
            	// 取得結果をログに出す（テストのため）
            	//$this->log ( $openLogs, LOG_SHELL );

            	// 開封数初期化（0時-6時）
            	$daily['count_open_06'] = 0;
            	// 開封数初期化（6時-12時）
            	$daily['count_open_12'] = 0;
            	// 開封数初期化（12時-18時）
            	$daily['count_open_18'] = 0;
            	// 開封数初期化（18時-24時）
            	$daily['count_open_24'] = 0;
            	 
            	/** 開封数格納 **/
            	foreach ($openLogs as $openLog) {
            		// key
            		$count_open_key = $openLog[0]['count_open_key'];
            		// 集計した開封数を格納する
            		$daily[$count_open_key] = $openLog[0]['count_open'];

            	}
            	
            	// システムタイム
            	$systime = date('Y-m-d H:i:s');
            	
            	$daily['created'] = $systime;
            	$daily['modified'] = $systime;
            	$daily['del_flag'] = 0;

            	// 統計結果配列をログに出す（テストのため）
            	//$this->log ( $daily, LOG_SHELL );
            	
                // 統計情報登録
                $saveresult = $this->Statistic->saveStatisticDaily($daily);
                if($saveresult === false) {
                	$count_ng += 1;
                	 
                	$this->log("【andpush_batch】[統計情報作成(日次)バッチ_error]統計情報登録が失敗しました。", LOG_SHELL);
                	
                	// メールを送信
                	$this->Mail->send("【andpush_batch】統計情報作成(日次)バッチ障害のお知らせ", "下記のエラーを発生しました（service_id={$service_id}）"
                	. "\n\r統計情報登録が失敗しました。");
                	 
                	continue;
                	
                } else {
                	$count_ok += 1;
                }
                
            } catch (Exception $ex) {
            	
            	$count_ng += 1;
            	
                $this->log($ex->getMessage(), LOG_SHELL);
            	$this->log($ex->getTraceAsString(), LOG_SHELL);
            
	            // メールを送信
	            $this->Mail->send("【andpush_batch】統計情報作成(日次)バッチ障害のお知らせ", "下記のエラーを発生しました（service_id={$service_id}）"
	                . "\n\r" . $ex->getMessage() 
	                . "\n\r" . $ex->getTraceAsString());
	                
                continue;
            }
        }
        
        $this->log ( "【andpush_batch】[統計情報作成(日次)バッチ_end]実行対象サービスの成功件数：{$count_ok} / 失敗件数：{$count_ng} / 処理件数：{$servicesCount}", LOG_SHELL );
        
    }
    
}
