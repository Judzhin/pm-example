<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Messenger\MessageHandler;
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
    /** @var Mailer */
    protected $mailer;

    /** @var Environment */
    protected $twig;

    /**
     * ConfirmTokenSender constructor.
     *
     * @param MailerInterface $mailer
     * @param array $from
     */
    public function __construct(MailerInterface $mailer, array $from)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function send(User $user): void
    {
        // /** @var TransportInterface $transport */
        // $transport = new EsmtpTransport('pm-mailer', 1025);
        //
        // /** @var Mailer $mailer */
        // $mailer = new Mailer($transport);

        /** @var Email $email */
        $email = (new TemplatedEmail)
            ->from([new Address('some@example.com')])
            ->to(new Address($user->getEmail()->getValue()))
            ->subject('Thanks for signing up!')

            // path of the Twig template to render
            ->htmlTemplate('emails/signup.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'token' => $user->getConfirmToken()->getValue(),
                'expiration_date' => $user->getConfirmToken()->getExpires(),
            ]);

        if (!$this->mailer->send($email)) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}