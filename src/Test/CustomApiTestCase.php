<?php
namespace App\Test;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;

class CustomApiTestCase extends \ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase
{
    protected function createUser(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setFirstname(strtolower(substr($email, 0, strpos($email, '@'))));
        $user->setLastname(strtoupper(substr($email, 0, strpos($email, '@'))));
        $user->setPassword(static::getContainer()->get('security.password_hasher')->hashPassword($user, $password));
        $user->setUsername($user->getFirstname());
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    protected function logIn(Client $client, string $email, string $password)
    {
        $this->authenticateClient($client, $email, $password);
        $this->assertResponseStatusCodeSame(200);
    }
    public function createUserAndLogin(Client $client, string $email, string $password): User
    {
        $user = $this->createUser($email, $password);
        $this->logIn($client, $email, $password);

        return $user;
    }
    protected function getEntityManager() {
        return static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    protected function authenticateClient(Client $client, $email = 'user', $password = 'password')
    {
        $data = $client->request(
            'POST',
            '/api/login',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]
        )->toArray();

        $client->setDefaultOptions(['headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $data['token'])
        ]]);
    }
}