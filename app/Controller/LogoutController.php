<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]ログアウトコントローラークラス
 *
 * ログアウト処理をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category CMS
 * @package Controller
 */
class LogoutController extends AdminController
{

    public $name = 'Logout';
    // モデルは利用しない（単純処理のため）
    public $uses = null;

    /**
     * [CMS]ログアウト処理コントローラー
     *
     * セッションを破棄して、ログイン画面へ遷移する。
     *
     * @access public
     */
    public function cms_index()
    {
        $this->Auth->logout();
        $this->Session->setFlash('ログアウトしました。', 'default', array(
            'class' => 'alert alert-info '
        ));
        // ログイン画面へ遷移する
        $this->redirect(array(
            'controller' => 'login',
            'action' => 'cms_index'
        ));
    }
}