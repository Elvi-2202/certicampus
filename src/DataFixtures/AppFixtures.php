<?php

namespace App\DataFixtures;

use App\Entity\Certified;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['label' => 'Jean Dupont', 'grade' => 'Mention Bien', 'date' => '2024-06-15'],
            ['label' => 'Marie Martin', 'grade' => 'Mention Très Bien', 'date' => '2024-06-15'],
            ['label' => 'Paul Bernard', 'grade' => 'Mention Assez Bien', 'date' => '2024-06-15'],
        ];

        foreach ($data as $item) {
            $certified = new Certified();
            $certified->setLabel($item['label']);
            $certified->setGrade($item['grade']);
            $certified->setGraduationDate(new \DateTimeImmutable($item['date']));
            $manager->persist($certified);
        }

        $manager->flush();
    }
}