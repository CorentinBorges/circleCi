<?php


namespace App\Validator\Voters;


use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class isGoodClientVoter extends Voter
{
    const SHOW_USERS_LIST = 'showUsersList';
    const SHOW_CLIENT_DETAIL='showClientDetail';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::SHOW_USERS_LIST, self::SHOW_CLIENT_DETAIL])) {
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

        if (!$client->isAdmin() || ($clientCalled->getId() !== $client->getId())) {
            return false;
        }
        return true;

    }

}