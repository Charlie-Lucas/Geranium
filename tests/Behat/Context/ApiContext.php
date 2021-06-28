<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Behat\Alice\AliceProcessor;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class ApiContext implements Context
{
    /** @var KernelBrowser */
    private KernelBrowser $browser;

    private Response $response;

    private ?string $authorization = null;
    private array $placeHolders = array();
    private AliceProcessor $aliceProcessor;
    private PropertyAccessor $propertyAccessor;


    public function __construct(KernelBrowser $browser, AliceProcessor $aliceProcessor)
    {
        $this->browser = $browser;
        $this->aliceProcessor = $aliceProcessor;
        $this->propertyAccessor =  PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    /**
     * Adds Basic Authentication header to next request.
     *
     * @param string $email
     * @param string $password
     *
     * @Given /^I am authenticating as "([^"]*)" with "([^"]*)" password$/
     * @throws Exception
     */
    public function iAmAuthenticatingAs(string $email, string $password)
    {
        $this->setAuthorization(null);
        $this->request('/api/login', 'GET', [
            'email' => $email,
            'password' => $password
        ]);
        $json = json_decode($this->getResponse()->getContent(), true);
        $this->setAuthorization($json['token']);
    }

    /**
     * @When a demo scenario sends a request to :path
     * @throws Exception
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        //$this->response = $this->browser->request($path, 'GET');
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        /*
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }*/
    }


    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a get request to "([^"]+)"$/
     */
    public function iSendARequest(string $url)
    {
        $this->request($url, 'GET');
    }

    /**
     * Sends HTTP request to specific URL with form data from PyString.
     *
     * @param string $method request method
     * @param string $url    relative url
     * @param TableNode    $table  formData
     *
     * @When /^(?:I )?send a "(POST|PUT|DELETE)" request to "([^"]+)" with:?$/
     */
    public function iSendARequestWithFormData(string $method, string $url, TableNode $table)
    {
        $rows = $table->getRows();
        if(count($rows) !== 2) throw new \Exception('Form Data invalid');
        $formDataKeys = array_shift($rows);
        $formDataValues = array_shift($rows);
        $values = array_combine($formDataKeys, $formDataValues);
        $this->request($url, $method, $values);
    }

    /**
     * Checks that response body contains data.
     *
     * @param TableNode    $table  responseData
     *
     * @Then response should contains
     */
    public function responseShouldContains(TableNode $table)
    {
        $rows = $table->getRows();
        $expectedKeys = array_shift($rows);
        $actual = json_decode($this->getResponse()->getContent(), true);
        foreach ($expectedKeys as $expectedKey){
            Assert::assertArrayHasKey($expectedKey, $actual,
                sprintf("Actual response keys are %s, but key %s not found", implode(', ', array_keys($actual)), $expectedKey));
        }
        if(isset($rows[0]))
        {
            $values = array_combine($expectedKeys, $rows[0]);
            foreach ($values as $key => $value) {
                Assert::assertEquals($actual[$key], $value,
                    sprintf("Actual response value for key %s is %s, but value is %s",
                        $key, $actual[$key], $value));
            }
        }

    }
    /**
     * Checks that response body contains data.
     *
     * @param TableNode    $table  responseData
     *
     * @Then response should not contains
     */
    public function responseShouldNotContains(TableNode $table)
    {
        $rows = $table->getRows();
        $expectedKeys = array_shift($rows);
        $actual = json_decode($this->getResponse()->getContent(), true);
        foreach ($expectedKeys as $expectedKey){
            Assert::assertArrayNotHasKey($expectedKey, $actual,
                sprintf("Actual response keys are %s, but key %s found", implode(', ', array_keys($actual)), $expectedKey));
        }
    }

    /**
     * Sets place holder for replacement.
     *
     * you can specify placeholders, which will
     * be replaced in URL, request or response body.
     *
     * @param string $key   token name
     * @param string $value replace value
     */
    public function setPlaceHolder($key, $value)
    {
        $this->placeHolders[$key] = $value;
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    public function replacePlaceHolder($string)
    {

        if (preg_match('/(?<=\{).+?(?=\})/', $string, $matches)) {
            foreach ($matches as $toReplace) {
                $valueToFind = explode('.', $toReplace);
                if($this->aliceProcessor->hasAliceFixture($valueToFind[0])){
                    $obj = $this->aliceProcessor->getAliceFixture($valueToFind[0]);
                    $string = str_replace('{'.$toReplace .'}', $this->propertyAccessor->getValue($obj, $valueToFind[1]), $string);
                } else {
                    throw new \Exception(sprintf('Cannot find used fixture %s', $valueToFind[0]));
                }
            }
        }
        return $string;
    }

    /**
     * @return string|null
     */
    public function getAuthorization(): ?string
    {
        return $this->authorization;
    }

    /**
     * @param string|null $authorization
     */
    public function setAuthorization(?string $authorization): void
    {
        $this->authorization = $authorization;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $formData
     * @throws Exception
     */
    private function request(string $url, string $method, array $formData = array()) {

        $url = '/' . ltrim($this->replacePlaceHolder($url), '/');

        if($this->getAuthorization())
        {
            $this->browser->setServerParameter('HTTP_Authorization', 'Bearer '.$this->getAuthorization());
        }

        $this->browser->jsonRequest($method, $url, $formData);
        $this->setResponse(
            $this->browser->getResponse()
        );

    }
}