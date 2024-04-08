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
            <?php if (empty($posts)): ?>
                <div class="alert alert-info">スニペットは登録されていません。</div>
                
            <?php else: ?>
                
                <ul id ="list-group" class="list-group list-unstyled">
                    <?php foreach ($posts as $post): ?>
                        <li class=" post border-top pt-2 pb-2">
                            <h2><?=htmlspecialchars($post -> subject) ?></h2>
                            <a href="<?= htmlspecialchars($post -> url) ?>" class="text-decoration-none">
                            <img src=" <?= "/uploads/".$post -> file_path ?>" alt="">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>
.post:hover{
    background-color: rgba(240,240,240);
}
.container {
    max-width: 800px; /* 最大幅を設定 */
    margin: auto; /* 中央寄せ */
    padding: 20px; /* コンテナの内側に余白を設定 */
}
#list-group {
    margin: 0;
    padding: 0;
}
.post {
    list-style-type: none; /* リストマーカーを非表示 */
    background-color: #fff; /* 背景色 */
    margin-bottom: 15px; /* 下の余白 */
    border: 1px solid #ddd; /* 枠線 */
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); /* 通常時の影 */
    transition: box-shadow 0.3s ease-in-out; /* 影のトランジション */
}
.post:hover {
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2); /* マウスオーバー時の影 */
}
.post h2 {
    font-size: 1.5em; /* 文字サイズ */
    margin: 0; /* マージンをリセット */
    padding: 10px; /* 余白 */
    border-bottom: 1px solid #ddd; /* 下枠線 */
}
.post img {
    width: 100%; /* 幅を調整 */
    max-height: 300px; /* 最大高さを設定 */
    object-fit: cover; /* 画像を要素に合わせてトリミング */
}
.post a {
    color: inherit; /* 継承された色を使用 */
    text-decoration: none; /* 下線を非表示 */
    display: block; /* ブロック要素として表示 */
    padding: 10px; /* 余白 */
}
.post p {
    padding: 10px; /* 余白 */
    margin: 0; /* マージンをリセット */
}
#send-form input[type="submit"] {
    display: block; /* ブロック要素として表示 */
    width: 100%; /* 幅を100%に設定 */
    padding: 10px; /* 余白 */
    margin-top: 10px; /* 上の余白 */
    border: none; /* 枠線を非表示 */
    background-color: #007bff; /* 背景色 */
    color: white; /* 文字色 */
    cursor: pointer; /* カーソルをポインターに */
}
#send-form input[type="submit"]:hover {
    background-color: #0056b3; /* マウスオーバー時の背景色 */
}
</style>
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

                    const posts = data.post;


                    const container = document.getElementById('list-group');
                    container.innerHTML = '';

                    posts.forEach(post => {
                        const postElement = document.createElement('li');
                        postElement.classList.add('post');

                        const subjectElement = document.createElement('h2');
                        subjectElement.textContent=post.subject;
                        postElement.appendChild(subjectElement);

                        const contentElement = document.createElement('p');
                        contentElement.textContent = post.content;
                        postElement.appendChild(contentElement);

                        const aElement = document.createElement('a');
                        aElement.setAttribute('href', post.url);

                        const imageElement = document.createElement('img');
                        imageElement.src = '/uploads/' + post.file_path;
                        imageElement.alt = post.subject; 
                        aElement.appendChild(imageElement);
                        postElement.appendChild(aElement);


                        container.appendChild(postElement);
                    })
                  
                } else {
                    
                    alert(data.message);
                }
            })
            .catch(error => {
                alert(error);
            });

    })

    // document.querySelector('.post').addEventListener('click',function(event){
    //     event.preventDefault();
    //     console.log('testt')
    // });








</script>
