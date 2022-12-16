<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->hasher = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user->setEmail("Admin_01@gmail.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPhone("0387451999");
        $user->setPassword($this->hasher->hashPassword($user,"123456"));
        $user->setFullname("Nguyen Thanh Giang(admin)");
        $user->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user->setGender("Male");
        $user->setImage("admin_Giang.jpg");
        $user->setIsVerified(true);
        $manager->persist($user);
        
        $user2 = new User;
        $user2->setEmail("Admin_02@gmail.com");
        $user2->setRoles(["ROLE_ADMIN"]);
        $user2->setPhone("0387451999");
        $user2->setPassword($this->hasher->hashPassword($user,"123456"));
        $user2->setFullname("Nguyen Thanh Giang(admin)");
        $user2->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user2->setGender("Male");
        $user2->setImage("admin_Giang.jpg");
        $user2->setIsVerified(true);
        $manager->persist($user2);
        
        $user3 = new User;
        $user3->setEmail("Admin_03@gmail.com");
        $user3->setRoles(["ROLE_ADMIN"]);
        $user3->setPhone("0387451999");
        $user3->setPassword($this->hasher->hashPassword($user,"123456"));
        $user3->setFullname("Nguyen Thanh Giang(admin)");
        $user3->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user3->setGender("Male");
        $user3->setImage("admin_Giang.jpg");
        $user3->setIsVerified(true);
        $manager->persist($user3);

        $user4 = new User;
        $user4->setEmail("User_01@gmail.com");
        $user4->setRoles(["ROLE_USER"]);
        $user4->setPhone("0387451999");
        $user4->setPassword($this->hasher->hashPassword($user,"123456"));
        $user4->setFullname("Nguyen Thanh Giang");
        $user4->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user4->setGender("Male");
        $user4->setImage("admin_Giang.jpg");
        $user4->setIsVerified(true);
        $manager->persist($user4);

        $user5 = new User;
        $user5->setEmail("User_02@gmail.com");
        $user5->setRoles(["ROLE_USER"]);
        $user5->setPhone("0387451999");
        $user5->setPassword($this->hasher->hashPassword($user,"123456"));
        $user5->setFullname("Nguyen Thanh Giang");
        $user5->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user5->setGender("Male");
        $user5->setImage("admin_Giang.jpg");
        $user5->setIsVerified(true);
        $manager->persist($user5);

        $user6 = new User;
        $user6->setEmail("User_03@gmail.com");
        $user6->setRoles(["ROLE_USER"]);
        $user6->setPhone("0387451999");
        $user6->setPassword($this->hasher->hashPassword($user,"123456"));
        $user6->setFullname("Nguyen Thanh Giang");
        $user6->setDob(DateTime::createFromFormat('Y/m/d', '2012/04/25'));
        $user6->setGender("Male");
        $user6->setImage("admin_Giang.jpg");
        $user6->setIsVerified(true);
        $manager->persist($user6);

        $manager->flush();
    }
}
