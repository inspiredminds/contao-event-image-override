<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Event Image Override extension.
 *
 * (c) INSPIRED MINDS
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_module']['fields']['disallowOverride'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 m12'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

PaletteManipulator::create()
    ->addField('disallowOverride', 'imgSize')
    ->applyToPalette('eventlist', 'tl_module')
    ->applyToPalette('eventreader', 'tl_module')
;
