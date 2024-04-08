<main class="container">
    <h2 class="text-align-center mb-0"><a class="text-decoration-none" href="/">Pix Pocket</a></h2>
    <div>
        <div class="text-align-center">
            <img class="dynamic-image" id="postimg" src=" <?= "/uploads/" . $path ?>" alt="uploaded image">
        </div>
    </div>

    <button id="modalOpen" class="button">Add a Comment</button>
    <div id="easyModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h1>Great job 🎉</h1>
          <span class="modalClose">×</span>
        </div>
        <div class="modal-body">
            <p>You've just displayed this awesome Modal Window!</p>
            <p>Let's enjoy learning JavaScript ☺️</p>
            <form id="send-form" method="post" enctype="multipart/form-data">
                <label for="file1"></label>
                <div class="text-align-center">
                    <input type="text" id="reply" name="reply" size="15"></p>
                    <input type="file" id="file1" name="file1"><br />
                </div>
                <input id="replybutton" type="submit" value="reply" />
        </div>
      </div>
    </div>
    <div>
        <ul id ="list-group" class="list-group list-unstyled">
        </ul>

    </div>

    
</form>
</main>
<style>
    body {
  font-size: 16px;
  line-height: 1.6;
  color: #fff;
}

.button {
  background: lightblue;
  color: #fff;
  padding: 0 2em;
  border: 0;
  font-size: 45px;
  border-radius: 5px;
  position: relative;
  font-family: serif;
}

.button:hover {
  background: lightcoral;
  cursor: pointer;
}

.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: #f4f4f4;
  margin: 20% auto;
  width: 50%;
  box-shadow: 0 5px 8px 0 rgba(0,0,0,0.2),0 7px 20px 0 rgba(0,0,0,0.17);

}

.modal-header h1 {
  margin: 1rem 0;
}

.modal-header {
  background: lightblue;
  padding: 3px 15px;
  display: flex;
  justify-content: space-between;
}

.modalClose {
  font-size: 2rem;
}

.modalClose:hover {
  cursor: pointer;
}

.modal-body {
  padding: 10px 20px;
  color: black;
}

.post {
    background: #f0f0f0;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.post-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.post-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: bold;
}

.post-content {
    margin: 0;
    font-size: 1rem;
}

.post-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
}

.post-link:hover {
    text-decoration: underline;
}

.post-image {
    width: 50px;
    height: 50px;
    margin-left: 10px;
}

</style>
<script>
    const buttonOpen = document.getElementById('modalOpen');
    const modal = document.getElementById('easyModal');
    const buttonClose = document.getElementsByClassName('modalClose')[0];
    const replyButton = document.getElementById('replybutton');

    // ボタンがクリックされた時
    buttonOpen.addEventListener('click', modalOpen);
    function modalOpen() {
        modal.style.display = 'block';
    }

    // バツ印がクリックされた時
    buttonClose.addEventListener('click', modalClose);
    replyButton.addEventListener('click', modalClose);
    function modalClose() {
      modal.style.display = 'none';
    }
    
    

    // モーダルコンテンツ以外がクリックされた時
    addEventListener('click', outsideClose);
    function outsideClose(e) {
      if (e.target == modal) {
        modal.style.display = 'none';
      }
    }

    document.getElementById('send-form').addEventListener('submit',function(event){
        event.preventDefault();

        let fileInput = document.querySelector("#file1");
        let replyInput = document.querySelector("#reply");
        let img = document.getElementById('postimg');
        let src = img.getAttribute('src');
        const formData = new FormData();
        formData.append('file1',fileInput.files[0]);
        formData.append('reply',replyInput.value);
        formData.append('src',src);

        fetch('/registerReply', {
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
                      // リプライのラッパー要素を作成
                      const postElement = document.createElement('div');
                      postElement.classList.add('post');

                      // const postElement = document.createElement('li');
                      // postElement.classList.add('post');

                      // ヘッダ部分
                      const postHeader = document.createElement('div');
                      postHeader.classList.add('post-header');

                    // 投稿者名などの情報を含むsubject（ここではタイトルとして扱う）
                    const subjectElement = document.createElement('h3');
                    subjectElement.classList.add('post-title');
                    subjectElement.textContent = post.subject;
                    postHeader.appendChild(subjectElement);

                    // コメントの内容
                    const contentElement = document.createElement('p');
                    contentElement.classList.add('post-content');
                    contentElement.textContent = post.content;
                    postElement.appendChild(contentElement);

                    // コメントに対するリンク（URL）
                    const aElement = document.createElement('a');
                    aElement.classList.add('post-link');
                    aElement.setAttribute('href', post.url);
                    aElement.textContent = "Read More";
                    postElement.appendChild(aElement);



                        // const subjectElement = document.createElement('h2');
                        // subjectElement.textContent=post.subject;
                        // postElement.appendChild(subjectElement);

                        // const contentElement = document.createElement('p');
                        // contentElement.textContent = post.content;
                        // postElement.appendChild(contentElement);

                        // const aElement = document.createElement('a');
                        // aElement.setAttribute('href', post.url);

                        if(post.file_path!='/'){
                        //     const imageElement = document.createElement('img');
                        //     imageElement.src = '/uploads/' + post.file_path;
                        //     imageElement.alt = post.subject; 
                        //     aElement.appendChild(imageElement);
                          const imageElement = document.createElement('img');
                          imageElement.classList.add('post-image');
                          imageElement.src = '/uploads/' + post.file_path;
                          imageElement.alt = post.subject;
                          postHeader.appendChild(imageElement);
                        }
                        // コメントにヘッダを追加
                        postElement.appendChild(postHeader);

                        // コメントをコンテナに追加
                        container.appendChild(postElement);

                        // postElement.appendChild(aElement);


                        // container.appendChild(postElement);
                    })
                  
                } else {
                    
                    alert(data.message);
                }
            })
            .catch(error => {
                alert(error);
            });

    })
</script>