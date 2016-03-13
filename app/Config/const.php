<?php
// process_result
const RESULT_SUCCESS = 1;
const RESULT_ERROR = 0;

// push_target
const OS_IOS = 'ios';
const OS_ANDROID = 'android';

const SEND_DETAIL_SUCCESS = 'success';
const SEND_DETAIL_ERROR = 'error';
const SEND_DETAIL_REGISTRATION_ID = 'registraion_id';
const SEND_DETAIL_SEPARATER = ':';

/* --- メッセージコード -- */
// send result code
// 1000番台：Pushエラー
$config['send_error_andpush'] = array(
    "code" => "1000",
    "msg"=>"Pushメッセージ送信処理が失敗しました。"
);
$config['send_error_reserved'] = array(
    "code" => "1001",
    "msg"=>"Pushメッセージ送信予約処理が失敗しました。"
);
$config['send_error_provider'] = array(
    "code" => "1002",
    "msg"=>"APNS/GCMサーバーでエラーが発生しました。"
);
// 2000番台：Push成功
$config['send_success_normality'] = array(
    "code" => "2000",
    "msg"=>"Pushメッセージ送信処理が全て成功しました。"
);
$config['send_success_reserved'] = array(
    "code" => "2001",
    "msg"=>"Pushメッセージ送信予約処理が成功しました。"
);
$config['send_success_subnormality'] = array(
    "code" => "2002",
    "msg"=>"Pushメッセージ送信処理が成功しました。"
);

// error code
// 4000番台：クライアントエラー
$config['client_error'] = array(
    "code" => "4000",
    "msg"=>"リクエストに問題があります。"
);
$config['auth_error'] = array(
    "code" => "4001",
    "msg"=>"認証情報が正しくありません。"
);
$config['param_error'] = array(
    "code" => "4002",
    "msg"=>"リクエストパラメーターが不正です。"
);
$config['method_error'] = array(
    "code" => "4003",
    "msg"=>"リクエストメソッドが不正です。"
);
$config['exist_error'] = array(
    "code" => "4004",
    "msg"=>"指定されたデータが存在しません。"
);
$config['push_list_error'] = array(
    "code" => "4005",
    "msg"=>"指定された条件ではPush対象端末が存在しません。"
);

// 5000番台：サーバーエラー
$config['server_error'] = array(
    "code" => "5000",
    "msg"=>"システムエラーが発生しました。"
);
$config['save_error'] = array(
    "code" => "5001",
    "msg"=>"データベースへの保存に失敗しました。"
);
$config['delete_error'] = array(
    "code" => "5002",
    "msg"=>"データベースからの削除に失敗しました。"
);


// 9000番台：不明なエラー
$config['unknown_error'] = array(
    "code" => "9000",
    "msg"=>"予期せぬエラーが発生しました。管理者へ問い合わせください。"
);

/* --- Push処理結果メッセージ -- */
// 1000番台：Pushエラー
$config['send_reslut_code_1000'] = $config['send_error_andpush']['msg'];
$config['send_reslut_code_1001'] = $config['send_error_reserved']['msg'];
$config['send_reslut_code_1002'] = $config['send_error_provider']['msg'];

// 2000番台：Push成功
$config['send_reslut_code_2000'] = $config['send_success_normality']['msg'];
$config['send_reslut_code_2001'] = $config['send_success_reserved']['msg'];
$config['send_reslut_code_2002'] = $config['send_success_subnormality']['msg'];
