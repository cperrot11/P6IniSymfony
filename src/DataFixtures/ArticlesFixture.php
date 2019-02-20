<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticlesFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($cpt=1;$cpt<=10;$cpt++) {
            $article = new Article();
            $article->setTitle("Article n°$cpt")
                    ->setContent("Contenu de $cpt")
                    ->setPicture("http://placehold.it/150x150")
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article);
        }
        $manager->flush();
    }
}
