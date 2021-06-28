<?php
namespace App\Tests\functional;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;
    public function testBasic() {
        $client = self::createClient();
        $this->createUserAndLogin($client, 'test@example.com', 'password');
        $this->assertResponseStatusCodeSame(200);
    }
    public function testUpdateUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'firstname@example.com', 'foo');

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
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($user->getId());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $user2 = $this->createUser('firstname2@example.com', 'foo');
        $client->request('PUT', '/api/users/'.$user2->getId(), [
            'json' => [
                'username' => 'newusername'
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);

        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
        $this->logIn($client, 'firstname@example.com', 'foo');
        $client->request('PUT', '/api/users/'.$user2->getId(), [
            'json' => [
                'username' => 'newusername'
            ]
        ]);

        $this->assertResponseIsSuccessful();

    }
    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUser( 'firstname@example.com', 'foo');
        $user->setPhoneNumber('555.123.4567');
        $this->createUserAndLogin($client, 'firstname2@example.com', 'foo');
        $em = $this->getEntityManager();
        $em->flush();
        $response = $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'username' => 'firstname'
        ]);
        $data = $response->toArray();
        $this->assertArrayNotHasKey('phoneNumber', $data);
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
        $this->logIn($client, 'firstname@example.com', 'foo');
        $client->request('GET', '/api/users/me');
        $this->assertJsonContains([
            'phoneNumber' => '555.123.4567',
            'isMe' => true
        ]);
    }
    public function testDeleteUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'firstname@example.com', 'foo');
        $user2 = $this->createUser('firstname2@example.com', 'foo');
        $client->request('DELETE', '/api/users/'.$user2->getId());
        $this->assertResponseStatusCodeSame(403);
        $em = $this->getEntityManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
        $client->request('DELETE', '/api/users/'.$user2->getId());
        $this->assertResponseIsSuccessful();
        $user = $em->getRepository(User::class)->find($user2->getId());
        //$this->assertNull($user);
    }
}