<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]端末ユーザー管理コントローラークラス
 *
 * 端末ユーザー管理画面の表示をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Device
 * @package Controller
 * @property DeviceUserAdmin $DeviceUserAdmin
 * @property mixed params
 */
class DeviceUserController extends AdminController
{
    public $name = 'DeviceUser';

    public $uses = array(
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
    }


    /**
     * [CMS]端末ユーザー一覧画面用の関数
     *
     * 端末ユーザー一覧画面を表示する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->set('title_for_layout', '端末ユーザー一覧');

        // メッセージ一覧情報取得（ページネーション利用）
        $conditions = array('service_id' => CakeSession::read('service_id'), 'del_flag' => 0);
        $list = $this->paginate($conditions);

        // モデル名除去
        $list = Hash::extract($list, '{n}.DeviceUser');

        $this->set('list', $list);
    }

    /**
     * [CMS]端末ユーザー情報詳細画面用の関数
     *
     * 端末ユーザー情報詳細画面を表示する。
     *
     * @access public
     * @param string $id 端末ID
     */
    public function cms_detail($id = null)
    {
        $this->set('title_for_layout', '端末ユーザー情報詳細');

        // URLにIDがないor数字以外の場合は400エラー
        if ($id === null || !is_numeric($id)) {
            throw new BadRequestException;
        }

        $item = $this->DeviceUserAdmin->readItem($this->Session->read('service_id'), $id);
        $propertyLabels = $this->DeviceUserAdmin->readPropertyLabels($this->Session->read('service_id'));

        // 情報が存在しない場合は404エラー
        if (empty($item)) {
            throw new NotFoundException;
        }

        $this->set('item', $item);
        $this->set('labels', $propertyLabels);
    }
}