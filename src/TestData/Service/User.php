<?php
namespace TestData\Service;

use \Decision\Model\Member as MemberModel;
use \User\Model\User as UserModel;
use \User\Model\NewUser as NewUserModel;

class User extends AbstractTestDataService
{
    public function generateTestData()
    {
        for($i = 0; $i < 200; $i++)
        {
            $this->generateUser(8000 + $i);
        }
    }

    public function generateUser($lidnr)
    {
        $member = new MemberModel();

        $email = $this->faker->unique()->email;
        $firstName = explode(' ', $this->faker->name())[0];

        $member->setlidnr($lidnr);
        $member->setEmail($email);
        $member->setLastName($this->faker->lastName);
        $member->setMiddleName('');
        $member->setInitials($firstName[0] . '.');
        $member->setFirstName($firstName);
        $member->setGender(array_rand(array('f' => 'f', 'm' => 'm', 'o' => 'o')));
        $member->setGeneration($this->faker->year());
        $member->setType(MemberModel::TYPE_ORDINARY);
        $member->setChangedOn(new \DateTime($this->faker->date()));
        $member->setBirth(new \DateTime($this->faker->date()));
        $member->setExpiration(new \DateTime($this->faker->date()));
        $this->em->persist($member);

        $password = '$2y$13$5WprUFHONf2tcFOKU2rlM.nhTs2x1m4rHEezFcZrMLm6qq.4hm6kC'; //==password
        $newUser = new NewUserModel($member);
        $user = new UserModel($newUser);
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function getRandomUsers($count)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('User\Model\User', 'u')
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand');
        $qb->setMaxResults($count);
        return $qb->getQuery()->getResult();
    }

    public function getRandomUser()
    {
        return $this->getRandomUsers(1)[0];
    }
}
