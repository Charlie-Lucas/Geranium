<?php
namespace App\DataPersister;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Product;

class ProductDataPersister implements DataPersisterInterface
{

    public function supports($data): bool
    {
        return $data instanceof Product;
    }

    /**
     * @param Product $data
     * @return object|void
     */
    public function persist($data)
    {
    }

    public function remove($data)
    {
    }
}
