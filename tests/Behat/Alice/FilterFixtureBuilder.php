<?php


namespace App\Tests\Behat\Alice;


use Nelmio\Alice\FixtureBuilderInterface;
use Nelmio\Alice\FixtureSet;

final class FilterFixtureBuilder implements FixtureBuilderInterface
{
    private FixtureBuilderInterface $decoratedFixtureBuilder;
    const FILTERED_FIXTURES_PARAM = "app.filtered_fixtures";

    public function __construct(FixtureBuilderInterface $decoratedFixtureBuilder) {
        $this->decoratedFixtureBuilder = $decoratedFixtureBuilder;
    }

    /**
     * @throws \Exception
     */
    public function build(array $data, array $parameters = [], array $objects = []): FixtureSet
    {
        if(array_key_exists(self::FILTERED_FIXTURES_PARAM, $parameters)) {
            $newFixtures = [];
            $filteredFixtures = $parameters[self::FILTERED_FIXTURES_PARAM];
            foreach ($filteredFixtures as $filteredFixture) {
                foreach ($data as $class => $fixtures) {
                    if(array_key_exists($filteredFixture, $fixtures)) {
                        $newFixtures[$class][$filteredFixture] = $fixtures[$filteredFixture];
                        continue;
                    }
                    throw new \Exception(sprintf ('Fixture %s not found', $filteredFixture));
                }
            }
            $data = $newFixtures;
        }
        return $this->decoratedFixtureBuilder->build($data, $parameters, $objects);
    }
}