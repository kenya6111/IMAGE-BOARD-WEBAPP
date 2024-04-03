<?php
    
namespace Database\Seeds;

use Database\AbstractSeeder;
use Carbon\Carbon;
use Faker;
require_once 'vendor/autoload.php';
class CarasSeeder extends AbstractSeeder {

    // TODO: tableName文字列を割り当ててください。
    protected ?string $tableName = 'cars';

    // TODO: tableColumns配列を割り当ててください。
    protected array $tableColumns = [
        [
            'data_type' => 'int',
            'column_name' => 'id'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'make'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'model'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'year'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'color'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'price'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'mileage'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'transmission'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'engine'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'status'
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
        for($i=0; $i<100; $i++){
            $data[]=[
                $i,
                $faker->name,
                $faker->name,
                $faker->randomDigit,
                $faker->name,
                $faker->randomFloat,
                $faker->randomFloat,
                $faker->name,
                $faker->name,
                $faker->name,
                Carbon::now()->toDateTimeString(),
                Carbon::now()->toDateTimeString()
            ];

        }

        return $data;

    }
}