<?php
App::uses('AdminModel', 'Model');
App::uses('StatisticDaily', 'Model/ActiveRecord');

/**
 * [class]統計情報管理系モデルクラス
 *
 * CMSの統計情報管理に関する処理をまとめたモデルクラス。
 * StatisticControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Service
 * @package Model
 */
class StatisticAdmin extends AdminModel
{
    public $name = "StatisticAdmin";
    public $useTable = false;
    private $statisticDaily;

    /**
     * [function]コンストラクタ関数
     *
     * 統計情報管理で利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->statisticDaily = new StatisticDaily ();

    }

    /**
     * [function]入力項目チェック用関数
     *
     * @access public
     * @param string $strFrom
     *              開始日
     * @param string $toFrom
     *              終了日
     * @return array $errMsg エラーメッセージ　
     */
    public function inputDailyCheck($strFrom, $toFrom)
    {
        $errMsg = array();

        if (!Validation::date($strFrom)) {
            $errMsg[] = "対象期間（From）の形式が正しくありません。";
            return $errMsg;
        }
        if (!Validation::date($toFrom)) {
            $errMsg[] = "対象期間（To）の形式が正しくありません。";
            return $errMsg;
        }

        if (strtotime($strFrom) > strtotime($toFrom)) {
            $errMsg[] = "対象期間（From）が対象期間（To）を越えています。";
            return $errMsg;
        }
        if (strtotime(date('Y-m-d')) < strtotime($toFrom)) {
            $errMsg[] = "対象期間（To）は過去日を設定してください。";
            return $errMsg;
        }

        $seconddiff = abs(strtotime($toFrom) - strtotime($strFrom));
        $daydiff = $seconddiff / (60 * 60 * 24);
        if ($daydiff > 7) {
            $errMsg[] = "対象期間は1週間以内に設定してください。";
            return $errMsg;
        }

        return $errMsg;
    }


    /**
     * [function]日次統計情報取得関数
     *
     * サービス情報を取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @param string $from
     *             開始日
     * @param string $to
     *             終了日
     *
     * @return array $item サービスs情報
     */
    public function getDailyDatum($serviceId, $from, $to)
    {
        // 条件（サービス単位で）
        $conditions = array(
            'service_id' => $serviceId,
            'statistic_date >=' => $from,
            'statistic_date <=' => $to,
            'del_flag' => 0
        );
        // SQL実行
        $result = $this->statisticDaily->find('all', array(
            'conditions' => $conditions
        ));

        if (empty($result)) {
            return array();
        }

        // モデル名除去
        $datum = Hash::extract($result, '{n}.StatisticDaily');

        return $datum;
    }
}