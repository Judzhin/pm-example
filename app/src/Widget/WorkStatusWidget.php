<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Widget;

use App\Entity\Work\StatusAwareInterface;
use Twig\Environment;
use Twig\Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class WorkStatusWidget
 *
 * @package App\Widget
 */
class WorkStatusWidget extends AbstractExtension
{
    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'work_status', [
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
     * @inheritDoc
     *
     * @param Environment $twig
     * @param StatusAwareInterface $aware
     * @return string
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     */
    public function onInvoke(Environment $twig, StatusAwareInterface $aware): string
    {
        return $twig->render(
            'widget/work_status.html.twig',
            [
                'status' => $aware->getStatus(),
            ]
        );
    }

}