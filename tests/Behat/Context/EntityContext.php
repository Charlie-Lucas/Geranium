<?php


namespace App\Tests\Behat\Context;


use App\Tests\Behat\Doctrine\EntityResolver;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class EntityContext implements Context
{
    private EntityManagerInterface $manager;
    private SchemaTool $schemaTool;
    private EntityResolver $entityResolver;

    public function __construct(
        EntityResolver $entityResolver,
        EntityManagerInterface $manager) {
        $this->entityResolver = $entityResolver;
        $this->manager = $manager;
        $this->schemaTool = new SchemaTool($this->manager);
    }

    /**
     * @BeforeScenario
     */
    public function createDatabase()
    {
        $classes = $this->manager->getMetadataFactory()->getAllMetadata();

        $this->schemaTool->dropSchema($classes);
        $this->schemaTool->createSchema($classes);

        $this->manager->clear();
    }
    /**
     * @Then /^should be (\d+) (.*) like:?$/
     */
    public function existLikeFollowing($nbr, $name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        for ($i = 0; $i < $nbr; $i++) {
            $row = $rows[$i % count($rows)];

            $values = array_map(array($this, 'clean'), array_combine($headers, $row));
            $object = $this->manager
                ->getRepository($entityName)
                ->findOneBy($values);

            if (is_null($object)) {
                throw new \Exception(sprintf("There is no object for the following criteria: %s", json_encode($values)));
            }
            $this->manager->refresh($object);
        }
    }
    private function resolveEntity($name)
    {

        $entities = $this
            ->entityResolver
            ->resolve(
                $this->manager,
                $name,
                empty($namespaces) ? '' : $namespaces
            )
        ;

        switch (true) {
            case 1 < count($entities):
                throw new \Exception(
                    sprintf(
                        'Failed to find a unique model from the name "%s", "%s" found',
                        $name,
                        implode('" and "', array_map(
                            function ($rfl) {
                                return $rfl->getName();
                            },
                            $entities
                        ))
                    )
                );
                break;
            case 0 === count($entities):
                throw new \Exception(
                    sprintf(
                        'Failed to find a model from the name "%s"',
                        $name
                    )
                );
        }

        return current($entities);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function clean($value)
    {
        return trim($value) === '' ? null : $value;
    }
}