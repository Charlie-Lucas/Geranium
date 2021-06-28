<?php
namespace App\Tests\functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

}