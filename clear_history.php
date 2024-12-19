<?php
$file = 'data/history.json';

// ファイルを空の配列として初期化
if (file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "履歴が消去されました。";
} else {
    echo "履歴ファイルが存在しません。";
}
?>
