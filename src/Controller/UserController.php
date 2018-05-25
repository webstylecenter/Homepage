<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class UserController extends Controller
{
    protected $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @Route("/login")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->get('username', null) === null) {
            return $this->render('user/login.html.twig', [
                'bodyClass' => 'error403',
                'error' => 'Username not set!'
            ]);
        }

        $username = $request->get('username');
        $password = $request->get('password');

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (!$user) {
            return $this->render('user/login.html.twig', [
                'bodyClass' => 'error403',
                'error' => 'User not found!'
            ]);
        }

        $factory = $this->encoderFactory;
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if (!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return $this->render('user/login.html.twig', [
                'bodyClass' => 'error403',
                'error' => 'Invalid credentials'
            ]);
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return new RedirectResponse('/');
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if ($request->get('username', null) === null) {
            return $this->render('user/register.html.twig', [
                'bodyClass' => 'register'
            ]);
        }

        $userManager = $this->get('fos_user.user_manager');

        if ($userManager->findUserByEmail($request->get('email'))) {
            return $this->render('user/register.html.twig', [
                'bodyClass' => 'register',
                'error' => 'Email address already used'
            ]);
        }

        $user = $userManager->createUser();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setEmailCanonical($request->get('email'));
        $user->setEnabled(true);
        $user->setPlainPassword($request->get('password'));
        $userManager->updateUser($user);

        return new RedirectResponse('/login');
    }

}