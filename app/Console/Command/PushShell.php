<?php
App::uses('Message', 'Model/ActiveRecord');

/**
 * [class]Pushメッセージシェルクラス
 *
 * Pushメッセージに関するバッチ処理をまとめたシェルクラス。
 *
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Push
 * @package Shell
 * @property Push $Push
 */
class PushShell extends AppShell
{
    public $uses = array(
        'Push'
    );

    /**
     * [function]Push通知用シェル（メイン処理）
     *
     * 対象となるメッセージ情報を取得後、Push通知を実行する。
     * 対象メッセージは送信時間≒実行時間のデータ（誤差2分）とする。
     *
     * @access public
     */
    public function main()
    {
        try {

            // メッセージモデルインスタンス化
            $messageModel = new Message ();
            // 対象メッセージ取得
            $messages = $messageModel->findOnService();

            if (empty ($messages)) {
                $this->log('【nothing】Push通知対象メッセージはありません。', LOG_SHELL);
                exit ();
            }
            $count = count($messages); // 対象件数
            $count1 = 0; // 完全成功件数
            $count2 = 0; // 成功件数
            $count3 = 0; // 失敗件数
            $dbCount = 0; // DB処理件数
            $this->log("【start】対象件数：$count", LOG_SHELL);

            foreach ($messages as $message) { // メッセージID単位で処理する
                $servicer ['id'] = $message ['s'] ['id'];
                $servicer ['android_api_key'] = $message ['s'] ['android_api_key'];
                $servicer ['ios_cert_file'] = $message ['s'] ['ios_cert_file'];
                $messageId = $message ['m'] ['id'];
                $messageTitle = $message ['m'] ['message_title'];
                $messageBody = $message ['m'] ['message_body'];
                $this->log("【proccess】処理対象メッセージID：$messageId", LOG_SHELL);
                // Push通知処理
                $pushResult = $this->Push->send($servicer, $messageId, $messageTitle, $messageBody);
                $this->log($pushResult, LOG_SHELL);

                $sendResult = Configure::read($pushResult['send_result_key']);
                $this->log("【proccess】送信結果コード：" . $sendResult ['code'], LOG_SHELL);

                if ($sendResult ['code'] == '2000') {
                    $count1++;
                } elseif ($sendResult ['code'] == '2002') {
                    $count2++;
                } else {
                    $count3++;
                }

                // メッセージマスタ更新
                $updData = array(
                    'id' => $messageId,
                    'send_result_code' => $sendResult ['code']
                );
                $updFields = array(
                    'send_result_code'
                );
                $updResult = $messageModel->save($updData, false, $updFields);

                if ($updResult) {
                    $dbCount++;
                }
                sleep(1); // 負荷を考慮して1秒遅延させる
            }
            $this->log("【end】完全成功件数：$count1 / 成功件数：$count2 / 失敗件数： $count3 / 処理件数：$dbCount", LOG_SHELL);
            exit ();
        } catch (Exception $e) {
            $this->log('【error】例外処理発生！', LOG_SHELL);
            $this->log($e->getMessage(), LOG_SHELL);
            exit ();
        }
    }
}