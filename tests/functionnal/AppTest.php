<?php


class AppTest extends \ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase
{
    public function testBasic() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(200);
    }
}