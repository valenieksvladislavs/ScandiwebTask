<?php
namespace ScandiWebTask\Entity;

class Book extends Product
{
    private float $weight;

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function setParameters(array $parameters): void
    {
        parent::setParameters($parameters);

        $this->setWeight((float) $parameters['weight']);
    }

    public function save(\PDO $pdo): void
    {
        parent::save($pdo);

        $sql = "UPDATE products SET weight = ? WHERE sku = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->getWeight(), $this->getSku()]);
    }

    public function getType(): string
    {
        return 'Book';
    }
}