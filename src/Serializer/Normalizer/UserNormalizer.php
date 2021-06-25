<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

class UserNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';
    use NormalizerAwareTrait;
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;
        if ($isOwner = $this->userIsOwner($object)) {
            $context['groups'][] = 'owner:read';
        }
        $data = $this->normalizer->normalize($object, $format, $context);
        $data['isMe'] = $isOwner;
        // Here: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof User;
    }

    private function userIsOwner(User $user): bool
    {
        $authenticatedUser = $this->security->getUser();
        if (!$authenticatedUser) {
            return false;
        }
        /** @var User $authenticatedUser */
        return $authenticatedUser->getEmail() === $user->getEmail();
    }
}
