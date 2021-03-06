<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Widget;

use App\Entity\User;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UserRolesWidget
 * @package App\Widget
 */
class UserRolesWidget extends AbstractExtension
{
    /**
     * @inheritdoc
     *
     * @return array|\Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'user_roles', [
                $this,
                'onInvoke',
            ], [
                    'needs_environment' => true,
                    'is_safe' => [
                        'html',
                    ],
                ]
            ),
        ];
    }

    /**
     * @inheritdoc
     *
     * @param Environment $twig
     * @param User $user
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onInvoke(Environment $twig, User $user): string
    {
        return $twig->render(
            'widget/user_roles.html.twig',
            [
                'roles' => $user->getRoles(),
            ]
        );
    }

}