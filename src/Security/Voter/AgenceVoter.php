<?php

namespace App\Security\Voter;

use App\Entity\Agence;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class AgenceVoter extends Voter
{
    const AGENCE_EDIT = 'agence_edit';
    const AGENCE_DELETE = 'agence_delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $agence): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::AGENCE_EDIT, self::AGENCE_DELETE])
            && $agence instanceof \App\Entity\Agence;
    }

    protected function voteOnAttribute(string $attribute, $agence, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // //On vérifie si l'utilisateur est admin 
        if ($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }
        
        // On vérifie si l'agence à un user
        if(null === $agence->getUsers()) return false;
        
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::AGENCE_EDIT:
                //on vérifier si on peut l'éditer
                return $this->canEDIT($user, $agence);
                break;
            case self::AGENCE_DELETE:
                //on vérifier si on peut l'éditer
                return $this->canDELETE($user, $agence);
                break;
        }

        return false;
    }

    private function canEDIT( User $user, Agence $agence){
        //L'utilisateur de l'agence peut la modifier
        return $user === $agence->getUsers();
        
    }

    private function canDELETE( User $user, Agence $agence){
        //L'utilisateur de l'agence peut la supprimer
        return $user === $agence->getUsers();
    }

}
