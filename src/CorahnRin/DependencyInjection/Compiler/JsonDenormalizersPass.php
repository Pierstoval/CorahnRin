<?php

declare(strict_types=1);

/*
 * This file is part of the Corahn-Rin package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\DependencyInjection\Compiler;

use CorahnRin\Serializer\WaysNormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class JsonDenormalizersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $def = $container->getDefinition('dunglas_doctrine_json_odm.serializer');

        /** @var array $normalizers */
        $normalizers = $def->getArgument(0);
        \array_unshift($normalizers, new Reference(WaysNormalizer::class));
        $def->setArgument(0, $normalizers);
    }
}
