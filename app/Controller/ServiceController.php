<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]サービス管理コントローラークラス
 *
 * サービス管理画面の表示をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Service
 * @package Controller
 * @property ServiceAdmin $ServiceAdmin
 * @property mixed params
 */
class ServiceController extends AdminController
{
    public $name = 'Service';

    public $uses = array(
        'ServiceAdmin'
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
     * [CMS]サービス情報詳細画面用の関数
     *
     * サービス詳細画面を表示する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->set('title_for_layout', 'サービス情報詳細');

        $item = $this->ServiceAdmin->readItem($this->Session->read('service_id'));

        // 情報が存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        $this->set('item', $item);
    }

    /**
     * [CMS]サービス更新画面の関数
     *
     * GET：サービス更新画面を表示する。
     * POST1：入力値をチェックを行う。チェック通過後、確認画面を表示する。
     * POST2：サービス更新確認画面から更新画面へ戻る。
     * POST3：サービス更新処理を行う。更新後、詳細画面へ遷移する。
     *
     * @access public
     */
    public function cms_update()
    {
        $this->set('title_for_layout', 'サービス情報更新');

        $item = $this->ServiceAdmin->readItem($this->Session->read('service_id'));

        // IDが存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        if ($this->request->is('post')) { // 登録時

            if ($this->request->data['mode'] == '1') {

                $errMsgList = $this->ServiceAdmin->inputCheck($this->request->data['Service']);

                if (!isset($errMsgList['certification']) && $this->request->data['Service']['certification']['name'] != '') {
                    $blobFile = base64_encode(file_get_contents($this->request->data['Service']['certification']['tmp_name']));
                    $this->request->data['Service']['certification_file'] = $blobFile;
                }
                if (isset($errMsgList['certification'])) {
                    $this->request->data['Service']['path_certification'] = null;
                }

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

            $result = $this->ServiceAdmin->updateService(CakeSession::read('service_id'), $this->request->data['Service']);

            if ($result) {
                // セッション情報更新
                $item = $this->ServiceAdmin->readItem($this->Session->read('service_id'));
                $this->Session->write('service_code', $item['service_code']);
                $this->Session->write('service_name', $item['service_name']);
                $this->Session->write('ios_cert_file', $item['ios_cert_file']);
                $this->Session->write('android_api_key', $item['android_api_key']);
                $this->Session->write('contact_email', $item['contact_email']);
                $this->Session->write('contact_name', $item['contact_name']);

                $this->Session->setFlash('サービス情報を更新しました。', 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
            } else {
                $this->Session->setFlash("サービス情報の更新に失敗しました。", 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));
            }

            $this->redirect(array(
                'action' => 'cms_index'
            ));

        } else { // 初期表示時

            $this->request->data['Service'] = $item;
        }
    }

    /**
     * [CMS] APNS証明書ダウンロード用関数
     *
     *
     * @access public
     */
    public function cms_download_cert() {

        $this->autoRender = false;

        $item = $this->ServiceAdmin->readItem($this->Session->read('service_id'));

        // 情報が存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        // $fileName = explode('/', $item['ios_cert_path']);
        // $fileName = $fileName[1];

        // $filePath = ROOT . DS . 'app/Vendor/ApnsPHP/Certificates' . DS . $item['ios_cert_path'];

        $this->response->header('Content-Type: application/octet-stream');
        $this->response->header("Content-Disposition: attachment; filename=andpush.pem");
        $this->response->body($item['ios_cert_file']);
        $this->response->send();
        exit;
    }

}