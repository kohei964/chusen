let candidates = []; // 候補リスト
let history = [];    // 履歴リスト

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoadedイベントが発生しました");

    const alphabetSection = document.querySelector("#left-alphabet-selection");
    const numberSection = document.querySelector("#left-number-selection");
    const radioButtons = document.querySelectorAll('input[name="left-type"]');

    function updateSections() {
        const selectedValue = document.querySelector('input[name="left-type"]:checked').value;
        console.log("現在の選択:", selectedValue);

        if (selectedValue === "alphabet") {
            alphabetSection.style.display = "flex";
            numberSection.style.display = "none";
        } else if (selectedValue === "number") {
            alphabetSection.style.display = "none";
            numberSection.style.display = "flex";
        }
    }

    // 初期表示を設定
    updateSections();

    // ラジオボタン変更時の処理
    radioButtons.forEach((radio) => {
        radio.addEventListener("change", updateSections);
    });
});


    //抽選箱の作成
    function createCandidates(maxNumber, alphabetStart, alphabetEnd, numberStart, numberEnd, leftType){

        candidates = [];

        //左側がアルファベットの場合
        if(leftType === "alphabet"){
            const startCharCode = alphabetStart.charCodeAt(0); //開始文字のASCIIコード
            const endCharCode = alphabetEnd.charCodeAt(0); //終了文字のASCIIコード

            //charCodeAt()を使用して範囲を取得し、文字と数字の組み合わせを生成
            for(let i = startCharCode; i <= endCharCode; i++){
                const letter = String.fromCharCode(i);
                for(let j = 1; j <= maxNumber; j++){
                    candidates.push(`${letter}-${j}`);
                }
            }
            //左側が数字の場合
        }else if(leftType === "number"){
            //charCodeAt()を使用して範囲を取得し、文字と数字の組み合わせを生成
            for(let i = numberStart; i <= numberEnd; i++){
                for(let j = 1; j <= maxNumber; j++){
                    candidates.push(`${i}-${j}`);
                }
            }
        }

        console.log("候補リスト:", candidates);
    }


    //フォーム送信イベントで抽選箱を作成
    document.querySelector("#config-form").addEventListener("submit", function (e) {

        e.preventDefault();

        const maxNumber = parseInt(document.querySelector("#max-number").value, 10);
        const leftType = document.querySelector('input[name="left-type"]:checked').value;

        let alphabetStart, alphabetEnd, numberStart, numberEnd;

        if(leftType === "alphabet"){
            alphabetStart = document.querySelector("#alphabet-start").value;
            alphabetEnd = document.querySelector("#alphabet-end").value;

            if(alphabetStart > alphabetEnd){
                alert("アルファベットの範囲が不正です。");
                return;
            }
        } else if(leftType === "number"){
            numberStart = parseInt(document.querySelector("#number-start").value, 10);
            numberEnd = parseInt(document.querySelector("#number-end").value, 10);

            if(isNaN(numberStart) || isNaN(numberEnd) || numberStart > numberEnd){
                alert("数字の範囲が不正です。");
                return;
            }
        }

        createCandidates(maxNumber, alphabetStart, alphabetEnd, numberStart, numberEnd, leftType);
        document.querySelector("#control-buttons").style.display = "flex";
        alert("抽選箱を作成しました！");
    });




    // 抽選するボタンの処理
    document.querySelector("#draw-button").addEventListener("click", function () {
        if (candidates.length === 0) {
            alert("全ての項目が引かれました！");
            return;
        }
        const randomIndex = Math.floor(Math.random() * candidates.length);
        const selected = candidates.splice(randomIndex, 1)[0]; // 候補から削除
        history.push(selected);
        document.querySelector("#result").textContent = `結果: ${selected}`;
        updateHistoryList();
    });

    // 終了するボタンの処理
    document.querySelector("#end-button").addEventListener("click", function () {
        if (history.length === 0) {
            alert("まだ抽選結果がありません");
            return;
        }

        fetch("save_history.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ history })
        })
            .then(response => response.text())
            .then(data => {
                alert(data); // 保存成功メッセージを表示
                location.reload(); // 保存後にリロード
            })
            .catch(error => {
                console.error("エラーが発生しました:", error);
            });
    });


    // 履歴リストを更新
    function updateHistoryList() {
        const historyList = document.querySelector("#history-list");
        historyList.innerHTML = "";
        history.forEach((entry, index) => {
            const li = document.createElement("li");
            li.textContent = `${index + 1}: ${entry}`;
            li.classList.add("list-group-item");
            historyList.appendChild(li);
        });
    }

    // 履歴を消去するボタンの処理
    document.querySelector("#clear-history-button").addEventListener("click", function () {
        if (confirm("履歴を消去してもよろしいですか？")) {
            fetch("clear_history.php", { method: "POST" })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    history = [];
                    location.reload(); // ページをリロード
                    updateHistoryList();
                })
                .catch(error => {
                    console.error("履歴の消去中にエラーが発生しました:", error);
                });
        }
    });






