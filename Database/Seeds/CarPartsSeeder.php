<?php
namespace Database\Seeds;


use Database\AbstractSeeder;

use Carbon\Carbon;
use Faker;
require_once 'vendor/autoload.php';
class CarPartsSeeder extends AbstractSeeder {

    // TODO: tableName文字列を割り当ててください。
    protected ?string $tableName = 'CarPart';

    // TODO: tableColumns配列を割り当ててください。
    protected array $tableColumns = [
        [
            'data_type' => 'int',
            'column_name' => 'carId'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'partID'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'description'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'price'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'quantityStock'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'created_at'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'updated_at'
        ]
    ];

    public function createRowData(): array
    {
        // TODO: createRowData()メソッドを実装してください。
        $faker = Faker\Factory::create('ja_JP');
        $data = [];
        for($i=0; $i<1000; $i++){
            
            $data[]=[
                $i,
                $i%100,
                $faker->name,
                $faker->name,
                $faker->randomFloat,
                $faker->randomDigit,
                Carbon::now()->toDateTimeString(),
                Carbon::now()->toDateTimeString()
            ];

        }

        return $data;


    }
}
