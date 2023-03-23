<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

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
    }
}
