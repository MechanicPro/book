<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookController extends AbstractController
{
    public function index()
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)->findAll();

        $authors = $this->getDoctrine()
            ->getRepository(Author::class)->findAll();

        return $this->render(
            'admin/book.html.twig',
            [
                'books' => $books,
                'authors' => $authors,
            ]
        );
    }

    public function createUpdateAuthor($id = null, Request $request)
    {
        if (empty($id)) {
            $author = new Author();
        } else {
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        }

        $form = $this->createFormBuilder($author)
            ->add('name', TextType::class, ['label' => 'Полное имя автора'])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Сохранить',
                    'attr' => [
                        'class' => 'btn btn-outline-success',
                        'style' => 'width:100%; margin-top: 15px',
                    ],
                ]
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            unset($form);

            return $this->redirectToRoute('indexAdmin');
        }

        return $this->render(
            'form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function createUpdateBook($id = null, Request $request)
    {
        $authors = $this->getDoctrine()
            ->getRepository(Author::class)->findAll();

        if (empty($id)) {
            $book = new Book();
        } else {
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        }

        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class, ['label' => 'Наименование книги'])
            ->add(
                'author',
                ChoiceType::class,
                [
                    'choices' => $authors,
                    'choice_label' => function ($author) {
                        /** @var Author $author */
                        return $author->getName();
                    },
                    'attr' => ['class' => 'custom-select mb-3'],
                    'label' => 'Автор',
                    'placeholder' => 'Выберети автора',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Сохранить',
                    'attr' => [
                        'class' => 'btn btn-outline-success',
                        'style' => 'width:100%; margin-top: 15px',
                    ],
                ]
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            unset($form);

            return $this->redirectToRoute('indexAdmin');
        }

        return $this->render(
            'admin/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function delAuthor($id = null)
    {
        if (!empty($id)) {
            $authors = $this->getDoctrine()
                ->getRepository(Author::class)->find($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($authors);
            $em->flush();
        }

        return $this->redirectToRoute('indexAdmin');
    }

    public function delBook($id = null)
    {
        if (!empty($id)) {
            $books = $this->getDoctrine()
                ->getRepository(Book::class)->find($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($books);
            $em->flush();
        }

        return $this->redirectToRoute('indexAdmin');
    }
}