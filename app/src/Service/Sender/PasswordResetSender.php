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
 * Class PasswordResetSender
 * @package App\Service\Sender
 */
class PasswordResetSender extends AbstractSender
{
    /**
     * @param User $user
     * @return Email
     */
    public function email(User $user): Email
    {
        return (new TemplatedEmail)
            ->subject('Thanks for signing up!')
            // path of the Twig template to render
            ->htmlTemplate('emails/password_reset.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'token' => $user->getResetToken()->getValue(),
                'expiration_date' => $user->getConfirmToken()->getExpires(),
            ]);
    }
}