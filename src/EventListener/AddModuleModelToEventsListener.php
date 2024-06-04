<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Event Image Override extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventImageOverride\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Module;

/**
 * Adds the module model instance to the event record.
 */
#[AsHook('getAllEvents')]
class AddModuleModelToEventsListener
{
    public function __invoke(array $events, array $calendars, int $timeStart, int $timeEnd, Module $module): array
    {
        foreach ($events as $a => $aa) {
            foreach ($aa as $b => $bb) {
                foreach (array_keys($bb) as $c) {
                    $events[$a][$b][$c]['module'] = $module->getModel();
                }
            }
        }

        return $events;
    }
}
