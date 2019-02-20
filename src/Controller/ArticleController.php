<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/article/{id}", name="blog_show")
     */
    public function show(Article $article){ /* trouve article via ID */
        /* $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id); */
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }


}
