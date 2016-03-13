<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]統計管理コントローラークラス
 *
 * メッセージ管理画面の表示をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Statistic
 * @package Controller
 * @property StatisticAdmin $StatisticAdmin
 * @property mixed params
 */
class StatisticController extends AdminController
{
    public $name = 'Statistic';

    public $uses = array(
        'StatisticAdmin'
    );

    public $helpers = array(
        'Form',
        'Xform.Xformjp',
        'Cakeplus.Formhidden'
    );

    public $components = array('Security');

    /**
     * [function]共通チェック処理用のラッパー関数
     *
     * セキュリティ関連の設定を行う。
     *
     * @access public
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Security->csrfUseOnce = false;
        $this->Security->csrfExpires = '+1 hour';
        $this->Security->validatePost = false;
        // $this->Auth->allow();
    }


    /**
     * [CMS]統計情報画面用の関数
     *
     * 統計情報画面を表示する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->set('title_for_layout', '統計情報');

        $serviceId = CakeSession::read('service_id');
        $from = date('Y-m-d', strtotime('-1 week'));
        $to = date('Y-m-d', strtotime('-1 day'));

        $datum = $this->StatisticAdmin->getDailyDatum($serviceId, $from, $to);

        $this->request->data['Statistic']['from_year'] = date('Y', strtotime($from));
        $this->request->data['Statistic']['from_month'] = date('m', strtotime($from));
        $this->request->data['Statistic']['from_day'] = date('d', strtotime($from));
        $this->request->data['Statistic']['to_year'] = date('Y', strtotime($to));
        $this->request->data['Statistic']['to_month'] = date('m', strtotime($to));
        $this->request->data['Statistic']['to_day'] = date('d', strtotime($to));

        $this->set('datum', $datum);

    }

    /**
     * [CMS]統計情報画面用のajax関数
     *
     * 統計情報を取得する。
     *
     * @access public
     */
    public function cms_async_daily()
    {
        // 今回はJSONのみを返すためViewのレンダーを無効化
        $this->autoRender = false;
        // Ajax以外の通信の場合
        if(!$this->request->is('ajax')) {
            throw new BadRequestException();
        }

        $fYear = $this->request->query['from_year'];
        $fMonth = $this->request->query['from_month'];
        $fDay = $this->request->query['from_day'];
        $strFrom = $fYear . '-' . $fMonth . '-' . $fDay;

        $tYear = $this->request->query['to_year'];
        $tMonth = $this->request->query['to_month'];
        $tDay = $this->request->query['to_day'];
        $strTo = $tYear . '-' . $tMonth . '-' . $tDay;

        $errMsgList = $this->StatisticAdmin->inputDailyCheck($strFrom, $strTo);

        if (!empty($errMsgList)) { // 失敗
            $result = 0;
            $datum = array();
            $err_msg = $errMsgList[0];
            // JSON形式で返却。errorが定義されていない場合はstatusとresultの配列になる。
            return json_encode(compact('result', 'datum', 'err_msg'));
        }


        $serviceId = CakeSession::read('service_id');
        $from = date('Y-m-d', strtotime($strFrom));
        $to = date('Y-m-d', strtotime($strTo));

        $datum = $this->StatisticAdmin->getDailyDatum($serviceId, $from, $to);

        if (empty($datum)) { // 失敗
            $result = 0;
            $err_msg = 'データがありません。';
        } else {
            $result = 1; // 成功
        }

        // JSON形式で返却。errorが定義されていない場合はstatusとresultの配列になる。
        return json_encode(compact('result', 'datum', 'err_msg'));
    }


}