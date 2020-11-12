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
            ->add('authors', EntityType::class, [
                'placeholder' => 'Choice a champ',
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

            $author = new Author();

            $author = $this->getDoctrine()->getRepository(Author::class)->find($authorId);

            $book->setName($form->get('name')->getData());
            $book->setYear($form->get('year')->getData());
            $book->addAuthor($author);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        return $this->render('book/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
