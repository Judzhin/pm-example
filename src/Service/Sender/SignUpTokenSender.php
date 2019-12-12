<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service\Sender;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

/**
 * Class SignUpTokenSender
 * @package App\Service\Sender
 */
class SignUpTokenSender extends AbstractSender
{
    /**
     * @param User $user
     * @return Email
     */
    public function email(User $user): Email
    {
        return (new TemplatedEmail)
            ->subject('Thanks for signing up!')
            // ->text('Sending emails is fun again!')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'token' => $user->getConfirmToken()->getValue(),
                'expiration_date' => $user->getConfirmToken()->getExpires(),
            ]);
    }
}