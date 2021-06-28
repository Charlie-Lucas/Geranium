<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Behat\Alice\AliceLoader;
use App\Tests\Behat\Alice\FilterFixtureBuilder;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister;
use Fidry\AliceDataFixtures\LoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Hautelook\AliceBundle\FixtureLocatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class AliceContext  implements Context
{
    private FixtureLocatorInterface $fixtureLocator;
    private KernelInterface $kernel;
    private EntityManagerInterface $manager;
    private LoaderInterface $loader;

    public function __construct(
        KernelInterface $kernel,
        FixtureLocatorInterface $fixtureLocator,
        LoaderInterface $purgeLoader,
        EntityManagerInterface $manager) {
        $this->fixtureLocator = $fixtureLocator;
        $this->kernel = $kernel;
        $this->loader = $purgeLoader;
        $this->manager = $manager;
    }
    /**
     * @BeforeScenario
     **/
    public function loadAlice(BeforeScenarioScope $scope)
    {
        $fixtures = $this->getTagContent('alice', $scope->getScenario()->getTags());
        if(count($fixtures)) {
            $this->load($fixtures);
        }elseif ($scope->getScenario()->hasTag('alice')) {
            $this->load(false);
        }
    }

    private function load($fixtures = false) {
        $bundles = $this->kernel->getBundles();
        $fixtureFiles = $this->fixtureLocator->locateFiles(array_values($bundles), 'test');
        $persister = new ObjectManagerPersister($this->manager);

        $loader = $this->loader->withPersister($persister);
        $parameters =$this->kernel->getContainer()->getParameterBag()->all();
        if($fixtures) {
            $parameters = array_merge($parameters, [FilterFixtureBuilder::FILTERED_FIXTURES_PARAM => $fixtures]);
        }
        $loader->load($fixtureFiles, $parameters, [], PurgeMode::createNoPurgeMode());
    }

    private function getTagContent($name, $tags)
    {
        $content = [];
        foreach ($tags as $tag) {
            $matches = [];
            if (preg_match(sprintf('/^%s\((.*)\)$/', $name), $tag, $matches)) {
                $content = array_merge($content, explode(',', end($matches)));
            }
        }
        return $content;
    }

}
