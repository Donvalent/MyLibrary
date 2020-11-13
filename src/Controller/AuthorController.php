<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author")
     */
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    /**
     * @Route("/author/create", name="create_author")
     *
     * @param Request $request
     * @return Response
     */
    public function createAuthor(Request $request) : Response
    {
        $author = new Author();

        $form = $this->createFormBuilder($author)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Author'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $author = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            echo 'Well done!';
        }

        return $this->render('author/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
