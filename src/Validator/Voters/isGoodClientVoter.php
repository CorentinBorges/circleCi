<?php


namespace App\Validator\Voters;


use App\Entity\Client;
use App\Validator\Voters\Services\AccessDeniedJsonResponder;
use App\Validator\Voters\Services\NotFoundJsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            try{
                if ($subject==null) {
                    throw new NotFoundHttpException("Client not found");
                }
            } catch (NotFoundHttpException $exception) {
                NotFoundJsonResponse::build($exception);
            }
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
        $message = '';
        switch ($attribute){
            case self::SHOW_USERS_LIST:
                $message = "Those users are not yours, you can not access to them";
                break;
            case self::SHOW_CLIENT_DETAIL:
                $message = "You can just see your own details";
                break;
        }

        try {
            if ($client->isAdmin() || ($clientCalled->getId() !== $client->getId())) {
                throw new AccessDeniedHttpException($message);
            }
        } catch ( AccessDeniedHttpException $exception) {
            AccessDeniedJsonResponder::build($exception);
        }
        return true;

    }

}