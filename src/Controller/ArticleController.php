<?php

namespace App\Controller;

//bring  in Artcle entity
use App\Entity\Article;

//bring in the response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//restrict which methods to use
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     * @Method("GET")
     */
    public function index(ManagerRegistry $doctrine)
    {
        // $articles = ['Article1', 'Article2']; //hardcoded array

        $articles = $doctrine->getRepository(Article::class)->findAll(); //from db
        //return new Response('Hello');
        return $this->render('articles/index.html.twig', array('articles' => $articles));
    }
    /**
     * @Route("/article/new", name="new_article")
     * Method({"GET","POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' =>
            array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            //save to db
            $entityManager = $doctrine->getManager();
            //persist means we want to eventually save the data
            $entityManager->persist($article);
            //to actually save use flush
            $entityManager->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show(ManagerRegistry $doctrine, $id)
    {
        //find a single article
        $article = $doctrine->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', array('article' => $article));
    }

    // /**
    //  * @Route("/article/save")
    //  */
    // //how to save an article using get request
    // public function save(ManagerRegistry $doctrine): Response
    // {
    //     $entityManager =  $doctrine->getManager();

    //     $article = new Article();
    //     $article->setTitle('Article Two');
    //     $article->setBody('This is the body of the second article');
    //     //persist means we want to eventually save the data
    //     $entityManager->persist($article);
    //     //to actually save use flush
    //     $entityManager->flush();

    //     return new Response('Saved an article with the id of' . $article->getId());
    // }
}