<?php
App::uses ( 'ApiModel', 'Model' );
App::uses ( 'Device', 'Model/ActiveRecord' );
App::uses ( 'StatisticDaily', 'Model/ActiveRecord' );

/**
 * [class]ユーザー系APIモデルクラス
 *
 * 統計情報に関するAPIをまとめたモデルクラス。
 * StatisticShellクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Statistic
 * @package Model
 */
class Statistic extends ApiModel {
    public $name = "Statistic";
    public $useTable = false;
    
    private $device;
    private $statisticDaily;
    
    /**
     * [function]コンストラクタ関数
     *
     * 統計情報系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct() {
    	$this->device = new Device ();
        $this->statisticDaily = new StatisticDaily ();
    }

    /**
     * [function]有効登録端末数取得
     *
     * @param string $service_id サービスID
     * @param string $statistic_date 統計日
     * @return int $countDevice 有効登録端末数
     */
    public function getCountDevice($service_id, $statistic_date)
    {
    
    	$statistic_date = date('Y-m-d 23:59:59', strtotime($statistic_date));
    	
    	$conditions = array(
    			'service_id' => $service_id,
    			'created <=' => $statistic_date,
    			'del_flag' => 0
    			
    	);
    
    	$countDevice = $this->device->find('count', array(
    			'conditions' => $conditions
    	));
    
    	return $countDevice;
    }
    
    /**
     * [function]日次ベースの統計情報登録の関数
     *
     * @access public
     * @param array $params
     *        	リクエストパラメーター
     * @return booleasn $result 処理結果
     */
    public function saveStatisticDaily($params) {

		$this->statisticDaily->clear();
        $this->statisticDaily->set($params);
        $result = $this->statisticDaily->save();
    
        return $result;
    }
}