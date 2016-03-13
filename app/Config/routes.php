<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect('/docs/*', array('controller' => 'docs', 'action' => 'display'));
    Router::connect('/cms', array('controller' => 'login', 'action' => 'index', 'cms' => true));

	
/**
 * API一覧
 * APIが追加された場合ここに書き込む
 */
	// ※パフォーマンス考慮してコントローラー側でレスポンス送信することにした
	// Router::parseExtensions('json', 'xml');
	
	// Pushメッセージ送信（トークンベース）
	Router::connect ( '/v2/push/token', array('controller' => 'PushApi', 'action' => 'token'));
    // Pushメッセージ送信（ユーザーベース）
    Router::connect ( '/v2/push/user', array('controller' => 'PushApi', 'action' => 'user'));
    // Pushメッセージ送信（属性情報ベース）
    Router::connect ( '/v2/push/property', array('controller' => 'PushApi', 'action' => 'property'));
    // Pushメッセージ送信（全端末）
    Router::connect ( '/v2/push/all', array('controller' => 'PushApi', 'action' => 'all'));
    // Pushメッセージ情報削除
    Router::connect ( '/v2/message/destroy', array('controller' => 'MessageApi', 'action' => 'destroy'));
    // Pushメッセージ情報取得
    Router::connect ( '/v2/message', array('controller' => 'MessageApi', 'action' => 'index'));
    // 端末情報登録 / 更新
    Router::connect ( '/v2/device/entry', array('controller' => 'DeviceApi', 'action' => 'entry'));
    // 端末開封通知
    Router::connect ( '/v2/device/open', array('controller' => 'DeviceApi', 'action' => 'open'));
    // 端末情報クリア
    Router::connect ( '/v2/device/clear', array('controller' => 'DeviceApi', 'action' => 'clear'));
    // 端末情報取得
    Router::connect ( '/v2/device', array('controller' => 'DeviceApi', 'action' => 'index'));
    // ユーザー属性情報登録 / 更新
    Router::connect ( '/v2/user/property', array('controller' => 'UserApi', 'action' => 'property'));
    // ユーザー情報削除
    Router::connect ( '/v2/user/destroy', array('controller' => 'UserApi', 'action' => 'destroy'));
    // ユーザー情報取得
    Router::connect ( '/v2/user', array('controller' => 'UserApi', 'action' => 'index'));
    // 日次統計情報取得
    Router::connect ( '/v2/statistic/daily', array('controller' => 'StatisticApi', 'action' => 'daily'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
