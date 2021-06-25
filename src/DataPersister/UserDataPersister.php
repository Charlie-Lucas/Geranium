<?php
namespace App\DataPersister;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private DataPersisterInterface $decorated;
    private UserPasswordHasherInterface $userPasswordHasher;
    private LoggerInterface $logger;
    private Security $security;

    public function __construct(DataPersisterInterface  $decorated, UserPasswordHasherInterface $userPasswordHasher, LoggerInterface $logger, Security $security)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->decorated = $decorated;
        $this->logger = $logger;
        $this->security = $security;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        // using original data
        //$originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($data);
        if (!$data->getId()) {
            $this->logger->info(sprintf('User %s just registered! Eureka!', $data->getEmail()));
        }
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->logger->info(sprintf('User "%s" is being updated!', $data->getId()));
        }
        if($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        $data->setIsMe($this->security->getUser() === $data);
        $this->decorated->persist($data);
    }
    public function remove($data, array $context = [])
    {
        $this->decorated->remove($data);
    }
}