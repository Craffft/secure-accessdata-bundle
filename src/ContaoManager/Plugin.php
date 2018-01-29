<?php

declare(strict_types=1);

/*
 * This file is part of the Secure Accessdata Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\SecureAccessdataBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Craffft\SecureAccessdataBundle\CraffftSecureAccessdataBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftSecureAccessdataBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['secure-accessdata']),
        ];
    }
}
