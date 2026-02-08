<?php

namespace Solidarity\User\Factory;

use Doctrine\ORM\EntityManager;
use Solidarity\Delegate\Entity\Delegate;
use Solidarity\School\Entity\School;
use Solidarity\User\Entity\User;

class UserFactory extends \Skeletor\User\Factory\UserFactory
{
    public static function compileEntityForUpdate($data, $entityManager)
    {
        $user = $entityManager->getRepository(\Solidarity\User\Entity\User::class)->find($data['id']);
        if (!$user->delegate && (int) $data['role'] === User::ROLE_DELEGATE) {
            $user->delegate = new Delegate();
        }
//        if (isset($data['tenantId'])) {
//            $tenant = $entityManager->getRepository(Tenant::class)->find($data['tenantId']);
//            unset($data['tenantId']);
//        }
//        if (isset($tenant)) {
//            $user->setTenant($tenant);
//        }
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->email = $data['email'];
        if(trim($data['password']) !== '') {
            $user->password = $data['password'];
        }
        $user->role = $data['role'];
        $user->isActive = $data['isActive'];
        $user->displayName = $data['displayName'];
        if ((int) $data['role'] === User::ROLE_DELEGATE && $data['delegate']['school']['id']) {
            $school = $entityManager->getRepository(School::class)->find($data['delegate']['school']['id']);
            $user->delegate->phone = $data['delegate']['phone'];
            $user->delegate->school = $school;
            $user->delegate->comment = $data['delegate']['comment'];
            $user->delegate->status = $data['delegate']['status'];
            $user->delegate->adminComment = $data['delegate']['adminComment'];
            $user->delegate->verifiedBy = $data['delegate']['verifiedBy'];
        }
        $entityManager->persist($user);

        return $user->getId();
    }

    public static function compileEntityForCreate($data, $entityManager)
    {
        $user = new \Solidarity\User\Entity\User();
//        $user->delegate = new Delegate();
//        if (isset($data['tenantId'])) {
//            $tenant = $entityManager->getRepository(Tenant::class)->find($data['tenantId']);
//            unset($data['tenantId']);
//        }
//        if (isset($tenant)) {
//            $user->setTenant($tenant);
//        }
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->isActive = $data['isActive'];
        $user->displayName = $data['displayName'];
        $entityManager->persist($user);
        $entityManager->flush();

        return $user->getId();
    }
}