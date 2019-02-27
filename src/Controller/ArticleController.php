<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{
     /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('article/home.html.twig',[
            'title'=> "Bienvenue les amis",
            'age'=>31
        ]);
    }

    /**
     * @Route("/article", name="blog")
     */
    public function index(ArticleRepository $repo) /* injection de dépendance */
    {
//        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles'=>$articles
        ]);
    }

    /**
     * @Route("/article/new", name="blog_create")
     * @Route("/article/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager)  {
        if (!$article) {
            $article = new Article();
        }

//        $form = $this->createFormBuilder($article)
//                        ->add('title')
//                        ->add('content')
//                        ->add('picture')
//                        ->getForm();

        $form = $this->createForm(ArticleType::class, $article);

        /* analyse la requête passée */
        $form->handleRequest($request);

        dump($article);
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id'=> $article->getId()]);
        }

        return $this->render('article/create.html.twig', [
            'formArticle'=> $form->createView(),
            'editMode'=> $article->getId() !== null
        ]);
    }

    /**
     * @Route("/article/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, ObjectManager $manager){ /* trouve article via ID */
        /* $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id); */
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm'=> $form->createView()
        ]);
    }



}
