<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]日次統計テーブル用のモデルクラス
 *
 * アクティブレコードなモデルクラス
 * 原則コントローラーから直接呼び出さない。
 * 
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category ActiveRecord
 * @package Model
 */
class StatisticDaily extends ApiModel {
	public $name = "StatisticDaily";
	public $useTable = 'statistic_dailies';
	// public $primaryKey = 'statistic_id';
}
