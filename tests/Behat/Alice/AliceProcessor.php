<?php


namespace App\Tests\Behat\Alice;


use Fidry\AliceDataFixtures\ProcessorInterface;

class AliceProcessor implements ProcessorInterface
{
    private array $aliceFixtures = array();

    public function preProcess(string $id, $object): void
    {
        // TODO: Implement preProcess() method.
    }

    public function postProcess(string $id, $object): void
    {
        $this->addAliceFixture($id, $object);
    }

    /**
     */
    public function getAliceFixture($id) :Object
    {
        return $this->aliceFixtures[$id];
    }

    /**
     * @param array $aliceFixtures
     */
    public function setAliceFixtures(array $aliceFixtures): void
    {
        $this->aliceFixtures = $aliceFixtures;
    }

    public function addAliceFixture($id, $object)
    {
        $this->aliceFixtures[$id] = $object;
    }

    public function hasAliceFixture($id)
    {
        return isset($this->aliceFixtures[$id]);
    }
}