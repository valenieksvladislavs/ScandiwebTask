<?php

namespace ScandiWebTask\Validator;

use ScandiWebTask\Entity\Product;

class ProductValidator extends BaseValidator
{
    public function validate(\PDO $pdo, array $input): bool
    {
        if (empty($input['sku'])) {
            $this->errors[] = ['key' => 'sku', 'message' => 'SKU is required'];
        } else {
            $existingEntity = Product::getBySku($pdo, $input['sku']);
            if ($existingEntity !== null) {
                $this->errors[] = ['key' => 'sku', 'message' => 'A product with the same sku already exists'];
            }
        }

        if (empty($input['name'])) {
            $this->errors[] = ['key' => 'name', 'message' => 'Name is required'];
        }

        if (empty($input['price'])) {
            $this->errors[] = ['key' => 'price', 'message' => 'Price is required'];
        } elseif (!is_numeric($input['price'])) {
            $this->errors[] = ['key' => 'price', 'message' => 'Price must be a number'];
        }

        $checkForNumeric = function ($value, $fieldName) {
            if (empty($value)) return "$fieldName is required";
            else if (!is_numeric($value)) return "$fieldName must be a number";
            return false;
        };

        $checkForInteger = function ($value, $fieldName) {
            if (empty($value)) return "$fieldName is required";
            else if (!is_numeric($value) || !ctype_digit($value)) return "$fieldName must be an integer";
            return false;
        };

        $typeToRulesMapping = [
            'book' => [
                'weight' => function($value) use ($checkForNumeric) { return $checkForNumeric($value, 'Weight'); }
            ],
            'dvd' => [
                'size' => function($value) use ($checkForInteger) { return $checkForInteger($value, 'Size'); }
            ],
            'furniture' => [
                'height' => function($value) use ($checkForInteger) { return $checkForInteger($value, 'Height'); },
                'length' => function($value) use ($checkForInteger) { return $checkForInteger($value, 'Length'); },
                'width' => function($value) use ($checkForInteger) { return $checkForInteger($value, 'Width'); }
            ]
        ];

        if (empty($input['productType'])) {
            $this->errors[] = ['key' => 'productType', 'message' => 'Product type is required'];
        } elseif (!Product::PRODUCT_TYPE_TO_CLASS_MAPPING[$input['productType']]) {
            $this->errors[] = ['key' => 'productType', 'message' => 'Invalid product type'];
        } else {
            foreach ($typeToRulesMapping[$input['productType']] as $key => $function) {
                $value = $input[$key];
                if ($message = $function($value)) {
                    $this->errors[] = ['key' => $key, 'message' => $message];
                }
            }
        }

        return count($this->errors) === 0;
    }
}