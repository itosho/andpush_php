<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AdminController extends AppController
{

    public $components = array(
        'Session',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'AuthApi',
                    'fields' => array(
                        'username' => 'service_code',
                        'password' => 'auth_key'
                    ),
                    'scope' => array(
                        'service_status' => 1,
                        'del_flag' => 0
                    )
                )
            ),
            // 遷移はログインコントローラーに任せる
            'loginAction' => array(
                'controller' => 'login',
                'action' => 'cms_index'
            ),
            'loginRedirect' => array(
                'controller' => 'login',
                'action' => 'cms_index'
            ),
            'logoutRedirect' => array(
                'controller' => 'login',
                'action' => 'cms_index'
            )
        )
    );

    // マージする親クラス変更（AppControllerはマージ対象から外れる）
    protected $_mergeParent = 'AdminController';

    /**
     * [Common]共通チェック用処理のラッパー関数
     *
     *
     * @access public
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'default_cms';
    }

}
