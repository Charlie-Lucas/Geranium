<?php

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;
    /*
    public function testUpdateUser()
    {
        $client = self::createClient();
        $client->request('PUT', '/api/products/'.$user->getId(), [
            'json' => [
                'roles' => ['ROLE_ADMIN'] // will be ignored
            ]
        ]);

        $user = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'username' => 'newusername'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'newusername'
        ]);
        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'roles' => ['ROLE_ADMIN'] // will be ignored
            ]
        ]);
        $em = $this->getEntityManager();
        /** @var User $user
        $user = $em->getRepository(User::class)->find($user->getId());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }
    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUser( 'cheeseplease@example.com', 'foo');
        $this->createUserAndLogIn($client, 'cheeseplease2@example.com', 'foo');
        $user->setPhoneNumber('555.123.4567');
        $em = $this->getEntityManager();
        $em->flush();
        $response = $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'username' => 'cheeseplease'
        ]);
        $data = $response->toArray();
        $this->assertArrayNotHasKey('phoneNumber', $data);
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
        $this->logIn($client, 'cheeseplease@example.com', 'foo');
        $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'phoneNumber' => '555.123.4567',
            'isMe' => true
        ]);
    }*/
}