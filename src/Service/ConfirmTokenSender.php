<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * Class ConfirmTokenSender
 * @package App\Service
 */
class ConfirmTokenSender
{
    /** @var MailerInterface|Mailer */
    protected $mailer;

    /** @var Environment */
    protected $twig;

    /**
     * ConfirmTokenSender constructor.
     * @param MailerInterface $mailer
     * @param Environment $twig
     * @param array $from
     */
    public function __construct(MailerInterface $mailer, Environment $twig, array $from)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function send(User $user): void
    {
        /** @var Email $email */
        $email = (new Email)
            ->from('some@example.com')
            ->to(new Address($user->getEmail()->getValue()))
            ->subject('Thanks for signing up!')
            ->text('Sending emails is fun again!')
            ->html(
                $this->twig->render('emails/signup.html.twig', [
                    'username' => $user->getUsername(),
                    'token' => $user->getConfirmToken()->getValue(),
                    'expiration_date' => $user->getConfirmToken()->getExpires(),
                ])
            );

        ///** @var Email $email */
        //$email = (new TemplatedEmail)
        //    ->from('some@example.com')
        //    ->to(new Address($user->getEmail()->getValue()))
        //    ->subject('Thanks for signing up!')
        //    // path of the Twig template to render
        //    ->htmlTemplate('emails/signup.html.twig')
        //    // pass variables (name => value) to the template
        //    ->context([
        //        'token' => $user->getConfirmToken()->getValue(),
        //        'expiration_date' => $user->getConfirmToken()->getExpires(),
        //    ]);

        $this->mailer->send($email);
    }
}