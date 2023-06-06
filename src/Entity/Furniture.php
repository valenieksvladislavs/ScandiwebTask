<?php
namespace ScandiWebTask\Entity;

class Furniture extends Product
{
    private int $height;
    private int $length;
    private int $width;

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    public function setParameters(array $parameters): void
    {
        parent::setParameters($parameters);

        $this->setHeight((int) $parameters['height']);
        $this->setLength((int) $parameters['length']);
        $this->setWidth((int) $parameters['width']);
    }

    public function save(\PDO $pdo): void
    {
        parent::save($pdo);

        $sql = "UPDATE products SET height = ?, length = ?, width = ? WHERE sku = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->getHeight(), $this->getLength(), $this->getWidth(), $this->getSku()]);
    }

    public function getType(): string
    {
        return 'Furniture';
    }
}