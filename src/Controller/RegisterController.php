<?php
/**
 * Created by PhpStorm.
 * User: ceirokilp
 * Date: 25/09/2018
 * Time: 15:23
 */

namespace App\Controller;


use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenGenerator $tokenGenerator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        TokenGenerator $tokenGenerator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $userRegisterEvent = new UserRegisterEvent($user);

            $eventDispatcher->dispatch(UserRegisterEvent::NAME, $userRegisterEvent);

            $this->redirect('micro_post_index');
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}