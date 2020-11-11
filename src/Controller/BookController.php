<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    }

    /**
     * @Route("/book/create", name="create_book")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newBook(Request $request)
    {
        $book = new Book();

        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class)
            ->add('year', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Book'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $book = $form->getData();
            return $this->redirectToRoute('task_success');
        }

        return $this->render('book/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
