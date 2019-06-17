<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{
    public function index()
    {
        $authors = $this->getDoctrine()
            ->getRepository(Author::class)->findAll();

        $list = [];
        foreach ($authors as $author)
        {

            $book = $this->getDoctrine()
                ->getRepository(Book::class)->findBy(['author' => $author->getId()]);

            $list[] = [
                'author' => $author,
                'book' => $book,
                ];
        }

//        foreach ($list as $key=>$val)
//        {
//            echo "<pre>";
//            var_dump($key);
//            echo "</pre>";
//            echo "<hr>";
//            echo "<pre>";
//            var_dump($val);
//            echo "</pre>";
//        }
//
//        exit;

        return $this->render(
            'list/list.html.twig',
            [
                'list' => $list,
            ]
        );
    }
}