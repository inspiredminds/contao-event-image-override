<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Event Image Override extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventImageOverride\EventListener;

use Contao\CalendarEventsModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\Model;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContaoEventImageOverrideListener
{
    public function __construct(
        private readonly Studio $studio,
        private readonly TranslatorInterface $translator,
        private readonly Connection $db,
        private $isOverrideDisallowedFallback = false,
    ) {
    }

    #[AsHook('parseTemplate')]
    public function onParseTemplate(Template $template): void
    {
        if (!str_starts_with($template->getName(), 'event_')) {
            return;
        }

        // Model should be in registry at this point
        if (!$event = CalendarEventsModel::findById($template->id)) {
            return;
        }

        if (!$event->singleSRC) {
            return;
        }

        $size = StringUtil::deserialize($event->size, true);

        if ([] === array_filter($size)) {
            return;
        }

        if ($this->isOverrideDisallowed($template)) {
            return;
        }

        $figureBuilder = $this->studio->createFigureBuilder();
        $figure = $figureBuilder
            ->from($event->singleSRC)
            ->setSize($size)
            ->enableLightbox((bool) $event->fullsize)
            ->setOverwriteMetadata($event->getOverwriteMetadata())
            ->buildIfResourceExists()
        ;

        if ($figure) {
            // Rebuild with link to event if none is set
            if ($template->href && !$figure->getLinkHref()) {
                $figure = $figureBuilder
                    ->setLinkHref($template->href)
                    ->setLinkAttribute('title', StringUtil::specialchars(sprintf($this->translator->trans('MSC.readMore', [], 'contao_default'), $event->title)))
                    ->build()
                ;
            }

            $figure->applyLegacyTemplateData($template, floating: $event->floating);
        }
    }

    /**
     * "Hack" for event reader modules. The 'isVisibleElement' hook is executed for
     * each module before the module is rendered. Thus we extract the disallowOverride
     * property here.
     */
    #[AsHook('isVisibleElement')]
    public function onIsVisibleElement(Model $element, bool $isVisible): bool
    {
        if (!$element instanceof ModuleModel || !str_starts_with($element->type, 'event')) {
            return $isVisible;
        }

        if ($element->disallowOverride) {
            $this->isOverrideDisallowedFallback = true;
        }

        return $isVisible;
    }

    private function isOverrideDisallowed(Template $template): bool
    {
        if ($template->module instanceof ModuleModel) {
            return (bool) $template->module->disallowOverride;
        }

        return $this->isOverrideDisallowedFallback;
    }
}
