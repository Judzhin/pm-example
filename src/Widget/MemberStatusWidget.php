<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Widget;

use App\Entity\Work\Member;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MemberStatusWidget
 * @package App\Widget
 */
class MemberStatusWidget extends AbstractExtension
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
                'member_status', [
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
     * @param Member $member
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onInvoke(Environment $twig, Member $member): string
    {
        return $twig->render(
            'widget/member_status.html.twig',
            [
                'status' => $member->getStatus(),
            ]
        );
    }

}