<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Event Image Override extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventImageOverride;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoEventImageOverrideBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
