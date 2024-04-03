<?php

?>
<form id="send-form" method="post" enctype="multipart/form-data">
    <label for="file1"></label>
    <div class="text-align-center">
        <input type="file" id="file1" name="file1"><br />
    </div>
    <input type="submit" value="送信する" />
</form>


<div class="container">
    <div class="row">
        <div class="col">
            <?php if (empty($images)): ?>
                <div class="alert alert-info">スニペットは登録されていません。</div>
                
            <?php else: ?>
          <img src="/images/Screenshottest.png" alt="">
          <img src="https://www.hitachi-solutions-create.co.jp/column/img/image-generation-ai.jpg" alt="">
          <img src="https://www.hitachi-solutions-create.co.jp/column/img/image-generation-ai.jpg" alt="">
                
                <ul class="list-group">
                    <?php foreach ($images as $image): ?>
                        <li class="list-group-item">
                            <a href="/show?path=<?= htmlspecialchars($image['id']) ?>" class="text-decoration-none">
                                <h5><?= htmlspecialchars($image['title']) ?></h5>
                                <small>ファイル名: <?= htmlspecialchars($image['file_name']) ?></small><br>
                                <small>Expire: <?= $image['title'] ? htmlspecialchars($image['title']) : "Never" ?></small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    //const modal = document.getElementById('modal');
    const progressWindow = document.getElementById('progress-window');

    document.getElementById('send-form').addEventListener('submit',function(event){
        event.preventDefault();

        let fileInput=document.querySelector("#file1");
        const formData = new FormData();
        formData.append('file1',fileInput.files[0]);
        //progressWindow.showModal();

        fetch('register', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                     //progressWindow.close;

                    const shared_url = data.shared_url;
                    const delete_url = data.delete_url;

                    const container = document.getElementById('modal-container');
                    container.innerHTML = `
                    <div id="test">
                        <div >
                            <h3 class="mt-2">Upload Complete!</h3>     
                            <p>共有用URL:<br><a href="${shared_url}" target="_blank" rel="noopener">${shared_url}</a></p>
                            <p>削除用URL:<br><a href="${delete_url}" target="_blank" rel="noopener">${delete_url}</a></p>
                        </div>
                    </div>
                    `;
                } else {
                    progressWindow.open = false;
                    alert(data.message);
                }
            })
            .catch(error => {
                progressWindow.open = false;
                alert(error);
            });

    })




</script>
