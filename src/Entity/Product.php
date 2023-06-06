<?php
namespace ScandiWebTask\Entity;

abstract class Product
{
    private string $sku;
    private string $name;
    private string $price;

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setParameters(array $parameters): void
    {
        $this->setSku((string) $parameters['sku']);
        $this->setName((string) $parameters['name']);
        $this->setPrice((float) $parameters['price']);
    }

    public function save(\PDO $pdo): void
    {
        $sql = 'INSERT INTO products (sku, name, price, type) VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), type = VALUES(type)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->getSku(), $this->getName(), $this->getPrice(), $this->getType()]);
    }
    public static function getBySku(\PDO $pdo, string $sku): ?Product
    {
        $sql = 'SELECT * FROM products WHERE sku = :sku';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':sku' => $sku]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $className = 'ScandiWebTask\\Entity\\' . $row['type'];
        /** @var Product $product */
        $product = new $className();
        $product->setParameters($row);

        return $product;
    }

    public static function getAll(\PDO $pdo): array
    {
        $sql = 'SELECT * FROM products';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function massDelete(\PDO $pdo, array $skuList): void
    {
        $skuListStr = implode(',', array_map([$pdo, 'quote'], $skuList));
        $sql = "DELETE FROM products WHERE sku IN ($skuListStr)";
        $pdo->exec($sql);
    }

    public static function mapProduct(array $row): self
    {
        $className = $row['type'];
        /** @var Product $product */
        $product = new $className();
        $product->setParameters($row);
        return $product;
    }

    public static function mapProducts(array $rows): array
    {
        return array_map(function (array $row) {
            return self::mapProduct($row);
        }, $rows);
    }

    public abstract function getType(): string;
}