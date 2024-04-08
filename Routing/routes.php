<?php

require_once "../vendor/autoload.php";
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Models\ComputerPart;
use Types\ValueType;
return [
    ''=>function(): HTTPRenderer{
        //期限切れのスニペットを削除する
        //DatabaseHelper::deleteExpiredSnippet();

       // $part = DatabaseHelper::getRandomComputerPart();
        $postDao = new ComputerPartDAOImpl();
        $posts = $postDao->getAllPost(0,7);

        if($posts === null) throw new Exception('Specified part was not found!');
        return new HTMLRenderer('component/list', ['posts'=>$posts]);
        
       //['part'=>$part]
    },
    'register' => function (): JSONRenderer {
        
        // エラーがなかった場合、スニペットをテーブルに登録
        // urlを生成する
        try{
            $tmpPath = $_FILES['file1']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpPath);
            $byteSize = filesize($tmpPath);

            $ipAddress = $_SERVER['REMOTE_ADDR'];
             /* 拡張子情報の取得・セット */
             $imginfo = getimagesize($_FILES['file1']['tmp_name']);
            if($imginfo['mime'] == 'image/jpeg'){ $extension = ".jpg"; }
            if($imginfo['mime'] == 'image/png'){ $extension = ".png"; }
            if($imginfo['mime'] == 'image/gif'){ $extension = ".gif"; }

            $extension = explode('/', $mime)[1];

            $filename = hash('sha256', uniqid(mt_rand(), true)) . '.' . $extension;
            $uploadDir =   './uploads/'; 
            $subdirectory = substr($filename, 0, 2);
            $imagePath = $uploadDir .  $subdirectory. '/' . $filename;
            // アップロード先のディレクトリがない場合は作成
            if(!is_dir(dirname($imagePath))){
                mkdir(dirname($imagePath),0777,true);
                chmod(dirname($imagePath), 0775);
            }
            // $imagesDir =   './images/';
            // $svgfilename = 'checkmark.svg';
            // chmod(dirname($imagesDir.$svgfilename), 0775);

            // アップロードにした場合は失敗のメッセージを送る
            if(move_uploaded_file($tmpPath, $imagePath)){
                chmod($imagePath, 0664);
            }else{
                return new JSONRenderer(['success' => false, 'message' => 'アップロードに失敗しました。']);
            }

            $hash_for_shared_url = hash('sha256', uniqid(mt_rand(), true));
            // $hash_for_delete_url = hash('sha256', uniqid(mt_rand(), true));
            $shared_url = '/' . $extension . '/' . $hash_for_shared_url;
            // $delete_url = '/' .  'delete' . '/' . $hash_for_delete_url;
            $imagePathFromUploadDir = $subdirectory . '/'.$filename;
            $postDao = new ComputerPartDAOImpl();
            $result = $postDao->insertImage($imagePathFromUploadDir,$_FILES['file1']['name'],$_FILES['file1']['type'],$_FILES['file1']['size'],$shared_url);

            $posts = $postDao->getAllPost(0,7);


            return new JSONRenderer(['success' => true,'post' => $posts]);
            
        }catch(Exception $e){
            print($e);
            
            return new JSONRenderer(['status' => 'error', 'message' => 'Invalid data.']);
        }
    },

    'registerReply' => function (): JSONRenderer {
        
        // エラーがなかった場合、スニペットをテーブルに登録
        // urlを生成する
        try{
            $tmpPath = $_FILES['file1']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $replyContent = $_POST['reply'];
            $src = $_POST['src'];
            $file_path_parent_img = substr($src ,10);

            $filename=null;
            $filetype=null;
            $filesize=null;
            
           
            if($tmpPath!=null){
                $mime = $finfo->file($tmpPath);
                $byteSize = filesize($tmpPath);
                
                $filename=$_FILES['file1']['name'];
                $filetype=$_FILES['file1']['type'];
                $filesize=$_FILES['file1']['size'];
                
                

                $ipAddress = $_SERVER['REMOTE_ADDR'];
                 /* 拡張子情報の取得・セット */
                 $imginfo = getimagesize($_FILES['file1']['tmp_name']);
                if($imginfo['mime'] == 'image/jpeg'){ $extension = ".jpg"; }
                if($imginfo['mime'] == 'image/png'){ $extension = ".png"; }
                if($imginfo['mime'] == 'image/gif'){ $extension = ".gif"; }

                $extension = explode('/', $mime)[1];

                $filename = hash('sha256', uniqid(mt_rand(), true)) . '.' . $extension;
                $uploadDir =   './uploads/'; 
                $subdirectory = substr($filename, 0, 2);
                $imagePath = $uploadDir .  $subdirectory. '/' . $filename;
                // アップロード先のディレクトリがない場合は作成
                if(!is_dir(dirname($imagePath))){
                    mkdir(dirname($imagePath),0777,true);
                    chmod(dirname($imagePath), 0775);
                }
                // $imagesDir =   './images/';
                // $svgfilename = 'checkmark.svg';
                // chmod(dirname($imagesDir.$svgfilename), 0775);

                // アップロードにした場合は失敗のメッセージを送る
                if(move_uploaded_file($tmpPath, $imagePath)){
                    chmod($imagePath, 0664);
                }else{
                    return new JSONRenderer(['success' => false, 'message' => 'アップロードに失敗しました。']);
                }
            }

            $hash_for_shared_url = hash('sha256', uniqid(mt_rand(), true));
            // $hash_for_delete_url = hash('sha256', uniqid(mt_rand(), true));
            $shared_url = '/' . $extension . '/' . $hash_for_shared_url;
            // $delete_url = '/' .  'delete' . '/' . $hash_for_delete_url;
            $imagePathFromUploadDir = $subdirectory . '/'.$filename;

            $postDao = new ComputerPartDAOImpl();

            //親ポストを取得
            $post_parent = $postDao->getPostDataByPath($file_path_parent_img);

            //$result = $postDao->insertReply($post_parent[0]['post_id'],$replyContent,$imagePathFromUploadDir,$_FILES['file1']['name'],$_FILES['file1']['type'],$_FILES['file1']['size']);
            $result = $postDao->insertReply($post_parent[0]['post_id'],$replyContent,$imagePathFromUploadDir,$filename,$filetype,$filesize);

            $posts = $postDao->getPostDataByReplyToId($result);


            return new JSONRenderer(['success' => true,'post' => $posts]);
            
        }catch(Exception $e){
            print($e);
            
            return new JSONRenderer(['status' => 'error', 'message' => 'Invalid data.']);
        }
    },
    'getImage' => function(): HTMLRenderer{
            $shared_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $postDao = new ComputerPartDAOImpl();
            $post = $postDao->getPostData($shared_url);

            if (!$post) {
                http_response_code(404);
                return new HTMLRenderer('component/404', ['errormsg' => "Page not found"]);
            }

           // if(!DatabaseHelper::updateImageData($shared_url)) return new JSONRenderer(['success' => false, 'message' => 'データベースの操作に失敗しました。']);

            // $path = $data['file_path'];
            // $viewCount = $data['view_count'];
            // $mime = $data['mine_type'];
            $path = $post[0]->file_path;

            return new HTMLRenderer('component/register-result', ['path'=> $path]);
    },
    
    'random/part'=>function(): HTTPRenderer{
        $partDao = new ComputerPartDAOImpl();
        $part = $partDao->getRandom();

        if($part === null) throw new Exception('No parts are available!');

        return new HTMLRenderer('component/computer-part-card', ['part'=>$part]);
    },
    'parts'=>function(): HTTPRenderer{
        // IDの検証
        $id = ValidationHelper::integer($_GET['id']??null);

        $partDao = new ComputerPartDAOImpl();
        $part = $partDao->getById($id);

        if($part === null) throw new Exception('Specified part was not found!');

        return new HTMLRenderer('component/computer-part-card', ['part'=>$part]);
    },
    'parts/all'=>function(): HTTPRenderer{
        
        $partDao = new ComputerPartDAOImpl();
        $parts = $partDao->getAll(2,7);

        if($parts === null) throw new Exception('Specified part was not found!');

        return new HTMLRenderer('component/computer-part-card-all', ['parts'=>$parts]);
    },
    'parts/type'=>function(): HTTPRenderer{

        //$type = ValidationHelper::validateFields($_GET['type']??null);
        
        $partDao = new ComputerPartDAOImpl();
        $parts = $partDao->getAll(2,7);

        if($parts === null) throw new Exception('Specified part was not found!');

        return new HTMLRenderer('component/computer-part-card-all', ['parts'=>$parts]);
    },
    'update/part' => function(): HTMLRenderer {
        $part = null;
        $partDao = new ComputerPartDAOImpl();
        if(isset($_GET['id'])){
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->getById($id);
        }
        return new HTMLRenderer('component/update-computer-part',['part'=>$part]);
    },
    'form/update/part' => function(): HTTPRenderer {
        try {
            // リクエストメソッドがPOSTかどうかをチェックします
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $required_fields = [
                'name' => ValueType::STRING,
                'type' => ValueType::STRING,
                'brand' => ValueType::STRING,
                'modelNumber' => ValueType::STRING,
                'releaseDate' => ValueType::DATE,
                'description' => ValueType::STRING,
                'performanceScore' => ValueType::INT,
                'marketPrice' => ValueType::FLOAT,
                'rsm' => ValueType::FLOAT,
                'powerConsumptionW' => ValueType::FLOAT,
                'lengthM' => ValueType::FLOAT,
                'widthM' => ValueType::FLOAT,
                'heightM' => ValueType::FLOAT,
                'lifespan' => ValueType::INT,
            ];

            $partDao = new ComputerPartDAOImpl();

            // 入力に対する単純なバリデーション。実際のシナリオでは、要件を満たす完全なバリデーションが必要になることがあります。
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            if(isset($_POST['id'])) $validatedData['id'] = ValidationHelper::integer($_POST['id']);

            // 名前付き引数を持つ新しいComputerPartオブジェクトの作成＋アンパッキング
            $part = new ComputerPart(...$validatedData);

            error_log(json_encode($part->toArray(), JSON_PRETTY_PRINT));

            // 新しい部品情報でデータベースの更新を試みます。
            // 別の方法として、createOrUpdateを実行することもできます。
            if(isset($validatedData['id'])) $success = $partDao->update($part);
            else $success = $partDao->create($part);

            if (!$success) {
                throw new Exception('Database update failed!');
            }

            return new JSONRenderer(['status' => 'success', 'message' => 'Part updated successfully']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage()); // エラーログはPHPのログやstdoutから見ることができます。
            return new JSONRenderer(['status' => 'error', 'message' => 'Invalid data.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return new JSONRenderer(['status' => 'error', 'message' => 'An error occurred.']);
        }
    },
    'delete/part' => function(): HTMLRenderer {
        $part = null;
        $partDao = new ComputerPartDAOImpl();
        if(isset($_GET['id'])){
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->delete($id);
        }
        return new HTMLRenderer('component/delete-computer-part',['part'=>$part]);
    }
];