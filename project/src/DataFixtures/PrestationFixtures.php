<?php

namespace App\DataFixtures;

use App\Entity\Coach;
use App\Entity\Motif;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PrestationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Motifs
        $motifs = [
            'Coaching en musculation',
            'Coaching cardio / perte de poids',
            'Coaching fitness / remise en forme',
            'Coaching en stretching / mobilité',
            'Coaching sport spécifique'
        ];
        foreach ($motifs as $nom) {
            $motif = new Motif();
            $motif->setNom($nom);
            $manager->persist($motif);
        }

        // Coachs
        $coachs = [
            ['nom' => 'Dupont', 'prenom' => 'Jean', 'email' =>'test@test.test', 'password'=>'testest'],
            ['nom' => 'Martin', 'prenom' => 'Sophie','email' =>'test1@test.test', 'password'=>'testest1'],
            ['nom' => 'Bernard', 'prenom' => 'Lucas','email' =>'test2@test.test', 'password'=>'testest2'],
        ];
        foreach ($coachs as $data) {
            $coach = new Coach();
            $coach->setNom($data['nom']);
            $coach->setPrenom($data['prenom']);
            $coach->setEmail($data['email']);
            $coach->setPassword($data['password']);
            // adaptez selon les champs de votre entité Coach
            $manager->persist($coach);
        }

        $manager->flush();
    }
}