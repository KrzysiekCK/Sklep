<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Type;
use App\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("security/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastLogin = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'title' => 'Logowanie',
            'types' => $types,
            'last_login' => $lastLogin,
            'error' => $error,
        ]);
    }

    /**
     * @Route("security/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($account, $account->getPlainPassword());
            $account->setPassword($password);

            $em->persist($account);
            $em->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/register.html.twig', [
            'title' => 'Rejestracja',
            'types' => $types,
            'form' => $form->createView(),
        ]);
    }

}
