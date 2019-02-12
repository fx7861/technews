<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use App\Repository\MembreRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{

    /**
     * @Route("/" ,
     *     name="front_home")
     * @return Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        $articles = $repository->findAll();
        $spotlight = $repository->findBySpotlight();

        return $this->render('front/home.html.twig', [
            'articles' => $articles,
            'spotlight' => $spotlight
        ]);
    }

    /**
     * @Route("/contact",
     *      name="front_contact")
     * @return Response
     */
    public function contact()
    {
        return new Response('<h1>Page contact</h1>');
    }

    /**
     * @Route("/tag/{slug<[a-zA-Z0-9\-_\/]+>}",
     *     defaults={"slug":"apple"},
     *     name="front_tag")
     * @param $slug
     * @return Response
     */
    public function tag($slug)
    {
        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findOneBy(['slug' => $slug]);

        $articles = $tag->getArticles();

        return $this->render('front/tag.html.twig', [
            'articles' => $articles,
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/categorie/{slug<[a-zA-Z0-9\-_\/]+>}",
     *     defaults={"slug":"sports"},
     *     name="front_categorie")
     * @param $slug
     * @return Response
     */
    public function categorie($slug)
    {
        $categorie = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findOneBy(['slug' => $slug]);

        $articles = $categorie->getArticles();

        return $this->render('front/cat.html.twig', [
            'articles' => $articles,
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/membre/{id<\d+>}",
     *     name="front_membre")
     */
    public function membre(MembreRepository $repository, $id)
    {
        $membre = $repository->find($id);

        $articles = $membre->getArticles();

        return $this->render('front/membre.html.twig', [
            'membre' => $membre,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/{categorie<[a-zA-Z0-9\-_\/]+>}/{slug<[a-zA-Z0-9\-_\/]+>}-{id<\d+>}",
     *     name="front_article")
     * @param $id
     * @param $categorie
     * @param $slug
     * @return Response
     */
    public function article(Article $article)
    {
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestion(
                $article->getId(),
                $article->getCategorie()->getId()
            );
        return $this->render('front/article.html.twig', [
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }

    public function sidebar()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $article = $repository->findLastArticle();

        $specials = $repository->findBySpecial();


        return $this->render('components/_sidebar.html.twig', [
           'articles' => $article,
           'specials' => $specials
        ]);
    }

    /**
     * @param TagRepository $repository
     * @return Response
     */
    public function cloudTags(TagRepository $repository) {
        $tags = $repository->findTags();

        return $this->render('components/_cloudTags.html.twig', [
           'tags' => $tags
        ]);
    }
}

