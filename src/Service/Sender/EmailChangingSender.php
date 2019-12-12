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
 * Class EmailChangingSender
 * @package App\Service\Sender
 */
class EmailChangingSender extends AbstractSender
{
    /**
     * @param User $user
     * @return Email
     */
    public function email(User $user): Email
    {
        return (new TemplatedEmail)
            ->subject('Email confirmation')
            // ->text('Sending emails is fun again!')
            ->htmlTemplate('emails/change_email.html.twig')
            ->context([
                'token' => $user->getNewConfirmToken()->getValue()
            ]);
    }
}