<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Event Image Override extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventImageOverride\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use InspiredMinds\ContaoEventImageOverride\ContaoEventImageOverrideBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoEventImageOverrideBundle::class)
                ->setLoadAfter([ContaoCalendarBundle::class]),
        ];
    }
}
