<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    private Security $authorizationChecker;

    public function __construct(Security $authorizationChecker) {
        $this->authorizationChecker = $authorizationChecker;
    }
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'DELETE':
                return $this->authorizationChecker->isGranted('ROLE_ADMIN');
                break;
        }

        return false;
    }
}
