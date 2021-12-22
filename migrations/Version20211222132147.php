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

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Main\Migrations\MigrationWithEntityManagerInterface;
use Main\Migrations\MigrationWithEntityManagerTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222132147 extends AbstractMigration implements MigrationWithEntityManagerInterface
{
    use MigrationWithEntityManagerTrait;

    public function getDescription(): string
    {
        return 'Change Character::$ways to be a serialized JSON Document instead of an object relation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E63437CCA');
        $this->addSql('DROP INDEX UNIQ_3A29410E63437CCA ON characters');
        $this->addSql('ALTER TABLE characters ADD ways JSON NOT NULL COMMENT "(DC2Type:json_document)"');
        $this->addSql('ALTER TABLE characters DROP ways_id');
    }

    public function down(Schema $schema): void
    {
    }
}
