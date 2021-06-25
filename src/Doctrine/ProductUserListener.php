<?php
namespace App\Doctrine;

use App\Entity\Product;
use Symfony\Component\Security\Core\Security;

class ProductUserListener
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Product $product)
    {
        if ($product->getUser()) {
            return;
        }
        if ($this->security->getUser()) {
            $product->setUser($this->security->getUser());
        }
    }
}