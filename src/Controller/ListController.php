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

        return $this->render(
            'list/list.html.twig',
            [
                'list' => $list,
            ]
        );
    }
}