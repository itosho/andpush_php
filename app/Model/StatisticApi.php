<?php
App::uses ( 'ApiModel', 'Model' );
App::uses ( 'StatisticDaily', 'Model/ActiveRecord' );

/**
 * [class]ユーザー系APIモデルクラス
 *
 * 統計情報に関するAPIをまとめたモデルクラス。
 * StatisticApiControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Statistic
 * @package Model
 */
class StatisticApi extends ApiModel {
    public $name = "StatisticApi";
    public $useTable = false;
    private $statisticDaily;
    
    /**
     * [function]コンストラクタ関数
     *
     * 統計情報系APIで利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct() {
        $this->statisticDaily = new StatisticDaily ();
    }

    /**
     * [function]パラメーターチェック用関数（統計情報取得API（日次））
     *
     * @access public
     * @param array $params
     *        	リクエストパラメーター（参照渡し）
     * @return boolean $result true=成功 / false=失敗
     */
    public function dailyParamCheck(&$params) {

    	// システム日付取得
    	$sysDate = date('Y-m-d');
    	
    	// 取得対象日の開始日
    	if(! isset($params['from']) || $params['from'] == '') {
    		$params['from'] = $sysDate;
    	}
    	
        // 取得対象日の終了日
        if (! isset($params['to']) || $params['to'] == '') {
            $params['to'] = $params['from'];
        }
        
        // 取得対象日の開始日
        if (! Validation::date($params['from'])) {
        	return false;
        }
        
        // 取得対象日の終了日
     	if (! Validation::date($params['to'])) {
        	return false;
        }
        
        // 日付を比較(開始日より終了日は古い)
        if (strtotime($params['from']) > strtotime($params['to'])) {
        	return false;
        }
        
        // 日付を比較(開始日から終了日まで31日超える)
        if (strtotime($params['to']) >= strtotime($params['from'] . " + 31 days")) {
        	return false;
        }
        
        return true;
    }

    /**
     * [function]日次ベース統計情報取得関数
     *
     * @access public
     * @param array $servicer
     *        	サービス情報
     * @param array $params
     *        	リクエストパラメーター
     * @return arrya $result 処理結果をまとめたもの
     */
    public function getDaily($servicer, $params) {

		// 日次ベースの統計情報取得
		$fields = array (
				'statistic_date',
				'count_device',
				'count_push_message_06',
				'count_push_device_06',
				'count_open_06',
				'count_push_message_12',
				'count_push_device_12',
				'count_open_12',
				'count_push_message_18',
				'count_push_device_18',
				'count_open_18',
				'count_push_message_24',
				'count_push_device_24',
				'count_open_24' 
		);
		$conditions = array (
				'service_id'  => $servicer['id'],
				'statistic_date >=' => $params ['from'],
				'statistic_date <=' => $params ['to'],
				'del_flag' => 0 
		);
		$dailyResultList = $this->statisticDaily->find ( 'all', array (
				'fields' => $fields,
				'conditions' => $conditions 
		) );
		
		$result = array ();

		if($dailyResultList) {
			
			$result['process_result'] = RESULT_SUCCESS;
			// 結果をまとめる
			$result ['dailies'] = array ();
			foreach ( $dailyResultList as $dailyResult ) {
				$statisticDate = $dailyResult ['StatisticDaily']['statistic_date'];
				unset($dailyResult ['StatisticDaily']['statistic_date']);
				$result ['dailies'] [$statisticDate] = $dailyResult ['StatisticDaily'];
			}
		} else {
			// ログ記載
			$this->logMsg ['title'] = '[統計情報取得API（日次）]統計情報存在チェックエラー';
			$this->logMsg ['line'] = '#' . __FILE__ . '(' . __LINE__ . ')';
			$this->logMsg ['body'] = $conditions;
			$this->log ( $this->logMsg, LOG_DEBUG );
			
			$result['process_result'] = RESULT_ERROR;
			$result['status_code'] = 404;
			$result['const_key'] = 'exist_error';
		}
		
		return $result;
    }
}