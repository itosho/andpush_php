<?php
App::uses('AdminController', 'Controller');

/**
 * [CMS]ログインコントローラークラス
 *
 * ログイン処理をまとめたコントローラークラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category CMS
 * @package Controller
 */
class LoginController extends AdminController
{
    public $name = 'Login';
    // 管理者情報操作モデル
    /*public $uses = array(
        'Login'
    );*/

    /**
     * [CMS]ログイン画面コントローラー
     *
     * POST時：認証処理を行う。成功時は集計画面へ遷移する。
     * GET時：ログイン画面を表示する。
     *
     * @access public
     */
    public function cms_index()
    {
        // ログイン画面のみ独自のレイアウトを利用する
        $this->autoLayout = false;

        if ($this->request->isPost()) {

            $res = $this->Auth->login();

            // 認証エラー時
            if ($res != true) {
                $this->Session->setFlash('IDまたはパスワードが正しくありません。', 'default', array(
                    'class' => 'alert alert-danger '
                ));
                // ログイン画面へ遷移する
                $this->redirect(array(
                    'controller' => 'login',
                    'action' => 'cms_index'
                ));
            }

            $this->Auth->user();
            // セッションにユーザ情報書き込み
            $this->Session->write('service_id', $this->Auth->user('id'));
            $this->Session->write('service_code', $this->Auth->user('service_code'));
            $this->Session->write('service_name', $this->Auth->user('service_name'));
            $this->Session->write('ios_cert_file', $this->Auth->user('ios_cert_file'));
            $this->Session->write('android_api_key', $this->Auth->user('android_api_key'));
            $this->Session->write('contact_email', $this->Auth->user('contact_email'));
            $this->Session->write('contact_name', $this->Auth->user('contact_name'));

            $this->redirect($this->Auth->redirectUrl(array(
                'controller' => 'message',
                'action' => 'cms_index'
            )));

        } else {

            if ($this->Auth->loggedIn()) { // ログイン済の場合
                $this->redirect($this->Auth->redirectUrl(array(
                    'controller' => 'message',
                    'action' => 'cms_index'
                )));
            }
        }
    }

    /**
     * [Common]共通チェック用処理のラッパー関数
     *
     * 認証不要画面の設定を行う。
     *
     * @access public
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // ログイン画面のみ認証不要とする
        $this->Auth->allow('cms_index');
    }
}