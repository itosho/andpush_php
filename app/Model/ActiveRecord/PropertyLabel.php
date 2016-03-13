<?php
App::uses ( 'ApiModel', 'Model' );
/**
 * [class]プロパティラベルテーブル用のモデルクラス
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
class PropertyLabel extends ApiModel {
    public $name = "PropertyLabel";
    public $useTable = 'property_labels';
}
