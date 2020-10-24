<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MarketVoter extends Voter
{
    protected function supports($attribute, $market)
    {
        return in_array($attribute, ['EDIT', 'SHOW', 'ADD','ADDP'])
            && $market instanceof \App\Entity\Market;
    }

    protected function voteOnAttribute($attribute, $market, TokenInterface $token)
    {
        $user = $token->getUser();
         
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'EDIT':
                if ($user->getAgriculteur()->getId() == $market->getAgriculteur()->getId()) {
                    return true;
                } 
                break;
            case 'SHOW':
                if ($user->getAgriculteur()->getId() == $market->getAgriculteur()->getId()) {
                    return true;
                }
            break;
            case 'ADD':
                if ( count($user->getAgriculteur()->getMarkets())<3) {
                    return true;
                }
                break;
                case 'ADDP':
                    if ( $user->getAgriculteur()->getId() == $market->getAgriculteur()->getId()) {
                        return true;
                    }
                    break;
            
            
        }

        return false;
    }
}
