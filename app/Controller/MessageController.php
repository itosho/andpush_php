<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]メッセージ管理コントローラークラス
 *
 * メッセージ管理画面の表示をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Message
 * @package Controller
 * @property MessageAdmin $MessageAdmin
 * @property ServiceAdmin $ServiceAdmin
 * @property mixed params
 */
class MessageController extends AdminController
{
    public $name = 'Message';

    public $uses = array(
        'MessageAdmin',
        'ServiceAdmin',
        'DeviceUserAdmin'
    );

    public $helpers = array(
        'Form',
        'Xform.Xformjp',
        'Cakeplus.Formhidden'
    );

    public $components = array('Security');

    public $paginate = array(
        'limit' => 20,
        'order' => array('id' => 'DESC')
    );

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

        if ($this->Auth->loggedIn()) {
            $serviceItem = $this->ServiceAdmin->readItem(CakeSession::read('service_id'));
            if (empty($serviceItem['android_api_key']) && empty($serviceItem['ios_cert_file'])) {
                $this->Session->setFlash("APNS証明書及びGCM APIキーが登録されていません。<br />メッセージ送信にはいずれかの登録が必要です。サービス管理から登録をお願いいたします。",
                    'default', array(
                        'class' => 'alert alert-warning alert-formresult'
                    ));
            }
        }
    }


    /**
     * [CMS]メッセージ一覧画面用の関数
     *
     * メッセージ一覧画面を表示する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->set('title_for_layout', 'Pushメッセージ一覧');

        // メッセージ一覧情報取得（ページネーション利用）
        $conditions = array('service_id' => CakeSession::read('service_id'), 'del_flag' => 0);
        $list = $this->paginate($conditions);

        // モデル名除去
        $list = Hash::extract($list, '{n}.MessageAdmin');

        $this->set('list', $list);
    }

    /**
     * [CMS]メッセージ詳細画面用の関数
     *
     * メッセージ詳細画面を表示する。
     *
     * @access public
     * @param string $id メッセージID
     */
    public function cms_detail($id = null)
    {
        $this->set('title_for_layout', 'Pushメッセージ詳細');

        // URLにIDがないor数字以外の場合は400エラー
        if ($id === null || !is_numeric($id)) {
            throw new BadRequestException;
        }

        $item = $this->MessageAdmin->readItem($this->Session->read('service_id'), $id);

        // IDが存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        $this->set('item', $item);
    }

    /**
     * [CMS]メッセージ登録画面の関数
     *
     * GET：メッセージ登録画面を表示する。
     * POST1：入力値をチェックを行う。チェック通過後、確認画面を表示する。
     * POST2：メッセージ確認画面から登録画面へ戻る。
     * POST3：メッセージ登録処理を行う。登録後、一覧画面へ遷移する。
     *
     * @access public
     */
    public function cms_create()
    {
        $this->set('title_for_layout', 'Pushメッセージ登録');

        $serviceId = $this->Session->read('service_id');
        $propertyLabels = $this->DeviceUserAdmin->readPropertyLabels($serviceId);
        $this->set('propertyLabels', $propertyLabels);

        if ($this->request->is('post')) { // 登録時
            $fixDevices = $this->Session->read('fix_segment_devices');
            if (count($fixDevices) > 0 && !empty($fixDevices)) {
                $deviceCount = count($fixDevices);
            } else {
                $deviceCount = $this->MessageAdmin->getDeviceCount($serviceId);
            }
            $this->set('deviceCount', $deviceCount);

            if ($this->request->data['mode'] == '1') {

                $errMsgList = $this->MessageAdmin->inputCheck($this->request->data);

                if ($errMsgList) {
                    $this->set('errMsgList', $errMsgList);
                    return;
                }
                $this->params['xformHelperConfirmFlag'] = true; // 確認画面
                return;
            }

            if ($this->request->data['back'] == '1') {
                $this->params['xformHelperConfirmFlag'] = false; // 登録画面
                return;
            }

            $serviceId = CakeSession::read('service_id');

            // Push対象端末情報抽出
            $fixDevices = $this->Session->read('fix_segment_devices');
            if (count($fixDevices) > 0 && !empty($fixDevices)) {
                $deviceList = $fixDevices;
            } else {
                $deviceConditions = array();
                $deviceList = $this->MessageAdmin->getDeviceList($serviceId, $deviceConditions);
            }

            // Pushメッセージ登録
            $pushContents = array();
            if ($this->request->data['Message']['message_title'] != '') {
                $pushContents['message_title'] = $this->request->data['Message']['message_title'];
            } else {
                $pushContents['message_title'] = CakeSession::read('service_name');
            }
            $pushContents['message_body'] = $this->request->data['Message']['message_body'];

            if ($this->request->data['Message']['send_type'] == '1') {
                $year = $this->request->data['Message']['send_time']['year'];
                $month = $this->request->data['Message']['send_time']['month'];
                $day = $this->request->data['Message']['send_time']['day'];
                $hour = $this->request->data['Message']['send_time']['hour'];
                $min = $this->request->data['Message']['send_time']['min'];
                $pushContents['send_time'] = "$year-$month-$day $hour:$min:00";
            }

            $messageId = $this->MessageAdmin->registMessage($serviceId, $pushContents, $deviceList);

            if ($messageId < 1) {
                $this->Session->setFlash("Pushメッセージの登録に失敗しました。", 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));
                $this->redirect(array(
                    'action' => 'cms_index'
                ));
            }

            if ($messageId > 0 && $this->request->data['Message']['send_type'] == '1') {
                $this->Session->setFlash("Pushメッセージを登録しました。", 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
                $this->redirect(array(
                    'action' => 'cms_index'
                ));
            }

            $services = array();
            $services['id'] = $serviceId;
            $services['ios_cert_file'] = CakeSession::read('ios_cert_file');
            $services['android_api_key'] = CakeSession::read('android_api_key');

            $pushResult = $this->MessageAdmin->pushMessage($services, $messageId, $pushContents);


            if ($pushResult['process_result'] == RESULT_SUCCESS) {
                $this->Session->setFlash($pushResult['send_result']['msg'], 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
            } else {
                $this->Session->setFlash($pushResult['send_result']['msg'], 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));

            }

            $this->redirect(array(
                'action' => 'cms_index'
            ));

        } else { // 初期表示時
            $this->Session->delete('tmp_segment_devices');
            $this->Session->delete('fix_segment_devices');
            $deviceCount = $this->MessageAdmin->getDeviceCount($serviceId);
            $this->set('deviceCount', $deviceCount);
        }
    }


    /**
     * [CMS]メッセージ更新画面の関数
     *
     * GET：メッセージ更新画面を表示する。
     * POST1：入力値をチェックを行う。チェック通過後、確認画面を表示する。
     * POST2：メッセージ更新確認画面から更新画面へ戻る。
     * POST3：メッセージ更新処理を行う。更新後、一覧画面へ遷移する。
     *
     * @access public
     * @param string $id メッセージID
     */
    public function cms_update($id = null)
    {
        $this->set('title_for_layout', 'Pushメッセージ更新');

        // URLにIDがないor数字以外の場合は400エラー
        if ($id === null || !is_numeric($id)) {
            throw new BadRequestException;
        }

        $item = $this->MessageAdmin->readItem($this->Session->read('service_id'), $id);

        // IDが存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        if ($this->request->is('post')) { // 登録時

            if ($this->request->data['mode'] == '1') {

                $errMsgList = $this->MessageAdmin->inputCheck($this->request->data);

                if ($errMsgList) {
                    $this->set('errMsgList', $errMsgList);
                    return;
                }
                $this->params['xformHelperConfirmFlag'] = true; // 確認画面
                return;
            }

            if ($this->request->data['back'] == '1') {
                $this->params['xformHelperConfirmFlag'] = false; // 登録画面
                return;
            }

            $serviceId = CakeSession::read('service_id');

            // Push対象端末情報抽出
            $deviceConditions = array(); // @TODO
            $deviceList = $this->MessageAdmin->getDeviceList($serviceId, $deviceConditions);

            // Pushメッセージ登録
            $pushContents = array();
            if ($this->request->data['Message']['message_title'] != '') {
                $pushContents['message_title'] = $this->request->data['Message']['message_title'];
            } else {
                $pushContents['message_title'] = CakeSession::read('service_name');
            }
            $pushContents['message_body'] = $this->request->data['Message']['message_body'];


            if ($this->request->data['Message']['send_type'] == '1') {
                $year = $this->request->data['Message']['send_time']['year'];
                $month = $this->request->data['Message']['send_time']['month'];
                $day = $this->request->data['Message']['send_time']['day'];
                $hour = $this->request->data['Message']['send_time']['hour'];
                $min = $this->request->data['Message']['send_time']['min'];
                $pushContents['send_time'] = "$year-$month-$day $hour:$min:00";
            }

            $messageId = $this->MessageAdmin->updateMessage($serviceId, $pushContents, $deviceList, $id);

            if ($messageId < 1) {
                $this->Session->setFlash("Pushメッセージの登録に失敗しました。", 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));
                $this->redirect(array(
                    'action' => 'cms_index'
                ));
            }

            if ($messageId > 0 && $this->request->data['Message']['send_type'] == '1') {
                $this->Session->setFlash("Pushメッセージを登録しました。", 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
                $this->redirect(array(
                    'action' => 'cms_index'
                ));
            }

            $services = array();
            $services['id'] = $serviceId;
            $services['ios_cert_path'] = CakeSession::read('ios_cert_path');
            $services['android_api_key'] = CakeSession::read('android_api_key');

            $pushResult = $this->MessageAdmin->pushMessage($services, $messageId, $pushContents);


            if ($pushResult['process_result'] == RESULT_SUCCESS) {
                $this->Session->setFlash($pushResult['send_result']['msg'], 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
            } else {
                $this->Session->setFlash($pushResult['send_result']['msg'], 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));

            }

            $this->redirect(array(
                'action' => 'cms_index'
            ));

        } else { // 初期表示時

            $this->request->data['Message']['message_title'] = $item['message_title'];
            $this->request->data['Message']['message_body'] = $item['message_body'];
            $this->request->data['Message']['send_type'] = "1";

            $sendTime = strtotime($item['send_time']);
            $this->request->data['Message']['send_time']['year'] = date('Y', $sendTime);
            $this->request->data['Message']['send_time']['month'] = date('m', $sendTime);
            $this->request->data['Message']['send_time']['day'] = date('d', $sendTime);
            $this->request->data['Message']['send_time']['hour'] = date('H', $sendTime);
            $this->request->data['Message']['send_time']['min'] = date('i', $sendTime);
        }
    }

    /**
     * [CMS]メッセージ削除画面の関数
     *
     * GET：メッセージ削除画面を表示する。
     * POST：メッセージ削除処理を行う。削除後、一覧画面へ遷移する。
     *
     * @access public
     * @param string $id メッセージID
     */
    public function cms_destroy($id = null)
    {
        $this->set('title_for_layout', 'Pushメッセージ削除');

        // URLにIDがないor数字以外の場合は400エラー
        if ($id === null || !is_numeric($id)) {
            throw new BadRequestException;
        }

        $item = $this->MessageAdmin->readItem($this->Session->read('service_id'), $id);

        // IDが存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        if ($this->request->is('post')) {//削除処理

            $result = $this->MessageAdmin->deleteItem($this->Session->read('service_code'), $id);

            if ($result) {
                $this->Session->setFlash('Pushメッセージを削除しました。', 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
            } else {
                $this->Session->setFlash("Pushメッセージの削除に失敗しました。", 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));

            }

            $this->redirect(array(
                'action' => 'cms_index'
            ));


        } else {

            $this->set('item', $item);
        }
    }

    /**
     * [CMS]セグメント配信検索用のajax関数
     *
     * 対象端末台数を取得する。
     *
     * @access public
     */
    public function cms_async_search_segment()
    {
        // 今回はJSONのみを返すためViewのレンダーを無効化
        $this->autoRender = false;
        // Ajax以外の通信の場合
        if (!$this->request->is('ajax')) {
            throw new BadRequestException();
        }

        // バリデーション
        $errMsgList = $this->MessageAdmin->inputSearchSegmentCheck($this->request->data['Message']);

        if (!empty($errMsgList)) {
            $result = 0;
            $error_msg_list = $errMsgList;
            return json_encode(compact('result', 'count', 'error_msg_list'));
        }

        $deviceList = $this->MessageAdmin->getDeviceListByProperties(
            $this->Session->read('service_id'), $this->request->data['Message']);

        $result = 1;
        $count = count($deviceList);

        if ($count > 0) {
            $this->Session->write('tmp_segment_devices', $deviceList);
        } else {
            $this->Session->delete('tmp_segment_devices');
        }

        return json_encode(compact('result', 'count', 'error_msg_list'));
    }

    /**
     * [CMS]セグメント配信設定用のajax関数
     *
     * 対象端末台数を取得する。
     *
     * @access public
     */
    public function cms_async_set_segment()
    {
        $this->autoRender = false;
        // Ajax以外の通信の場合
        if (!$this->request->is('ajax')) {
            throw new BadRequestException();
        }

        $deviceList = $this->Session->read('tmp_segment_devices');
        $count = count($deviceList);

        if ($count > 0) {
            $result = 1;
            $this->Session->write('fix_segment_devices', $deviceList);
            $this->Session->delete('tmp_segment_devices');
        } else {
            $result = 0;
            $error_msg = '対象端末が0件です。';
        }

        return json_encode(compact('result', 'count', 'error_msg'));
    }
}