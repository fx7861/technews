<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    use HelperTrait;

    /**
     * @Route("/demo/article", name="article_demo")
     */
    public function demo()
    {
        $categorie = new Categorie();
        $categorie->setNom('Histoire')
            ->setSlug('histoire');

        $membre = new Membre();
        $membre->setNom('Mass')
            ->setPrenom('FXavier')
            ->setEmail('fx@gmail.com')
            ->setPassword('test')
            ->setRoles(['ROLE_MEMBRE']);

        $article = new Article();
        $article->setTitre('Rachida Dati aurait touché 600 000 euros de Renault-Nissan')
            ->setContenu("Selon les informations recueillies par L'Express, Dati aurait perçu la somme de 600 000 euros, payés en quatre fois. Petit problème : dans la déclaration d'intérêts qu'elle a dû remettre le 31 décembre 2014, en tant que députée européenne, à la Haute Autorité pour la transparence de la vie publique, Rachida Dati n'a déclaré aucune activité professionnelle sur l'année 2009 en tant qu'avocate, alors même qu'elle travaillait déjà pour l'Alliance.")
            ->setSlug('rachida-dati-avocate-de-luxe-pour-renault-nissan')
            ->setFeaturedImage('23256545.jpg')
            ->setSpecial(0)
            ->setSpotlight(1)
            ->setMembre($membre)
            ->setCategorie($categorie);

        /**
         * Récupération du Manager de Doctrine
         */
        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->persist($categorie);
        $em->persist($article);
        $em->flush();

        return new Response('Nouvel Article ajouté avec ID : '
            . $article->getId()
            . ' et la nouvelle catégorie '
            . $categorie->getNom()
            . 'de Auteur : '
            . $membre->getPrenom() . ' ' . $membre->getNom()
        );

    }

    /**
     * Formulaire de création d'article
     * @Route("/creer-un-article", name="article_new")
     * @Security("has_role('ROLE_AUTEUR')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newArticle(Request $request)
    {
        $article = new Article();

        $membre = $this->getDoctrine()
            ->getRepository(Membre::class)
            ->find(1);
        $article->setMembre($membre);

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            /** @var UploadedFile $featuredImage */
            $featuredImage = $article->getFeaturedImage();

            // tester mettre a jour le slug puis l'utiliser dans le nom du fichier

            // Renommer le nom du fichier
            $fileName = $this->slugify($article->getTitre()) . '.' . $featuredImage->guessExtension();
            $featuredImage->move(
                $this->getParameter('articles_assets_dir'), $fileName
            );

            // Mise à jour de l'image
            $article->setFeaturedImage($fileName);

            // Mise à jour du slug
            $article->setSlug($this->slugify($article->getTitre()));

            // Sauvegarde BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            // Redirection
            return $this->redirectToRoute('front_article', [
                'categorie' => $article->getCategorie()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editer-un-article/{id<\d+>}",
     *     name="article_edit")
     * @param ArticleRepository $repository
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editArticle(ArticleRepository $repository, Request $request, $id)
    {
        $article = $repository->find($id);

        $featuredImage = $article->getFeaturedImage();

        $article->setFeaturedImage(
            new File($this->getParameter('articles_assets_dir').'/'.$article->getFeaturedImage())
        );

        $form = $this->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {

            if ($article->getFeaturedImage() != null) {
                /** @var UploadedFile $featuredImage */
                $featuredImage = $article->getFeaturedImage();

                $fileName = $this->slugify($article->getTitre()) . '.' . $featuredImage->guessExtension();
                $featuredImage->move(
                    $this->getParameter('articles_assets_dir'), $fileName
                );

                // Mise à jour de l'image
                $article->setFeaturedImage($fileName);
            } else {
                $article->setFeaturedImage($featuredImage);
            }

            // Mise à jour du slug
            $article->setSlug($this->slugify($article->getTitre()));

            // Sauvegarde BDD
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Redirection
            return $this->redirectToRoute('front_article', [
                'categorie' => $article->getCategorie()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

}