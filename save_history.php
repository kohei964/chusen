<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // php://input のデータ確認
    $rawData = file_get_contents('php://input');
    if ($rawData) {
        file_put_contents('data/debug.log', "受信データ: " . $rawData . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents('data/debug.log', "php://input が空です" . PHP_EOL, FILE_APPEND);
        echo "不正なデータです";
        exit;
    }

    // JSONデコードとエラー確認
    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        file_put_contents('data/debug.log', "JSONデコードエラー: " . json_last_error_msg() . PHP_EOL, FILE_APPEND);
        echo "不正なデータです";
        exit;
    }

    // デコード結果をログ出力
    if ($data && is_array($data)) {
        file_put_contents('data/debug.log', "デコード結果: " . print_r($data, true) . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents('data/debug.log', "JSONデコード後にデータが不正です" . PHP_EOL, FILE_APPEND);
        echo "不正なデータです";
        exit;
    }

    // history キーの存在確認
    if (isset($data['history']) && is_array($data['history'])) {
        $file = 'data/history.json';
        $currentData = [];

        // 既存の履歴を取得
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $currentData = json_decode($json, true);
            if (!is_array($currentData)) {
                $currentData = [];
            }
        }

        // 新しい履歴を追加
        $record = [
            'timestamp' => date('Y-m-d H:i:s'),
            'history' => $data['history']
        ];
        $currentData[] = $record;

        // ファイルに書き込み
        file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "履歴が保存されました";
    } else {
        echo "不正なデータです";
    }
} else {
    echo "無効なリクエストです";
}
?>
