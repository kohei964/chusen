<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

//履歴データの読み込み
$file = 'data/history.json';
$history = [];

if(file_exists($file)){
    $json = file_get_contents($file);
    $history = json_decode($json, true);
}

    // JSONデータが壊れている場合、または配列でない場合の対応
    if (!is_array($history)) {
        $history = [];
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>抽選箱</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=1.1">
</head>
<body>

    <div class="container py-5">
        <h1 class="text-center mb-5">抽選箱</h1>



        <!-- 抽選箱の設定フォーム -->
        <div class="form-group mb-5">
            <form id="config-form">
                <!-- 左側を数字かアルファベットで選ぶ -->
                <div class="mb-4">
                    <label class="form-label d-block fw-bold">左側（列）の選択</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="left-type" id="left-alphabet" value="alphabet" checked>
                        <label class="form-check-label" for="left-alphabet">アルファベット</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="left-type" id="left-number" value="number">
                        <label class="form-check-label" for="left-number">数字</label>
                    </div>
                </div>

                <!-- アルファベットまたは数字の選択 -->
                <div class="mb-4" id="left-alphabet-selection">
                    <label for="alphabet-start" class="form-label">開始アルファベット:</label>
                    <select id="alphabet-start" class="form-select d-inline w-auto">
                        <?php foreach (range('a', 'z') as $char): ?>
                            <option value="<?= $char ?>"><?= $char ?></option>
                        <?php endforeach; ?>
                    </select>
                    〜
                    <label for="alphabet-end" class="form-label">終了アルファベット:</label>
                    <select id="alphabet-end" class="form-select d-inline w-auto">
                        <?php foreach (range('a', 'z') as $char): ?>
                            <option value="<?= $char ?>"><?= $char ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4" id="left-number-selection" style="display: none;">
                    <label for="number-start" class="form-label">開始番号:</label>
                    <input type="number" id="number-start" class="form-control d-inline w-auto" placeholder="開始" min="1" value="1">
                    〜
                    <label for="number-end" class="form-label">終了番号:</label>
                    <input type="number" id="number-end" class="form-control d-inline w-auto" placeholder="終了" min="1" value="10">
                </div>

                <!-- 右側の数字の上限 -->
                <div class="mb-4">
                    <label for="max-number" class="form-label">座席の数字の上限：</label>
                    <input type="number" id="max-number" class="form-control w-50" placeholder="例: 100" min="1" max="100" value="10">
                </div>

                <button type="submit" class="btn btn-primary">抽選箱を作成</button>
            </form>
        </div>

        <!--  除外番号用のフォーム作成  -->
        <form id="exclude-form">
            <h2>除外リストの設定</h2>
            <div class="mb-4">
                <label for="exclude-input" class="form-label">除外番号を入力（カンマ区切り）：</label>
                <input id="exclude-input" type="text" name="exclude-input" placeholder="例: 1-1,2-2,3-3" />
            </div>
            <button type="button" class="btn btn-secondary">除外リストを適用</button>
        </form>



        <!-- ボタンセクション -->
        <div id="control-buttons" class="d-flex justify-content-center gap-3 mb-5">
            <button id="draw-button" class="btn btn-primary button-hidden">抽選する</button>
            <button id="end-button" class="btn btn-success button-hidden">終了する</button>
            <button id="clear-history-button" class="btn btn-danger button-hidden">履歴を消去</button>
        </div>

        <!-- 結果表示 -->
        <div class="text-center mb-5">
            <h4 id="result" class="text-info">結果: </h4>
            <ul id="history-list" class="list-group"></ul>
        </div>

        <!-- 履歴セクション -->
        <h2 class="text-center mb-3">過去の履歴</h2>
        <ul class="list-group">
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $set): ?>
                    <li class="list-group-item">
                        <strong>セット日時:</strong> <?= htmlspecialchars($set['timestamp']) ?> - 
                        <strong>結果:</strong> <?= htmlspecialchars(implode(', ', $set['history'])) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item text-muted">履歴がまだありません。</li>
            <?php endif; ?>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>
ß

<!-- http://localhost/chusen/  -->