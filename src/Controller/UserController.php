<?php

namespace App\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class UserController extends AbstractController
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(EncoderFactoryInterface $encoderFactory, UserManagerInterface $userManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->encoderFactory = $encoderFactory;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
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

        if ($user = $this->validateUser($request->get('username'), $request->get('password'))) {
            $this->signInUser($user, $request);
            return new RedirectResponse('/');
        } else {
            return $this->render('user/login.html.twig', [
                'bodyClass' => 'error403',
                'error' => 'User not found!'
            ]);
        }
    }

    /**
     * @Route("/logout/")
     * @return RedirectResponse
     */
    public function logout()
    {
        session_destroy();
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

        $this->validateRegister($request);
        $this->createUser($request);

        if ($user = $this->validateUser($request->get('username'), $request->get('password'))) {
            $this->signInUser($user, $request);
            return new RedirectResponse('/');
        }

        return new RedirectResponse('/login');
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function validateRegister(Request $request)
    {
        if (strlen($request->get('username')) === 0 || strlen($request->get('password')) === 0 || strlen($request->get('email')) === 0) {
            return $this->render('user/register.html.twig', [
                'bodyClass' => 'register',
                'error' => 'You need to enter all details to successfully register'
            ]);
        }

        $userManager = $this->userManager;
        if ($userManager->findUserByEmail(strtolower($request->get('email')))) {
            return $this->render('user/register.html.twig', [
                'bodyClass' => 'register',
                'error' => 'Email address already used'
            ]);
        }
    }

    /**
     * @param Request $request
     */
    private function createUser(Request $request)
    {
        $userManager = $this->userManager;
        $user = $userManager->createUser();
        $user->setUsername(strtolower($request->get('username')));
        $user->setEmail(strtolower($request->get('email')));
        $user->setEmailCanonical($request->get('email'));
        $user->setEnabled(true);
        $user->setPlainPassword($request->get('password'));
        $userManager->updateUser($user);
    }

    /**
     * @param $username
     * @param $password
     * @return \FOS\UserBundle\Model\UserInterface|boolean
     */
    private function validateUser($username, $password)
    {
        $userManager = $this->userManager;
        $user = $userManager->findUserByUsername(strtolower($username));

        if (!$user) {
            return false;
        }

        $factory = $this->encoderFactory;
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if (!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return false;
        }

        return $user;
    }

    /**
     * @param $user
     * @param Request $request
     */
    private function signInUser($user, Request $request)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch("security.interactive_login", $event);
    }

    /**
     * @Route("/privacy-policy/")
     * @return Response
     */
    public function privacyPolicy()
    {
        return $this->render('user/privacy-policy.html.twig', [
            'bodyClass' => 'privacy',
        ]);
    }
}
