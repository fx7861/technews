<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Form\LoginFormType;
use App\Form\SignUpFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MembreController extends AbstractController
{
    /**
     * @Route("membre/login", name="membre_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return mixed
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        $form = $this->createForm(LoginFormType::class, [
            'email' => $authenticationUtils->getLastUsername()
        ]);

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('membre/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("membre/signup", name="membre_signup")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $membre = new Membre();

        $membre->setRoles(['ROLE_MEMBRE']);

        $form = $this->createForm(SignUpFormType::class, $membre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encodage du mot de passe
            $membre->setPassword($encoder->encodePassword($membre, $membre->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();

            // Notification

            $this->addFlash('notice', 'Félicitation, vous pouvez vous connecter !');

            // Redirection
            return $this->redirectToRoute('membre_login');

        }

        return $this->render('membre/signUp.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/membre/deconnexion", name="membre_deconnexion")
     */
    public function deconnexion() {}
}