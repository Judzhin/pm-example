<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security\OAuth;

use App\Entity\Name;
use App\UseCase\SignUp\Network\Command;
use App\UseCase\SignUp\Network\Handler;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

// your user entity

/**
 * Class FacebookAuthenticator
 * @package App\Security\OAuth
 */
class FacebookAuthenticator extends SocialAuthenticator
{
    /** @var ClientRegistry */
    private $clientRegistry;

    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var Handler */
    private $handler;

    /**
     * FacebookAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param Handler $handler
     */
    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        Handler $handler
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'pm-facebook-check';
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        /** @var string $username */
        $username = 'facebook:' . $facebookUser->getId();

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $exception) {

            /** @var Command $command */
            $command = new Command(
                'facebook',
                $facebookUser->getId()
            );
            $command->name = new Name(
                $facebookUser->getFirstName(),
                $facebookUser->getLastName()
            );
            $this->handler->handle($command);
            return $userProvider->loadUserByUsername($username);
        }

        // $email = $facebookUser->getEmail();
        //
        // // 1) have they logged in with Facebook before? Easy!
        // $existingUser = $this->em->getRepository(User::class)
        //     ->findOneBy(['facebookId' => $facebookUser->getId()]);
        // if ($existingUser) {
        //     return $existingUser;
        // }
        //
        // // 2) do we have a matching user by email?
        // $user = $this->em->getRepository(User::class)
        //     ->findOneBy(['email' => $email]);
        //
        // // 3) Maybe you just want to "register" them by creating
        // // a User object
        // $user->setFacebookId($facebookUser->getId());
        // $this->em->persist($user);
        // $this->em->flush();
        //
        // return $user;
    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient(): FacebookClient
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('facebook_main');
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        // change "app_homepage" to some route in your app
        return new RedirectResponse(
            $this->router->generate('pm-home')
        );

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null|Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('pm-login')
        );

        //return new RedirectResponse(
        //    '/connect/', // might be the site, where users choose their oauth provider
        //    Response::HTTP_TEMPORARY_REDIRECT
        //);
    }

    // ...
}