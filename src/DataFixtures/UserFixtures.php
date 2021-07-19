<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            [
            'username'=> 'rh@humanbooster.com',
            'firstname'=> 'rh',
            'lastname'=> 'rh',
            'email'=> 'rh@humanbooster.com',
            'password'=> 'rh123@',
            'isRh'=> true  
            ],
            [
            'username'=> 'user',
            'firstname'=> 'user',
            'lastname'=> 'user',
            'email'=> 'user@user.com',
            'password'=> 'user',
            'isRh'=> false  
            ],
        ];

        foreach ($users as $user){
            $object = new User();
            $object->setUsername($user['username']);
            $object->setFirstname($user['firstname']);
            $object->setLastname($user['lastname']);
            $object->setEmail($user['email']);

            if($user['isRh']){
                $object->setRoles(['ROLE_RH']);
            }
            $object->setPassword($this->encoder->hashPassword($object, $user['password']));
            $manager->persist($object);
        }

        $manager->flush();
    }
}
