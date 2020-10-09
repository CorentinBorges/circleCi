<?php


namespace App\Validator\Voters;


use App\Entity\Client;
use App\Entity\User;
use App\Validator\Voters\Services\AccessDeniedJsonResponder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IsGoodClientForUserVoter extends Voter
{
    const DELETE = 'delete';
    const EDIT = 'edit';
    const SHOW = 'show';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT, self::SHOW])) {
            return false;
        }

        if (!$subject instanceof User) {
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
         * @var User $user
         */
        $user = $subject;
        $message = '';
        switch ($attribute){
            case self::DELETE:
                $message = "You can not delete this user";
                break;
            case self::EDIT:
                $message = "You can not edit this user";
                break;
            case self::SHOW:
                $message = "You can not see details on this user";
        }

        try {
            if ($user->getClient()->getId() !== $client->getId()) {
                throw new AccessDeniedHttpException($message);
            }
        } catch ( AccessDeniedHttpException $exception) {
            AccessDeniedJsonResponder::build($exception);
        }

        return true;
    }
}