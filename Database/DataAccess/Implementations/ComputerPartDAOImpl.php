<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\ComputerPartDAO;
use Database\DatabaseManager;
use Models\ComputerPart;
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

    public static function insertImage($file_path,$file_name,$mine_type,$size): array {
        
        
        $mysqli = DatabaseManager::getMysqliConnection();

        $stmt = $mysqli->prepare("INSERT INTO images (title,file_path,file_name,url,delete_url,view_count,mine_type,expired_date,size) VALUES(?,?,?,?,?,?,?,?,?);");
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 16;
        $random_string = str_shuffle($characters);
        $random_string = substr($random_string, 0, $length);

        $random_string2 = str_shuffle($characters);
        $random_string2 = substr($random_string, 0, $length);
        $title='タイトル';
        $date=date("Y/m/d H:i:s", strtotime("1 month"));
        $view_count = 0; // ビューカウントの初期値
        // バインドパラメータ
        $stmt->bind_param("sssssssss", $title,$file_path,$file_name,$shared_url, $delete_url,$view_count,$mine_type,$date,$size);
        $stmt->execute();


        $stmt = $mysqli->prepare("SELECT * FROM images ORDER BY uploaded_at DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $snippet = $result->fetch_assoc();

        return $snippet;
    }

}