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
        
       //['part'=>$part]
        return new HTMLRenderer('component/list');
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



            /* 拡張子存在チェック */
            if(!empty($extension)){
                
                // /* 画像登録処理 */
                // $file_save = dirname(__FILE__, 2).'/'.'images/'; // アップロード対象のディレクトリを指定
                // //$file_path=dirname(__FILE__, 2).$file_save;
                // $file_tmp = $_FILES['file1']['tmp_name'];
                // $file_name = basename($_FILES['file1']['name']);
                // $file_save_path = dirname(__FILE__, 2) . '/images/' . $file_name; 
                // move_uploaded_file($file_tmp, $file_save_path); // アップロード処理
                // chmod($file_save_path,0664);
                

                //echo "success"; // jquery側にレスポンス
	
            } else {
                
                echo "fail"; // jquery側にレスポンス
                
            }

            // $hash_for_shared_url = hash('sha256', uniqid(mt_rand(), true));
            // $hash_for_delete_url = hash('sha256', uniqid(mt_rand(), true));
            // $shared_url = '/' . $extension . '/' . $hash_for_shared_url;
            // $delete_url = '/' .  'delete' . '/' . $hash_for_delete_url;
            $imagePathFromUploadDir = $subdirectory . '/' . $filename;
            $partDao = new ComputerPartDAOImpl();
        //$part = $partDao->insertImage($imagePathFromUploadDir,$_FILES['file1']['name'],$_FILES['file1']['type'],$_FILES['file1']['size']);
            //$result = DatabaseHelper::insertImage($imagePathFromUploadDir,$_FILES['file1']['name'],$_FILES['file1']['type'],$_FILES['file1']['size'],$shared_url,$delete_url );

            if (true) {//$result
                return new JSONRenderer(["success" => true,
                                       // "shared_url" => $shared_url, "delete_url"=> $delete_url
                                       ]);
            } else {
                return new JSONRenderer(["success" => false, "message" => "データベースの操作に失敗しました。"]);
            }
            
            //return new HTMLRenderer('register-result', ["url"=>$result["url"]]);
            //return new JSONRenderer(['result'=>json_encode($result['url'])]);
            //return $result;
        }catch(Exception $e){
            return new HTMLRenderer('register-result', []);

        }
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