<?php

namespace App\DataFixtures;

use App\Entity\Certified;
use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\School;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $certified = [
            ['label' => 'Jean Dupont', 'grade' => 'Mention Bien', 'date' => '2024-06-15'],
            ['label' => 'Marie Martin', 'grade' => 'Mention Très Bien', 'date' => '2024-06-15'],
            ['label' => 'Paul Bernard', 'grade' => 'Mention Assez Bien', 'date' => '2024-06-15'],
        ];

        foreach ($certified as $item) {
            $c = new Certified();
            $c->setLabel($item['label']);
            $c->setGrade($item['grade']);
            $c->setGraduationDate(new \DateTimeImmutable($item['date']));
            $manager->persist($c);
        }

        $subscriptions = [
            ['label' => 'Basique', 'price' => '29.00', 'duration' => '1 mois'],
            ['label' => 'Pro', 'price' => '99.00', 'duration' => '1 mois'],
            ['label' => 'Annuel', 'price' => '799.00', 'duration' => '1 an'],
        ];

        foreach ($subscriptions as $item) {
            $s = new Subscription();
            $s->setLabel($item['label']);
            $s->setPrice($item['price']);
            $s->setDuration($item['duration']);
            $s->setIsActive(true);
            $manager->persist($s);
        }

        $schools = [
            ['label' => 'École Supérieure d\'Art', 'address' => '12 rue des Arts, Paris'],
            ['label' => 'Institut Tech Pro', 'address' => '5 avenue de l\'Innovation, Lyon'],
            ['label' => 'Académie du Numérique', 'address' => '8 boulevard du Digital, Bordeaux'],
        ];

        foreach ($schools as $item) {
            $school = new School();
            $school->setLabel($item['label']);
            $school->setAddress($item['address']);
            $manager->persist($school);
        }

        $manager->flush();
    }
}