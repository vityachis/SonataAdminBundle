<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Action;

use Sonata\AdminBundle\Templating\TemplateRegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class DashboardAction
{
    /**
     * @param array<array<string, mixed>> $dashboardBlocks
     */
    public function __construct(
        private array $dashboardBlocks,
        private TemplateRegistryInterface $templateRegistry,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $blocks = [
            'top' => [],
            'left' => [],
            'center' => [],
            'right' => [],
            'bottom' => [],
        ];

        foreach ($this->dashboardBlocks as $block) {
            $blocks[$block['position']][] = $block;
        }

        $parameters = [
            'base_template' => $request->isXmlHttpRequest() ?
                $this->templateRegistry->getTemplate('ajax') :
                $this->templateRegistry->getTemplate('layout'),
            'blocks' => $blocks,
        ];

        return new Response($this->twig->render($this->templateRegistry->getTemplate('dashboard'), $parameters));
    }
}
