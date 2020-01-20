<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security;

use App\Service\PasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class FormLoginAuthenticator
 * @package App\Security
 */
class FormLoginAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var PasswordEncoder|UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * FormLoginAuthenticator constructor.
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param PasswordEncoder $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        PasswordEncoder $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        /** @var array $credentials */
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var CsrfToken $token */
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException;
        }

        /** @var UserInterface $user */
        if ($user = $userProvider->loadUserByUsername($credentials['email'])) {
            return $user;
        }

        // fail authentication with a custom error
        throw new CustomUserMessageAuthenticationException('Email could not be found.');
    }

    /**
     * @inheritdoc
     *
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid(
            $user->getPassword(), $credentials['password']
        );
    }

    /**
     * @inheritdoc
     *
     * @param $credentials
     * @return null|string
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /**
     * @inheritDoc
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        /** @var string $targetPath */
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($this->urlGenerator->generate('pm_home'));

        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
