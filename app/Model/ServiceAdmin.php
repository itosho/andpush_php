<?php
App::uses('AdminModel', 'Model');
App::uses('Service', 'Model/ActiveRecord');

/**
 * [class]サービス管理系モデルクラス
 *
 * CMSのサービス管理に関する処理をまとめたモデルクラス。
 * ServiceControllerクラスから呼ばれる。
 * アクティブレコード系のモデルクラスを持つ。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Service
 * @package Model
 */
class ServiceAdmin extends AdminModel
{
    public $name = "ServiceAdmin";
    public $useTable = 'services';
    private $service;

    /**
     * [function]コンストラクタ関数
     *
     * メッセージ管理で利用するテーブルのモデルクラスをインスタンス化する。
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new Service ();

    }

    /**
     * [function]入力項目チェック用関数
     *
     * @access public
     * @param array $params
     *            リクエストパラメーター
     * @return boolean $result true=成功 / false=失敗
     */
    public function inputCheck($params)
    {
        $errMsg = array();

        // サービス名
        if (!Validation::notEmpty($params['service_name'])) {
            $errMsg[] = "サービス名は必ず入力してください。";
        }
        if (!Validation::maxLength($params['service_name'], 30)) {
            $errMsg[] = "サービス名は30文字以内で入力してください。";
        }

        // サービスコード
        if (!Validation::notEmpty($params['service_code'])) {
            $errMsg[] = "サービスコードは必ず入力してください。";
        }
        if (!preg_match("/^[a-zA-Z0-9]{1,20}+$/", $params['service_code']) ) {
            $errMsg[] = "サービスコードは半角英数字20文字以内で入力してください。";
        }
        // 条件
        $conditions = array(
            'id !=' => CakeSession::read('service_id'),
            'service_code' => $params['service_code'],
            'service_status' => 1,
            'del_flag' => 0
        );
        // SQL実行
        $count = $this->service->find('count', array(
            'conditions' => $conditions
        ));
        if ($count > 0) {
            $errMsg[] = "入力したサービスコードは既に利用されています。";
        }

        // 証明書
        if ($params['certification']['name'] != '') {
            if (!is_uploaded_file($params['certification']['tmp_name'])){
                $errMsg['certification'] = "証明書ファイルが不正です。";
            } elseif ($params['certification']['size'] > 1024*1024) {
                $errMsg['certification'] = "証明書のファイルサイズが不正です。1MB以内のファイルを設定してください。";
            } elseif (! Validation::extension($params['certification']['name'], array('pem'))) {
                $errMsg['certification'] = "証明書ファイルの拡張子が不正です。.pemファイルを設定してください。";
            }
        }

        // 認証キー
        if ($params['password_text'] != '') {
            if (!preg_match("/^[a-zA-Z0-9]{8,24}+$/", $params['password_text']) ) {
                $errMsg[] = "認証キーは半角英数字8文字以上で入力してください。";
            }
        } else {
            if ($params['password_confirm'] != '') {
                $errMsg[] = "認証キー確認を入力した場合は、認証キーを入力してください。";
            }
        }

        // 認証キー確認
        if ($params['password_text'] != '') {
            if (!Validation::notEmpty($params['password_confirm'])) {
                $errMsg[] = "認証キーを入力した場合は、認証キー確認を入力してください。";
            }

            if ($params['password_text'] != $params['password_confirm']) {
                $errMsg[] = "認証キーと認証キー確認が一致しません。";
            }
        }

        // GCM APIキー確認
        if ($params['android_api_key'] != '') {
            if (!Validation::maxLength($params['android_api_key'], 255)) {
                $errMsg[] = "GCM APIキーの値が不正です。";
            }
        }

        // 管理者名
        if (!Validation::notEmpty($params['contact_name'])) {
            $errMsg[] = "管理者名は必ず入力してください。";
        }
        if (!Validation::maxLength($params['contact_name'], 20)) {
            $errMsg[] = "管理者名は20文字以内で入力してください。";
        }
        // 管理者メールアドレス
        if (!Validation::notEmpty($params['contact_email'])) {
            $errMsg[] = "管理者メールアドレスは必ず入力してください。";
        }
        if (!Validation::email($params['contact_email'])) {
            $errMsg[] = "管理者メールアドレスはメールアドレス形式で入力してください。";
        }

        return $errMsg;
    }

    /**
     * [function]サービス情報取得関数
     *
     * サービス情報を取得する。
     *
     * @access public
     * @param string $serviceId
     *            サービスID
     * @return array $item サービスs情報
     */
    public function readItem($serviceId)
    {
        // 条件（サービス単位で）
        $conditions = array(
            'id' => $serviceId,
            'service_status' => 1,
            'del_flag' => 0
        );
        // SQL実行
        $result = $this->service->find('first', array(
            'conditions' => $conditions
        ));

        if (empty($result)) {
            return array();
        }

        // モデル名除去
        $item = Hash::extract($result, 'Service');

        return $item;
    }

    /**
     * [function]Pushメッセージ更新処理用の関数
     *
     * @access public
     * @param integer $serviceId
     *            サービスID
     * @param array $params
     *            更新パラメーター
     * @return boolean $saveResult 更新結果
     */
    public function updateService($serviceId, $params)
    {
        $saveData = array();
        $saveData ['id'] = $serviceId; // サービスID（主キー）
        $saveData ['service_code'] = $params['service_code'];
        $saveData ['service_name'] = $params['service_name'];
        if ($params['password_text'] != '') {
            $passwordHasher = new SimplePasswordHasher ();
            $saveData ['auth_key'] = $passwordHasher->hash($params['password_text']);
        }
        if ($params['certification_file'] != '') {
            $saveData ['ios_cert_file'] = base64_decode($params['certification_file']);
        }
        if ($params['android_api_key'] != '') {
            $saveData ['android_api_key'] = $params['android_api_key'];
        }
        $saveData ['contact_name'] = $params['contact_name'];
        $saveData ['contact_email'] = $params['contact_email'];

        // サービスマスタ更新
        $saveResult = $this->service->save($saveData, false);

        return $saveResult;
    }
}