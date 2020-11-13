<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        for ($i = 0; $i < 10; $i++)
        {
            $author = new Author();
            $author->setName('Author Name ' . $i);
            $manager->persist($author);

            $book = new Book();
            $book->setName('Book Name' . $i);
            $book->setYear(mt_rand(0,2020));
            $book->addAuthor($author);
            $manager->persist($book);

        }

        $manager->flush();
    }
}
