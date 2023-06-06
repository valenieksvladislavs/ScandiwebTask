<?php
namespace ScandiWebTask\Entity;

class DVDDisc extends Product
{
    private int $size;

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function setParameters(array $parameters): void
    {
        parent::setParameters($parameters);

        $this->setSize((int) $parameters['size']);
    }

    public function save(\PDO $pdo): void
    {
        parent::save($pdo);

        $sql = "UPDATE products SET size = ? WHERE sku = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->getSize(), $this->getSku()]);
    }

    public function getType(): string
    {
        return 'DVDDisc';
    }
}