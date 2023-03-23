<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Factory\AddressFactory;
use App\Factory\SkillFactory;
use App\Factory\TeacherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        SkillFactory::createOne(['skillName' => 'PHP']);
        SkillFactory::createOne(['skillName' => 'Java']);
        SkillFactory::createOne(['skillName' => 'React']);
        SkillFactory::createOne(['skillName' => 'Android']);
        SkillFactory::createOne(['skillName' => 'SQL']);
        SkillFactory::createOne(['skillName' => 'Python']);
        SkillFactory::createOne(['skillName' => 'C++']);
        SkillFactory::createOne(['skillName' => 'C#']);
        SkillFactory::createOne(['skillName' => 'Ruby']);
        SkillFactory::createOne(['skillName' => 'Go']);
        SkillFactory::createOne(['skillName' => 'Scala']);
        SkillFactory::createOne(['skillName' => 'Haskell']);

        AddressFactory::createMany(300);

        TeacherFactory::createOne([
            'lastName' => 'Koudelka',
            'address' => AddressFactory::random()
        ]);
        TeacherFactory::createOne();
        TeacherFactory::createMany(
            50,
            function(){
                return [
                    'address' => AddressFactory::random(),
                    'skills' => SkillFactory::randomRange(1, 4)
                ];
            }
        );

        /*
        $teacher = new Teacher();

        $address = (new Address())->setStreet('5 rue du bac')
        ->setCity('Paris')->setZipCode('75008');




        $teacher->setFirstName('Joseph')
            ->setLastName('Koudelka')
            ->setDateOfBirth(new \DateTime('1945-2-8'))
            ->setDailyRate(400)
            ->setAddress($address)
            ->addSkill((new Skill())->setSkillName('PHP'))
            ->addSkill((new Skill())->setSkillName('Java'))
            ->addSkill((new Skill())->setSkillName('Python'));

        $student = new Student();
        $student->setFirstName('Sophie')
            ->setLastName('Calle')
            ->setDateOfBirth(new \DateTime('1985-2-8'))
            ->setEnrolledAt(new \DateTime("now - 5 months"))
            ->setAddress($address);

        $manager->persist($teacher);
        $manager->persist($student);

        $manager->flush();
        */
    }
}
