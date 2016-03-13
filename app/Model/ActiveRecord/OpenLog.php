<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]開封ログテーブル用のモデルクラス
 *
 * アクティブレコードなモデルクラス
 * 原則コントローラーから直接呼び出さない。
 *
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category ActiveRecord
 * @package Model
 */
class OpenLog extends ApiModel {
    public $name = "OpenLog";
    public $useTable = 'open_logs';
    // public $primaryKey = 'read_id';
    
    /**
     * [function]開封数取得用のSQL関数
     *
     * @access public
     * @param string $service_id サービスID
     * @param string $statistic_date 統計日
     * @return array $openLogs SQL実行結果
     */
    public function getOpenLogs($service_id, $statistic_date) {
    
    	$statistic_date_from = date('Y-m-d 00:00:00', strtotime($statistic_date));
    
    	$statistic_date_to = date('Y-m-d 23:59:59', strtotime($statistic_date));
    
    	// SQL文
    	$sql = "SELECT
    			    CASE WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '05:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='00:00:00'THEN 'count_open_06'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '11:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='06:00:00'THEN 'count_open_12'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '17:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='12:00:00'THEN 'count_open_18'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '23:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='18:00:00'THEN 'count_open_24'
    			    END AS count_open_key,
					COUNT(id) count_open
				FROM open_logs
				WHERE
					service_id = ". $service_id ."
				AND del_flag = 0
				AND created IS NOT NULL
				AND (created BETWEEN '". $statistic_date_from ."' AND '". $statistic_date_to ."' )
				GROUP BY 
					CASE WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '05:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='00:00:00'THEN 'count_open_06'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '11:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='06:00:00'THEN 'count_open_12'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '17:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='12:00:00'THEN 'count_open_18'
                         WHEN DATE_FORMAT(created, '%H:%i:%S')  <= '23:59:59' AND DATE_FORMAT(created, '%H:%i:%S') >='18:00:00'THEN 'count_open_24'
    			    END		
						";
    
    	// SQL実行
    	$openLogs = $this->query ( $sql );
    
    	return $openLogs;
    }
    
}
