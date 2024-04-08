<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ComputerPartDAO;
use Database\DatabaseManager;
use Models\ComputerPart;
use Models\Post;
use Models\DataTimeStamp;

class ComputerPartDAOImpl implements ComputerPartDAO
{
    public function create(ComputerPart $partData): bool
    {
        if($partData->getId() !== null) throw new \Exception('Cannot create a computer part with an existing ID. id: ' . $partData->getId());
        return $this->createOrUpdate($partData);
    }

    public function getById(int $id): ?ComputerPart
    {
        $mysqli = DatabaseManager::getMysqliConnection();
        $computerPart = $mysqli->prepareAndFetchAll("SELECT * FROM computer_parts WHERE id = ?",'i',[$id])[0]??null;

        return $computerPart === null ? null : $this->resultToComputerPart($computerPart);
    }

    public function update(ComputerPart $partData): bool
    {
        if($partData->getId() === null) throw new \Exception('Computer part specified has no ID.');

        $current = $this->getById($partData->getId());
        if($current === null) throw new \Exception(sprintf("Computer part %s does not exist.", $partData->getId()));

        return $this->createOrUpdate($partData);
    }

    public function delete(int $id): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();
        return $mysqli->prepareAndExecute("DELETE FROM computer_parts WHERE id = ?", 'i', [$id]);
    }

    public function getRandom(): ?ComputerPart
    {   //mysqliのインスタンスを生成して受け取る（MySQLWrapperクラス）
        $mysqli = DatabaseManager::getMysqliConnection();
        $computerPart = $mysqli->prepareAndFetchAll("SELECT * FROM computer_parts ORDER BY RAND() LIMIT 1",'',[])[0]??null;

        return $computerPart === null ? null : $this->resultToComputerPart($computerPart);
    }

    public function getAll(int $offset, int $limit): array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM computer_parts LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

        return $results === null ? [] : $this->resultsToComputerParts($results);
    }

    public function getAllByType(string $type, int $offset, int $limit): array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM computer_parts WHERE type = ? LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'sii', [$type, $offset, $limit]);
        return $results === null ? [] : $this->resultsToComputerParts($results);
    }

    public function createOrUpdate(ComputerPart $partData): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query =
        <<<SQL
            INSERT INTO computer_parts (id, name, type, brand, model_number, release_date, description, performance_score, market_price, rsm, power_consumptionw, lengthm, widthm, heightm, lifespan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE id = ?,
            name = VALUES(name),
            type = VALUES(type),
            brand = VALUES(brand),
            model_number = VALUES(model_number),
            release_date = VALUES(release_date),
            description = VALUES(description),
            performance_score = VALUES(performance_score),
            market_price = VALUES(market_price),
            rsm = VALUES(rsm),
            power_consumptionw = VALUES(power_consumptionw),
            lengthm = VALUES(lengthm),
            widthm = VALUES(widthm),
            heightm = VALUES(heightm),
            lifespan = VALUES(lifespan);
        SQL;

        $result = $mysqli->prepareAndExecute(
            $query,
            'issssssidddddddi',
            [
                $partData->getId(), // on null ID, mysql will use auto-increment.
                $partData->getName(),
                $partData->getType(),
                $partData->getBrand(),
                $partData->getModelNumber(),
                $partData->getReleaseDate(),
                $partData->getDescription(),
                $partData->getPerformanceScore(),
                $partData->getMarketPrice(),
                $partData->getRsm(),
                $partData->getPowerConsumptionW(),
                $partData->getLengthM(),
                $partData->getWidthM(),
                $partData->getHeightM(),
                $partData->getLifespan(),
                $partData->getId()
            ],
        );

        if(!$result) return false;

        // insert_id returns the last inserted ID.
        if($partData->getId() === null){
            $partData->setId($mysqli->insert_id);
            $timeStamp = $partData->getTimeStamp()??new DataTimeStamp(date('Y-m-h'), date('Y-m-h'));
            $partData->setTimeStamp($timeStamp);
        }

        return true;
    }

    private function resultToComputerPart(array $data): ComputerPart{
        return new ComputerPart(
            name: $data['name'],
            type: $data['type'],
            brand: $data['brand'],
            id: $data['id'],
            modelNumber: $data['model_number'],
            releaseDate: $data['release_date'],
            description: $data['description'],
            performanceScore: $data['performance_score'],
            marketPrice: $data['market_price'],
            rsm: $data['rsm'],
            powerConsumptionW: $data['power_consumptionw'],
            lengthM: $data['lengthm'],
            widthM: $data['widthm'],
            heightM: $data['heightm'],
            lifespan: $data['lifespan'],
            timeStamp: new DataTimeStamp($data['created_at'], $data['updated_at'])
        );
    }

    private function resultsToComputerParts(array $results): array{
        $computerParts = [];

        foreach($results as $result){
            $computerParts[] = $this->resultToComputerPart($result);
        }

        return $computerParts;
    }






    public function getAllPost(int $offset, int $limit): array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM Posts LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

        return $results === null ? [] : $this->resultsToPosts($results);
    }

    public function insertImage($file_path,$file_name,$mime_type,$size,$shared_url) {
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO Posts (reply_to_id,subject,content,file_path,file_name,mime_type,size,url) VALUES(?,?,?,?,?,?,?,?);";
        
        $title='タイトル';
        $content='コンテンツコンテンツコンテンツコンテンツ';
        // バインドパラメータ
        $results = $mysqli->prepareAndExecute($query, 'isssssis', [null, $title,$content,$file_path,$file_name,$mime_type,$size,$shared_url]);
        
        // $query = "SELECT * FROM Posts LIMIT ?,?";
        // $posts = $mysqli->prepareAndFetchAll($query,'ii',[0,9]);

        return $results;
        
    }

    public function insertReply($reply_to_id,$replyContent,$file_path,$file_name,$mime_type,$size) {
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "INSERT INTO Posts (reply_to_id,content,file_path,file_name,mime_type,size) VALUES(?,?,?,?,?,?);";
        
        // バインドパラメータ
        $results = $mysqli->prepareAndExecute($query, 'issssi', [$reply_to_id,$replyContent,$file_path,$file_name,$mime_type,$size]);
        
        
        return $reply_to_id;
    }

    public function getPostData($url): array {
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM Posts WHERE url = ?";
        $posts = $mysqli->prepareAndFetchAll($query,'s',[$url]);

        return $posts === null ? null : $this->resultsToPosts($posts);
        
    }

    public function getPostDataByPath($file_path): array {
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM Posts WHERE file_path = ?";
        $posts = $mysqli->prepareAndFetchAll($query,'s',[$file_path]);

        return $posts;
        
    }

    public function getPostDataByReplyToId($reply_to_id): array {
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM Posts WHERE reply_to_id = ?";
        $posts = $mysqli->prepareAndFetchAll($query,'s',[$reply_to_id]);

        return $posts;
        
    }

    private function resultToPost(array $data): Post{
        return new Post(
            reply_to_id: $data['reply_to_id'],
            subject: $data['subject'],
            content: $data['content'],
            file_path: $data['file_path'],
            file_name:$data['file_name'],
            mime_type:$data['mime_type'],
            size:$data['size'],
            url:$data['url'],
            timeStamp: new DataTimeStamp($data['created_at'], $data['updated_at'])
           
        );
    }

    private function resultsToPosts(array $results): array{
        $Posts = [];

        foreach($results as $Post){
            $Posts[] = $this->resultToPost($Post);
        }

        return $Posts;
    }

}