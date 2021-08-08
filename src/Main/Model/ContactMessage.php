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

namespace Main\Model;

class ContactMessage
{
    public const SUBJECT_SUPPORT = 'contact.subject.support';
    public const SUBJECT_APPLICATION = 'contact.subject.application';
    public const SUBJECT_LICENSE = 'contact.subject.license';
    public const SUBJECT_PRO = 'contact.subject.pro';
    public const SUBJECT_EVENTS = 'contact.subject.events';
    public const SUBJECT_ORGANIZATIONS = 'contact.subject.organizations';

    public const SUBJECT_OTHER = 'contact.subject.other';

    public const BRAND_7TH_SEA = 'contact.brand.7th_sea';
    public const BRAND_DRAGONS = 'contact.brand.dragons';
    public const BRAND_DEMON = 'contact.brand.demon';
    public const BRAND_ESTEREN = 'contact.brand.esteren';
    public const BRAND_ESTEREN_MAPS = 'contact.brand.esteren_maps';
    public const BRAND_REQUIEM = 'contact.brand.requiem';
    public const BRAND_VERMINE = 'contact.brand.vermine';
    public const BRAND_OTHER = 'contact.brand.other';

    public const SUBJECTS = [
        'contact.subject.specify' => '',
        self::SUBJECT_SUPPORT => self::SUBJECT_SUPPORT,
        self::SUBJECT_LICENSE => self::SUBJECT_LICENSE,
        self::SUBJECT_PRO => self::SUBJECT_PRO,
        self::SUBJECT_EVENTS => self::SUBJECT_EVENTS,
        self::SUBJECT_ORGANIZATIONS => self::SUBJECT_ORGANIZATIONS,
        self::SUBJECT_OTHER => self::SUBJECT_OTHER,
        self::SUBJECT_APPLICATION => self::SUBJECT_APPLICATION,
    ];

    public const BRANDS = [
        'contact.brand.specify' => '',
        self::BRAND_7TH_SEA => self::BRAND_7TH_SEA,
        self::BRAND_DRAGONS => self::BRAND_DRAGONS,
        self::BRAND_DEMON => self::BRAND_DEMON,
        self::BRAND_ESTEREN => self::BRAND_ESTEREN,
        self::BRAND_ESTEREN_MAPS => self::BRAND_ESTEREN_MAPS,
        self::BRAND_REQUIEM => self::BRAND_REQUIEM,
        self::BRAND_VERMINE => self::BRAND_VERMINE,
        self::BRAND_OTHER => self::BRAND_OTHER,
    ];

    private string $name = '';
    private string $email = '';
    private string $message = '';
    private string $subject = '';
    private string $brand = '';
    private string $title = '';
    private string $locale = 'fr';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = \strip_tags((string) $name);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = \strip_tags((string) $email);

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = \strip_tags((string) $message);

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = (string) $subject;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = (string) $brand;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = (string) $title;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = (string) $locale;

        return $this;
    }
}
