<?php

namespace ScandiWebTask\Controller;

use Exception;
use ScandiWebTask\Entity\Book;
use ScandiWebTask\Entity\DVDDisc;
use ScandiWebTask\Entity\Furniture;
use ScandiWebTask\Entity\Product;
use ScandiWebTask\FormException;
use ScandiWebTask\Validator\ProductValidator;

class ProductController extends BaseController
{
    public function actionIndex(): string
    {
        return $this->list();
    }

    public function actionList(): string
    {
        return $this->list();
    }

    private function list(): string
    {
        $products = Product::getAll($this->pdo);
        return $this->render('product-list.php', ['title' => 'Product list', 'products' => $products]);
    }

    public function actionGet(): string
    {
        $product = Product::getBySku($this->pdo, $_GET['sku'] ?? '');

        if (!$product) {
            return $this->actionNotFound();
        }

        return json_encode($product);
    }

    public function actionSaveNew(): string
    {
        return $this->render('add-product.html', ['title' => 'Add new product']);
    }

    public function actionSaveApi(): string
    {
        $input = $this->getJsonBody();

        $validator = new ProductValidator();
        if (!$validator->validate($this->pdo, $input)) {
            $validator->handleErrors();
        }

        $className = Product::PRODUCT_TYPE_TO_CLASS_MAPPING[$input['productType']];

        /** @var Product $product */
        $product = new $className();
        $product->setParameters($input);
        $product->save($this->pdo);

        return 'ok';
    }

    public function actionDeleteMassApi(): string
    {
        $skuList = $this->getJsonBody();
        if (count($skuList) > 0) {
            Product::massDelete($this->pdo, $skuList);
        }

        return 'ok';
    }
}