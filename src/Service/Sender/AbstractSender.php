<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service\Sender;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Class AbstractSender
 * @package App\Service\Sender
 */
abstract class AbstractSender
{
    /** @var MailerInterface */
    protected $mailer;

    /** @var array */
    protected $from;

    /**
     * AbstractSender constructor.
     * @param MailerInterface $mailer
     * @param array $from
     */
    public function __construct(MailerInterface $mailer, array $from = ['sender@example.com'])
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    /**
     * @param User $user
     * @return Email
     */
    public abstract function email(User $user): Email;

    /**
     * @param User $user
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(User $user)
    {
        ///** @var Email $email */
        //$email = (new TemplatedEmail)
        //    ->from('some@example.com')
        //    ->to(new Address($user->getEmail()->getValue()))
        //    ->subject('Thanks for signing up!')
        //    // path of the Twig template to render
        //    ->htmlTemplate('emails/password_reset.html.twig')
        //    // pass variables (name => value) to the template
        //    ->context([
        //        'token' => $user->getResetToken()->getValue(),
        //        'expiration_date' => $user->getConfirmToken()->getExpires(),
        //    ]);

        /** @var Email $email */
        $email = $this->email($user);

        foreach ($this->from as $address => $name) {
            $email->from(new Address($address, $name));
        }

        $email->to(new Address($user->getEmail()->getValue()));
        $this->mailer->send($email);
    }
}