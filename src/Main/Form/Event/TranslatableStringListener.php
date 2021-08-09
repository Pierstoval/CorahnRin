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

namespace Main\Form\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TranslatableStringListener implements EventSubscriberInterface
{
    private const LOCALES_LABELS = [
        'en' => '🇬🇧',
        'fr' => '🇫🇷',
    ];

    private array $locales;

    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'addTranslatableFields',
        ];
    }

    public function addTranslatableFields(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!$data) {
            foreach ($this->locales as $locale) {
                $data[$locale] = '';
            }
            $event->setData($data);
        }

        foreach ($data as $locale => $translatedString) {
            $form->add($locale, TextType::class, [
                'label' => self::LOCALES_LABELS[$locale] ?? $locale,
                'data' => $translatedString,
            ]);
        }
    }
}
