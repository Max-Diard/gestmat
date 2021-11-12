<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
//        if (!$user instanceof AppUser) {
//            return;
//        }

        if(in_array('ROLE_ADMIN', $user->getRoles(), true) || in_array('ROLE_TECH', $user->getRoles(), true)){
            return;
        }


        if(count($user->getAgence()) === 0) {
            throw new CustomUserMessageAccountStatusException('Vous n\'avez pas encore d\'agence associée à votre compte, merci de contacter l\'administrateur !');
        } else {
            $date = new \DateTime('now');
            $userAgence = $user->getAgence();
            if(count($userAgence) > 1){
                $test = 0;
                $diff = 0;
                while ($test != count($userAgence)){
                    foreach($userAgence as $agence){
                        if($date > $agence->getEndingAt()){
                            $diff++;
                        }
                        $test++;
                    }
                }
                if($diff === count($userAgence)){
                    throw new CustomUserMessageAccountStatusException('Les contrats de vos agences sont arrivé à terme, merci de contacter l\'administrateur !');
                } else{
                    return;
                }
            } else {
                foreach($userAgence as $agence){
                    if($date > $agence->getEndingAt()){
                        throw new CustomUserMessageAccountStatusException('Le contrat de l\'agence de ' . $agence->getName() . ' est arrivé à terme, merci de contacter l\'administrateur !');
                    }
                }
            }
        }
//        if ($user->isDeleted()) {
//            // the message passed to this exception is meant to be displayed to the user
//            throw new CustomUserMessageAccountStatusException('Your user account no longer exists.');
//        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
//        if ($user->isExpired()) {
//            throw new AccountExpiredException('...');
//        }
    }
}