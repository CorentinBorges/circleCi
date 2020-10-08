<?php


namespace App\Validator\Voters;


use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanShowListVoter extends Voter
{
    const SHOWLIST = 'showList';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::SHOWLIST])) {
            return false;
        }

        if (!$subject instanceof Client) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $client=$token->getUser();

        if (!$client instanceof Client) {
            return false;
        }

        /**
         * @var Client $clientCalled
         */
        $clientCalled = $subject;

        if ($clientCalled->getId() !== $client->getId()) {
            throw new AccessDeniedHttpException(json_encode("You can not access to this user"));
        }

        return true;
    }

}