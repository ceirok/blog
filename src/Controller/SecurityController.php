<?php
/**
 * Created by PhpStorm.
 * User: ceirokilp
 * Date: 25/09/2018
 * Time: 14:45
 */

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route(/confirm", name="security_confirm")
     */
    public function confirm(string $token, UserRepository $userRepository)
    {
        $user = $userRepository->findBy([
            'confirmationToken' => $token
        ]);

        return $this->render('security/confirmation.html.twig', [
            'user' => $user
        ]);
    }

}