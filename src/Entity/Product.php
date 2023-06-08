<?php
namespace ScandiWebTask\Entity;

abstract class Product
{
    const PRODUCT_TYPE_TO_CLASS_MAPPING = [
        'dvd' => DVDDisc::class,
        'book' => Book::class,
        'furniture' => Furniture::class
    ];
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

    public static function getBySku(\PDO $pdo, string $sku): ?array
    {
        $sql = 'SELECT * FROM products WHERE sku = :sku';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':sku' => $sku]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $row;
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

    public abstract function getType(): string;
}