<?php

namespace Helpers;

use Types\ValueType;

class ValidationHelper
{//INF = 定義済み定数。無限大の値。
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        // PHPには、データを検証する組み込み関数があります。詳細は https://www.php.net/manual/en/filter.filters.validate.php を参照ください。
        //変数を特定のフィルタでフィルタリングし、フィルタリングされたデータを返すか、または指定されたフィルタリングオプションによって変数が無効と判断された場合はfalseを返します。
        //第一引数はフィルタする対象の値。第二引数は適用するフィルタのID。第三引数はオプションmin_range および max_range は、filter_var 関数の FILTER_VALIDATE_INT フィルタオプションとして予め定義されているキーです。PHP の filter_var 関数はこれらのキーを認識して、渡された変数が指定された範囲内の整数であるかを検証します。
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range"=>(int) $max]);

        // 結果がfalseの場合、フィルターは失敗したことになります。
        if ($value === false) throw new \InvalidArgumentException("The provided value is not a valid integer.");

        // 値がすべてのチェックをパスしたら、そのまま返します。
        return $value;
    }

    public static function validateDate(string $date, string $format = 'Y-m-d'): string
    {
        $d = \DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date) {
            return $date;
        }

        throw new \InvalidArgumentException(sprintf("Invalid date format for %s. Required format: %s", $date, $format));
    }

    public static function validateFields(array $fields, array $data): array
    {
        $validatedData = [];

        foreach ($fields as $field => $type) {
            if (!isset($data[$field]) || ($data)[$field] === '') {
                throw new \InvalidArgumentException("Missing field: $field");
            }

            $value = $data[$field];

            $validatedValue = match ($type) {
                ValueType::STRING => is_string($value) ? $value : throw new \InvalidArgumentException("The provided value is not a valid string."),
                ValueType::INT => self::integer($value), // You can further customize this method if needed
                ValueType::FLOAT => filter_var($value, FILTER_VALIDATE_FLOAT),
                ValueType::DATE => self::validateDate($value),
                default => throw new \InvalidArgumentException(sprintf("Invalid type for field: %s, with type %s", $field, $type)),
            };

            if ($validatedValue === false) {
                throw new \InvalidArgumentException(sprintf("Invalid value for field: %s", $field));
            }

            $validatedData[$field] = $validatedValue;
        }

        return $validatedData;
    }
}