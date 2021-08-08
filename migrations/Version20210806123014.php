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

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806123014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE armors (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(50) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          protection SMALLINT NOT NULL,
          price SMALLINT NOT NULL,
          availability VARCHAR(3) NOT NULL,
          UNIQUE INDEX UNIQ_AFBA56C25E237E06 (name),
          INDEX IDX_AFBA56C216A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE artifacts (
          id INT AUTO_INCREMENT NOT NULL,
          flux_id INT NOT NULL,
          name VARCHAR(70) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          price SMALLINT NOT NULL,
          consumption SMALLINT NOT NULL,
          consumption_interval SMALLINT NOT NULL,
          tank SMALLINT DEFAULT NULL,
          resistance SMALLINT NOT NULL,
          vulnerability VARCHAR(255) DEFAULT NULL,
          handling VARCHAR(20) DEFAULT NULL,
          damage SMALLINT DEFAULT NULL,
          created DATETIME NOT NULL,
          updated DATETIME NOT NULL,
          deleted DATETIME DEFAULT NULL,
          UNIQUE INDEX UNIQ_299E46885E237E06 (name),
          INDEX IDX_299E4688C85926E (flux_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE avantages (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(50) NOT NULL,
          name_female VARCHAR(50) NOT NULL,
          xp SMALLINT NOT NULL,
          description LONGTEXT DEFAULT NULL,
          bonus_count SMALLINT DEFAULT 0 NOT NULL,
          bonuses_for LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\',
          requires_indication VARCHAR(255) DEFAULT NULL,
          indication_type VARCHAR(20) DEFAULT \'single_value\' NOT NULL,
          is_disadvantage TINYINT(1) NOT NULL,
          avtg_group VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX UNIQ_CBC7848D5E237E06 (name),
          INDEX IDX_CBC7848D16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE books (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(80) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          UNIQUE INDEX UNIQ_4A1B2A925E237E06 (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters (
          id INT AUTO_INCREMENT NOT NULL,
          geo_living_id INT DEFAULT NULL,
          ways_id INT DEFAULT NULL,
          people_id INT DEFAULT NULL,
          social_class_id INT DEFAULT NULL,
          mental_disorder_id INT DEFAULT NULL,
          job_id INT DEFAULT NULL,
          trait_flaw_id INT DEFAULT NULL,
          trait_quality_id INT DEFAULT NULL,
          domains_id INT DEFAULT NULL,
          user_id INT DEFAULT NULL,
          game_id INT DEFAULT NULL,
          name VARCHAR(255) NOT NULL,
          name_slug VARCHAR(255) NOT NULL,
          player_name VARCHAR(255) NOT NULL,
          sex VARCHAR(30) NOT NULL,
          description LONGTEXT NOT NULL,
          story LONGTEXT NOT NULL,
          facts LONGTEXT NOT NULL,
          inventory LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
          treasures LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
          orientation VARCHAR(40) NOT NULL,
          temporary_trauma SMALLINT DEFAULT 0 NOT NULL,
          permanent_trauma SMALLINT DEFAULT 0 NOT NULL,
          hardening SMALLINT DEFAULT 0 NOT NULL,
          age SMALLINT NOT NULL,
          mental_resistance_bonus SMALLINT NOT NULL,
          stamina SMALLINT NOT NULL,
          stamina_bonus SMALLINT NOT NULL,
          survival SMALLINT NOT NULL,
          speed_bonus SMALLINT NOT NULL,
          defense_bonus SMALLINT NOT NULL,
          rindath SMALLINT NOT NULL,
          rindathMax SMALLINT NOT NULL,
          exaltation SMALLINT NOT NULL,
          exaltation_max SMALLINT NOT NULL,
          experience_actual SMALLINT NOT NULL,
          experience_spent SMALLINT NOT NULL,
          tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\',
          social_class_domain1 VARCHAR(100) NOT NULL,
          social_class_domain2 VARCHAR(100) NOT NULL,
          ost_service VARCHAR(100) NOT NULL,
          birth_place INT NOT NULL,
          created_at DATETIME NOT NULL,
          updated_at DATETIME NOT NULL,
          daol_ember INT NOT NULL,
          daol_azure INT NOT NULL,
          daol_frost INT NOT NULL,
          health_good SMALLINT NOT NULL,
          health_okay SMALLINT NOT NULL,
          health_bad SMALLINT NOT NULL,
          health_critical SMALLINT NOT NULL,
          health_agony SMALLINT NOT NULL,
          max_health_good SMALLINT NOT NULL,
          max_health_okay SMALLINT NOT NULL,
          max_health_bad SMALLINT NOT NULL,
          max_health_critical SMALLINT NOT NULL,
          max_health_agony SMALLINT NOT NULL,
          INDEX IDX_3A29410E8B7556B0 (geo_living_id),
          UNIQUE INDEX UNIQ_3A29410E63437CCA (ways_id),
          INDEX IDX_3A29410E3147C936 (people_id),
          INDEX IDX_3A29410E64319F3C (social_class_id),
          INDEX IDX_3A29410E46CBF851 (mental_disorder_id),
          INDEX IDX_3A29410EBE04EA9 (job_id),
          INDEX IDX_3A29410E7C43360E (trait_flaw_id),
          INDEX IDX_3A29410E42FEF757 (trait_quality_id),
          UNIQUE INDEX UNIQ_3A29410E3700F4DC (domains_id),
          INDEX IDX_3A29410EA76ED395 (user_id),
          INDEX IDX_3A29410EE48FD905 (game_id),
          UNIQUE INDEX idcUnique (name_slug, user_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_armors (
          characters_id INT NOT NULL,
          armors_id INT NOT NULL,
          INDEX IDX_91F54665C70F0E28 (characters_id),
          UNIQUE INDEX UNIQ_91F54665333F27F1 (armors_id),
          PRIMARY KEY(characters_id, armors_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_artifacts (
          characters_id INT NOT NULL,
          artifacts_id INT NOT NULL,
          INDEX IDX_59084303C70F0E28 (characters_id),
          UNIQUE INDEX UNIQ_5908430338F3D9E1 (artifacts_id),
          PRIMARY KEY(characters_id, artifacts_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_ogham (
          characters_id INT NOT NULL,
          ogham_id INT NOT NULL,
          INDEX IDX_53F77947C70F0E28 (characters_id),
          UNIQUE INDEX UNIQ_53F779473241FF23 (ogham_id),
          PRIMARY KEY(characters_id, ogham_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_weapons (
          characters_id INT NOT NULL,
          weapons_id INT NOT NULL,
          INDEX IDX_1A82C2BAC70F0E28 (characters_id),
          UNIQUE INDEX UNIQ_1A82C2BA2EE82581 (weapons_id),
          PRIMARY KEY(characters_id, weapons_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_combat_arts (
          characters_id INT NOT NULL,
          combat_arts_id INT NOT NULL,
          INDEX IDX_4423FA34C70F0E28 (characters_id),
          UNIQUE INDEX UNIQ_4423FA342E5E3C78 (combat_arts_id),
          PRIMARY KEY(characters_id, combat_arts_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_avantages (
          character_id INT NOT NULL,
          advantage_id INT NOT NULL,
          score INT NOT NULL,
          indication VARCHAR(255) NOT NULL,
          INDEX IDX_BB5181061136BE75 (character_id),
          INDEX IDX_BB5181063864498A (advantage_id),
          PRIMARY KEY(character_id, advantage_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_disciplines (
          domain VARCHAR(100) NOT NULL,
          character_id INT NOT NULL,
          discipline_id INT NOT NULL,
          score INT NOT NULL,
          INDEX IDX_50099411136BE75 (character_id),
          INDEX IDX_5009941A5522701 (discipline_id),
          PRIMARY KEY(
            character_id, discipline_id, domain
          )
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_domains (
          id INT AUTO_INCREMENT NOT NULL,
          character_id INT DEFAULT NULL,
          craft SMALLINT NOT NULL,
          close_combat SMALLINT NOT NULL,
          stealth SMALLINT NOT NULL,
          magience SMALLINT NOT NULL,
          natural_environment SMALLINT NOT NULL,
          demorthen_mysteries SMALLINT NOT NULL,
          occultism SMALLINT NOT NULL,
          perception SMALLINT NOT NULL,
          prayer SMALLINT NOT NULL,
          feats SMALLINT NOT NULL,
          relation SMALLINT NOT NULL,
          performance SMALLINT NOT NULL,
          science SMALLINT NOT NULL,
          shooting_and_throwing SMALLINT NOT NULL,
          travel SMALLINT NOT NULL,
          erudition SMALLINT NOT NULL,
          UNIQUE INDEX UNIQ_C4F7C6C61136BE75 (character_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_flux (
          flux INT NOT NULL,
          character_id INT NOT NULL,
          quantity SMALLINT NOT NULL,
          INDEX IDX_A1DA630E1136BE75 (character_id),
          PRIMARY KEY(character_id, flux)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_miracles (
          character_id INT NOT NULL,
          miracle_id INT NOT NULL,
          is_major TINYINT(1) NOT NULL,
          INDEX IDX_977340CA1136BE75 (character_id),
          INDEX IDX_977340CAB3BDFD5E (miracle_id),
          PRIMARY KEY(character_id, miracle_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_setbacks (
          character_id INT NOT NULL,
          setback_id INT NOT NULL,
          is_avoided TINYINT(1) NOT NULL,
          INDEX IDX_97CD32521136BE75 (character_id),
          INDEX IDX_97CD3252B42EEDE2 (setback_id),
          PRIMARY KEY(character_id, setback_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE characters_ways (
          id INT AUTO_INCREMENT NOT NULL,
          character_id INT DEFAULT NULL,
          combativeness SMALLINT NOT NULL,
          creativity SMALLINT NOT NULL,
          empathy SMALLINT NOT NULL,
          reason SMALLINT NOT NULL,
          conviction SMALLINT NOT NULL,
          UNIQUE INDEX UNIQ_7AC056231136BE75 (character_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE combat_arts (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(255) NOT NULL,
          description LONGTEXT NOT NULL,
          ranged TINYINT(1) NOT NULL,
          melee TINYINT(1) NOT NULL,
          INDEX IDX_EC3E3FAD16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE disciplines (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(50) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          rank VARCHAR(40) NOT NULL,
          domains LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\',
          UNIQUE INDEX UNIQ_AD1D5CD85E237E06 (name),
          INDEX IDX_AD1D5CD816A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE disorders (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(100) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          UNIQUE INDEX UNIQ_A14FE96D5E237E06 (name),
          INDEX IDX_A14FE96D16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE disorders_ways (
          way VARCHAR(255) NOT NULL,
          disorder_id INT NOT NULL,
          major TINYINT(1) DEFAULT \'0\' NOT NULL,
          INDEX IDX_F2628E1787EB36AD (disorder_id),
          PRIMARY KEY(disorder_id, way)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ext_log_entries (
          id INT AUTO_INCREMENT NOT NULL,
          action VARCHAR(8) NOT NULL,
          logged_at DATETIME NOT NULL,
          object_id VARCHAR(64) DEFAULT NULL,
          object_class VARCHAR(191) NOT NULL,
          version INT NOT NULL,
          data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\',
          username VARCHAR(191) DEFAULT NULL,
          INDEX log_class_lookup_idx (object_class),
          INDEX log_date_lookup_idx (logged_at),
          INDEX log_user_lookup_idx (username),
          INDEX log_version_lookup_idx (object_id, object_class, version),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');

        $this->addSql('CREATE TABLE ext_translations (
          id INT AUTO_INCREMENT NOT NULL,
          locale VARCHAR(8) NOT NULL,
          object_class VARCHAR(191) NOT NULL,
          field VARCHAR(32) NOT NULL,
          foreign_key VARCHAR(64) NOT NULL,
          content LONGTEXT DEFAULT NULL,
          INDEX translations_lookup_idx (
            locale, object_class, foreign_key
          ),
          UNIQUE INDEX lookup_unique_idx (
            locale, object_class, field, foreign_key
          ),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');

        $this->addSql('CREATE TABLE flux (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(70) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          UNIQUE INDEX UNIQ_7252313A5E237E06 (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE fos_user_user (
          id INT AUTO_INCREMENT NOT NULL,
          username VARCHAR(255) NOT NULL,
          username_canonical VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          email_canonical VARCHAR(255) NOT NULL,
          password VARCHAR(255) NOT NULL,
          confirmation_token VARCHAR(255) DEFAULT NULL,
          roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
          email_confirmed TINYINT(1) DEFAULT \'0\' NOT NULL,
          created_at DATETIME NOT NULL,
          updated_at DATETIME NOT NULL,
          UNIQUE INDEX UNIQ_C560D76192FC23A8 (username_canonical),
          UNIQUE INDEX UNIQ_C560D761A0D96FBF (email_canonical),
          UNIQUE INDEX UNIQ_C560D761C05FB297 (confirmation_token),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE game_invitations (
          id INT AUTO_INCREMENT NOT NULL,
          game_id INT NOT NULL,
          character_id INT NOT NULL,
          token VARCHAR(43) NOT NULL,
          INDEX IDX_FD7252ACE48FD905 (game_id),
          INDEX IDX_FD7252AC1136BE75 (character_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE games (
          id INT AUTO_INCREMENT NOT NULL,
          game_master_id INT NOT NULL,
          name VARCHAR(140) NOT NULL,
          summary LONGTEXT DEFAULT NULL,
          gm_notes LONGTEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          updated_at DATETIME NOT NULL,
          INDEX IDX_FF232B31C1151A13 (game_master_id),
          UNIQUE INDEX idgUnique (name, game_master_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE geo_environments (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(255) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          domain VARCHAR(100) NOT NULL,
          INDEX IDX_18F4720A16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE jobs (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(140) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          daily_salary INT DEFAULT 0 NOT NULL,
          primary_domain VARCHAR(100) NOT NULL,
          secondary_domains LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\',
          UNIQUE INDEX UNIQ_A8936DC55E237E06 (name),
          INDEX IDX_A8936DC516A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE miracles (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(70) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          INDEX IDX_6B8244CF16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE minor_miracles_jobs (
          miracle_id INT NOT NULL,
          job_id INT NOT NULL,
          INDEX IDX_2201D41CB3BDFD5E (miracle_id),
          INDEX IDX_2201D41CBE04EA9 (job_id),
          PRIMARY KEY(miracle_id, job_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE major_miracles_jobs (
          miracle_id INT NOT NULL,
          job_id INT NOT NULL,
          INDEX IDX_46585DDFB3BDFD5E (miracle_id),
          INDEX IDX_46585DDFBE04EA9 (job_id),
          PRIMARY KEY(miracle_id, job_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ogham (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(70) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          type TINYTEXT NOT NULL,
          UNIQUE INDEX UNIQ_729005A05E237E06 (name),
          INDEX IDX_729005A016A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE peoples (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(255) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          INDEX IDX_C92B5C9C16A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE setbacks (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(50) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          malus VARCHAR(50) DEFAULT \'\' NOT NULL,
          is_unlucky TINYINT(1) DEFAULT \'0\' NOT NULL,
          is_lucky TINYINT(1) DEFAULT \'0\' NOT NULL,
          UNIQUE INDEX UNIQ_6B3C36575E237E06 (name),
          INDEX IDX_6B3C365716A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE setbacks_advantages (
          setback_id INT NOT NULL,
          advantage_id INT NOT NULL,
          INDEX IDX_A85197DFB42EEDE2 (setback_id),
          INDEX IDX_A85197DF3864498A (advantage_id),
          PRIMARY KEY(setback_id, advantage_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE social_class (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(25) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          domains LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\',
          UNIQUE INDEX UNIQ_A7DBAD0D5E237E06 (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE traits (
          id INT AUTO_INCREMENT NOT NULL,
          book_id INT DEFAULT NULL,
          name VARCHAR(50) NOT NULL,
          name_female VARCHAR(50) NOT NULL,
          is_quality TINYINT(1) NOT NULL,
          is_major TINYINT(1) NOT NULL,
          way VARCHAR(255) NOT NULL,
          INDEX IDX_E4A0A16616A2B381 (book_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE weapons (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(50) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          damage SMALLINT NOT NULL,
          price SMALLINT NOT NULL,
          availability VARCHAR(5) NOT NULL,
          melee TINYINT(1) NOT NULL,
          weapon_range SMALLINT NOT NULL,
          UNIQUE INDEX UNIQ_520EBBE15E237E06 (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE armors ADD CONSTRAINT FK_AFBA56C216A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE artifacts ADD CONSTRAINT FK_299E4688C85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE avantages ADD CONSTRAINT FK_CBC7848D16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E8B7556B0 FOREIGN KEY (geo_living_id) REFERENCES geo_environments (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E63437CCA FOREIGN KEY (ways_id) REFERENCES characters_ways (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E3147C936 FOREIGN KEY (people_id) REFERENCES peoples (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E64319F3C FOREIGN KEY (social_class_id) REFERENCES social_class (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E46CBF851 FOREIGN KEY (mental_disorder_id) REFERENCES disorders (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E7C43360E FOREIGN KEY (trait_flaw_id) REFERENCES traits (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E42FEF757 FOREIGN KEY (trait_quality_id) REFERENCES traits (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E3700F4DC FOREIGN KEY (domains_id) REFERENCES characters_domains (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EE48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE characters_armors ADD CONSTRAINT FK_91F54665C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_armors ADD CONSTRAINT FK_91F54665333F27F1 FOREIGN KEY (armors_id) REFERENCES armors (id)');
        $this->addSql('ALTER TABLE characters_artifacts ADD CONSTRAINT FK_59084303C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_artifacts ADD CONSTRAINT FK_5908430338F3D9E1 FOREIGN KEY (artifacts_id) REFERENCES artifacts (id)');
        $this->addSql('ALTER TABLE characters_ogham ADD CONSTRAINT FK_53F77947C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_ogham ADD CONSTRAINT FK_53F779473241FF23 FOREIGN KEY (ogham_id) REFERENCES ogham (id)');
        $this->addSql('ALTER TABLE characters_weapons ADD CONSTRAINT FK_1A82C2BAC70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_weapons ADD CONSTRAINT FK_1A82C2BA2EE82581 FOREIGN KEY (weapons_id) REFERENCES weapons (id)');
        $this->addSql('ALTER TABLE characters_combat_arts ADD CONSTRAINT FK_4423FA34C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_combat_arts ADD CONSTRAINT FK_4423FA342E5E3C78 FOREIGN KEY (combat_arts_id) REFERENCES combat_arts (id)');
        $this->addSql('ALTER TABLE characters_avantages ADD CONSTRAINT FK_BB5181061136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_avantages ADD CONSTRAINT FK_BB5181063864498A FOREIGN KEY (advantage_id) REFERENCES avantages (id)');
        $this->addSql('ALTER TABLE characters_disciplines ADD CONSTRAINT FK_50099411136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_disciplines ADD CONSTRAINT FK_5009941A5522701 FOREIGN KEY (discipline_id) REFERENCES disciplines (id)');
        $this->addSql('ALTER TABLE characters_domains ADD CONSTRAINT FK_C4F7C6C61136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_flux ADD CONSTRAINT FK_A1DA630E1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_miracles ADD CONSTRAINT FK_977340CA1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_miracles ADD CONSTRAINT FK_977340CAB3BDFD5E FOREIGN KEY (miracle_id) REFERENCES miracles (id)');
        $this->addSql('ALTER TABLE characters_setbacks ADD CONSTRAINT FK_97CD32521136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_setbacks ADD CONSTRAINT FK_97CD3252B42EEDE2 FOREIGN KEY (setback_id) REFERENCES setbacks (id)');
        $this->addSql('ALTER TABLE characters_ways ADD CONSTRAINT FK_7AC056231136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE combat_arts ADD CONSTRAINT FK_EC3E3FAD16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE disciplines ADD CONSTRAINT FK_AD1D5CD816A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE disorders ADD CONSTRAINT FK_A14FE96D16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE disorders_ways ADD CONSTRAINT FK_F2628E1787EB36AD FOREIGN KEY (disorder_id) REFERENCES disorders (id)');
        $this->addSql('ALTER TABLE game_invitations ADD CONSTRAINT FK_FD7252ACE48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE game_invitations ADD CONSTRAINT FK_FD7252AC1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31C1151A13 FOREIGN KEY (game_master_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE geo_environments ADD CONSTRAINT FK_18F4720A16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC516A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE miracles ADD CONSTRAINT FK_6B8244CF16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE minor_miracles_jobs ADD CONSTRAINT FK_2201D41CB3BDFD5E FOREIGN KEY (miracle_id) REFERENCES miracles (id)');
        $this->addSql('ALTER TABLE minor_miracles_jobs ADD CONSTRAINT FK_2201D41CBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE major_miracles_jobs ADD CONSTRAINT FK_46585DDFB3BDFD5E FOREIGN KEY (miracle_id) REFERENCES miracles (id)');
        $this->addSql('ALTER TABLE major_miracles_jobs ADD CONSTRAINT FK_46585DDFBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE ogham ADD CONSTRAINT FK_729005A016A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE peoples ADD CONSTRAINT FK_C92B5C9C16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE setbacks ADD CONSTRAINT FK_6B3C365716A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE setbacks_advantages ADD CONSTRAINT FK_A85197DFB42EEDE2 FOREIGN KEY (setback_id) REFERENCES setbacks (id)');
        $this->addSql('ALTER TABLE setbacks_advantages ADD CONSTRAINT FK_A85197DF3864498A FOREIGN KEY (advantage_id) REFERENCES avantages (id)');
        $this->addSql('ALTER TABLE traits ADD CONSTRAINT FK_E4A0A16616A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters_armors DROP FOREIGN KEY FK_91F54665333F27F1');
        $this->addSql('ALTER TABLE characters_artifacts DROP FOREIGN KEY FK_5908430338F3D9E1');
        $this->addSql('ALTER TABLE characters_avantages DROP FOREIGN KEY FK_BB5181063864498A');
        $this->addSql('ALTER TABLE setbacks_advantages DROP FOREIGN KEY FK_A85197DF3864498A');
        $this->addSql('ALTER TABLE armors DROP FOREIGN KEY FK_AFBA56C216A2B381');
        $this->addSql('ALTER TABLE avantages DROP FOREIGN KEY FK_CBC7848D16A2B381');
        $this->addSql('ALTER TABLE combat_arts DROP FOREIGN KEY FK_EC3E3FAD16A2B381');
        $this->addSql('ALTER TABLE disciplines DROP FOREIGN KEY FK_AD1D5CD816A2B381');
        $this->addSql('ALTER TABLE disorders DROP FOREIGN KEY FK_A14FE96D16A2B381');
        $this->addSql('ALTER TABLE geo_environments DROP FOREIGN KEY FK_18F4720A16A2B381');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC516A2B381');
        $this->addSql('ALTER TABLE miracles DROP FOREIGN KEY FK_6B8244CF16A2B381');
        $this->addSql('ALTER TABLE ogham DROP FOREIGN KEY FK_729005A016A2B381');
        $this->addSql('ALTER TABLE peoples DROP FOREIGN KEY FK_C92B5C9C16A2B381');
        $this->addSql('ALTER TABLE setbacks DROP FOREIGN KEY FK_6B3C365716A2B381');
        $this->addSql('ALTER TABLE traits DROP FOREIGN KEY FK_E4A0A16616A2B381');
        $this->addSql('ALTER TABLE characters_armors DROP FOREIGN KEY FK_91F54665C70F0E28');
        $this->addSql('ALTER TABLE characters_artifacts DROP FOREIGN KEY FK_59084303C70F0E28');
        $this->addSql('ALTER TABLE characters_ogham DROP FOREIGN KEY FK_53F77947C70F0E28');
        $this->addSql('ALTER TABLE characters_weapons DROP FOREIGN KEY FK_1A82C2BAC70F0E28');
        $this->addSql('ALTER TABLE characters_combat_arts DROP FOREIGN KEY FK_4423FA34C70F0E28');
        $this->addSql('ALTER TABLE characters_avantages DROP FOREIGN KEY FK_BB5181061136BE75');
        $this->addSql('ALTER TABLE characters_disciplines DROP FOREIGN KEY FK_50099411136BE75');
        $this->addSql('ALTER TABLE characters_domains DROP FOREIGN KEY FK_C4F7C6C61136BE75');
        $this->addSql('ALTER TABLE characters_flux DROP FOREIGN KEY FK_A1DA630E1136BE75');
        $this->addSql('ALTER TABLE characters_miracles DROP FOREIGN KEY FK_977340CA1136BE75');
        $this->addSql('ALTER TABLE characters_setbacks DROP FOREIGN KEY FK_97CD32521136BE75');
        $this->addSql('ALTER TABLE characters_ways DROP FOREIGN KEY FK_7AC056231136BE75');
        $this->addSql('ALTER TABLE game_invitations DROP FOREIGN KEY FK_FD7252AC1136BE75');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E3700F4DC');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E63437CCA');
        $this->addSql('ALTER TABLE characters_combat_arts DROP FOREIGN KEY FK_4423FA342E5E3C78');
        $this->addSql('ALTER TABLE characters_disciplines DROP FOREIGN KEY FK_5009941A5522701');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E46CBF851');
        $this->addSql('ALTER TABLE disorders_ways DROP FOREIGN KEY FK_F2628E1787EB36AD');
        $this->addSql('ALTER TABLE artifacts DROP FOREIGN KEY FK_299E4688C85926E');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EA76ED395');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31C1151A13');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EE48FD905');
        $this->addSql('ALTER TABLE game_invitations DROP FOREIGN KEY FK_FD7252ACE48FD905');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E8B7556B0');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EBE04EA9');
        $this->addSql('ALTER TABLE minor_miracles_jobs DROP FOREIGN KEY FK_2201D41CBE04EA9');
        $this->addSql('ALTER TABLE major_miracles_jobs DROP FOREIGN KEY FK_46585DDFBE04EA9');
        $this->addSql('ALTER TABLE characters_miracles DROP FOREIGN KEY FK_977340CAB3BDFD5E');
        $this->addSql('ALTER TABLE minor_miracles_jobs DROP FOREIGN KEY FK_2201D41CB3BDFD5E');
        $this->addSql('ALTER TABLE major_miracles_jobs DROP FOREIGN KEY FK_46585DDFB3BDFD5E');
        $this->addSql('ALTER TABLE characters_ogham DROP FOREIGN KEY FK_53F779473241FF23');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E3147C936');
        $this->addSql('ALTER TABLE characters_setbacks DROP FOREIGN KEY FK_97CD3252B42EEDE2');
        $this->addSql('ALTER TABLE setbacks_advantages DROP FOREIGN KEY FK_A85197DFB42EEDE2');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E64319F3C');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E7C43360E');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E42FEF757');
        $this->addSql('ALTER TABLE characters_weapons DROP FOREIGN KEY FK_1A82C2BA2EE82581');
        $this->addSql('DROP TABLE armors');
        $this->addSql('DROP TABLE artifacts');
        $this->addSql('DROP TABLE avantages');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE characters');
        $this->addSql('DROP TABLE characters_armors');
        $this->addSql('DROP TABLE characters_artifacts');
        $this->addSql('DROP TABLE characters_ogham');
        $this->addSql('DROP TABLE characters_weapons');
        $this->addSql('DROP TABLE characters_combat_arts');
        $this->addSql('DROP TABLE characters_avantages');
        $this->addSql('DROP TABLE characters_disciplines');
        $this->addSql('DROP TABLE characters_domains');
        $this->addSql('DROP TABLE characters_flux');
        $this->addSql('DROP TABLE characters_miracles');
        $this->addSql('DROP TABLE characters_setbacks');
        $this->addSql('DROP TABLE characters_ways');
        $this->addSql('DROP TABLE combat_arts');
        $this->addSql('DROP TABLE disciplines');
        $this->addSql('DROP TABLE disorders');
        $this->addSql('DROP TABLE disorders_ways');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE flux');
        $this->addSql('DROP TABLE fos_user_user');
        $this->addSql('DROP TABLE game_invitations');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE geo_environments');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE miracles');
        $this->addSql('DROP TABLE minor_miracles_jobs');
        $this->addSql('DROP TABLE major_miracles_jobs');
        $this->addSql('DROP TABLE ogham');
        $this->addSql('DROP TABLE peoples');
        $this->addSql('DROP TABLE setbacks');
        $this->addSql('DROP TABLE setbacks_advantages');
        $this->addSql('DROP TABLE social_class');
        $this->addSql('DROP TABLE traits');
        $this->addSql('DROP TABLE weapons');
    }
}
