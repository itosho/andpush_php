<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]属性情報管理コントローラークラス
 *
 * 属性情報管理画面の表示をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Property
 * @package Controller
 * @property PropertyAdmin $PropertyAdmin
 * @property mixed params
 */
class PropertyController extends AdminController
{
    public $name = 'Property';

    public $uses = array(
        'PropertyAdmin'
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
     * [CMS]属性情報更新画面の関数
     *
     * GET：属性情報更新画面を表示する。
     * POST：属性情報更新処理を行う。更新後、一覧画面へ遷移する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->set('title_for_layout', '属性情報更新');

        $item = $this->PropertyAdmin->readItem($this->Session->read('service_id'));

        if ($this->request->is('post')) { // 登録時

            $errMsgList = $this->PropertyAdmin->inputCheck($this->request->data);

            if ($errMsgList) {
                $this->set('errMsgList', $errMsgList);
                return;
            }

            $result = $this->PropertyAdmin->updateProperty($this->Session->read('service_id'), $this->request->data['Property']);

            if ($result) {
                $this->Session->setFlash("属性情報を更新しました。", 'default', array(
                    'class' => 'alert alert-success alert-formresult'
                ));
            } else {
                $this->Session->setFlash("属性情報の更新に失敗しました。", 'default', array(
                    'class' => 'alert alert-danger alert-formresult'
                ));
            }

            $this->redirect(array(
                'action' => 'cms_index'
            ));

        } else { // 初期表示時

            $i = 0;
            foreach ($item as $property) {
                $this->request->data['Property'][$i]['key_name'] = $property['key_name'];
                $this->request->data['Property'][$i]['label_name'] = $property['label_name'];
                $i++;
            }
        }
    }
}