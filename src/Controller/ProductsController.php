<?php

namespace ScandiWebTask\Controller;

use Exception;
use ScandiWebTask\Entity\Product;
use ScandiWebTask\FormException;

class ProductsController extends BaseController
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
        $params = $this->getJsonBody();

        $existingEntity = Product::getBySku($this->pdo, $params['sku']);
        if ($existingEntity !== null) {
            throw new FormException('sku', 'A product with the same sku already exists');
        }

        $className = 'ScandiWebTask\\Entity\\' . $params['type'];
        /** @var Product $product */
        $product = new $className();
        $product->setParameters($params);
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