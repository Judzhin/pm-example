<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * Class ResetSender
 * @package App\Service
 */
class PasswordResetSender
{
    /** @var MailerInterface */
    protected $mailer;

    /** @var Environment */
    protected $twig;

    /** @var array */
    protected $from;

    /**
     * PasswordResetSender constructor.
     * @param MailerInterface $mailer
     * @param Environment $twig
     * @param array $from
     */
    public function __construct(MailerInterface $mailer, Environment $twig, array $from)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->from = $from;
    }

    /**
     * @param User $user
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(User $user)
    {
        /** @var Email $email */
        $email = (new TemplatedEmail)
            ->from('some@example.com')
            ->to(new Address($user->getEmail()->getValue()))
            ->subject('Thanks for signing up!')
            // path of the Twig template to render
            ->htmlTemplate('emails/password_reset.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'token' => $user->getResetToken()->getValue(),
                'expiration_date' => $user->getConfirmToken()->getExpires(),
            ]);

        $this->mailer->send($email);
    }
}