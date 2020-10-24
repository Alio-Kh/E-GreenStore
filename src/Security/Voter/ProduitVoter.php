<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProduitVoter extends Voter
{
    protected function supports($attribute, $produit)
    {

        return in_array($attribute, ['EDIT', 'SHOW', 'delete'])
            && $produit instanceof \App\Entity\Produit;
    }

    protected function voteOnAttribute($attribute, $produit, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'EDIT':
                if ($user->getAgriculteur()->getId() == $produit->getMarket()->getAgriculteur()->getId()) {
                    return true;
                }
                break;
            case 'SHOW':
                if ($user->getAgriculteur()->getId() == $produit->getMarket()->getAgriculteur()->getId()) {
                    return true;
                }
                break;
            case 'delete':
                if ($user->getAgriculteur()->getId() == $produit->getMarket()->getAgriculteur()->getId()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
