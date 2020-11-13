<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function index(): Response
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/book/create", name="create_book")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newBook(Request $request) : Response
    {
        $book = new Book();

        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class)
            ->add('year', IntegerType::class)
            ->add('authors', EntityType::class, [
                'required' => true,
                'class' => Author::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'attr' => array('class' => 'dropdown'),
                'required' => true,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Create Book'))
            ->getForm();

        $form->handleRequest($request);

        $authorId = 0;

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($_POST as $item)
            {
                foreach ($item as $property)
                {
                    if (is_array($property))
                        $authorId = $property[0];              }
            }

            $author = $this->getDoctrine()->getRepository(Author::class)->find($authorId);

            $book->setName($form->get('name')->getData());
            $book->setYear($form->get('year')->getData());
            $author->addBook($book);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Book Created!');
        }

        return $this->render('book/view.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/book/edit/{book}", name="edit_book")
     *
     * @param Book $book
     * @param Request $request
     * @return Response
     */
    public function editBook(Book $book, Request $request) : Response
    {
        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class)
            ->add('year', IntegerType::class)
            ->add('authors', EntityType::class, [
                'required' => true,
                'class' => Author::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'attr' => array('class' => 'dropdown'),
                'required' => true,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Update Book'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $authorId = 0;

            foreach ($_POST as $item)
            {
                foreach ($item as $property)
                {
                    if (is_array($property))
                        $authorId = $property[0];              }
            }

            $author = $this->getDoctrine()->getRepository(Author::class)->find($authorId);

            $book->setName($form->get('name')->getData());
            $book->setYear($form->get('year')->getData());
            $author->addBook($book);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Book Updated!');
        }

        return $this->render('book/view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
