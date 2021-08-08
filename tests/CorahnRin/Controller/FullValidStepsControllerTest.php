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

namespace Tests\CorahnRin\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tests\GetHttpClientTestTrait;

/**
 * The goal here is to create one single character to make sure the whole step process is working correctly.
 * Specific steps tests and validations are made in the "Step" namespace, so check the directory next to this one.
 */
class FullValidStepsControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @see StepController::indexAction
     * @group functional
     */
    public function testIndex(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        $client->request('GET', '/fr/character/generate');

        static::assertSame(302, $client->getResponse()->getStatusCode());

        static::assertTrue(
            $client->getResponse()->isRedirect('/fr/character/generate/01_people'),
            'Could not check that generator index redirects to first step'
        );
    }

    /**
     * @see StepController::stepAction
     * @dataProvider provide valid steps from file
     * @group functional
     */
    public function test all valid steps from file(array $steps): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        foreach ($steps as $stepName => $step) {
            if (!\array_key_exists('route_uri', $step)) {
                $step['route_uri'] = $stepName;
            }
            $step['step'] = $stepName;

            $this->doTestOneStep(
                $stepName,
                $step['route_uri'],
                $step['next_step'],
                $step['form_values'],
                $step['session_value'],
                $client
            );

            if ('20_finish' === $step['next_step']) {
                // Finished generation.
                // Just make sure that the final request doesn't throw an error when showing character.

                $crawler = $client->request('GET', '/fr/character/generate/'.$step['next_step']);

                // If it's not 200, session is probably invalid.
                $statusCode = $client->getResponse()->getStatusCode();
                $errorBlock = $crawler->filter('title');

                $msg = 'Could not execute final step request...';
                $msg .= $errorBlock->count() ? ("\n".$errorBlock->text('', true)) : '';
                static::assertSame(200, $statusCode, $msg);

                return;
            }
        }
    }

    public static function provide valid steps from file()
    {
        /** @var Finder|SplFileInfo[] $files */
        $files = (new Finder())->name('*.php')->in(__DIR__.'/full_valid_steps/');

        foreach ($files as $file) {
            $steps = require $file;
            yield $file->getBasename('.php') => [$steps];
        }
    }

    private function doTestOneStep(
        $stepName,
        $routeUri,
        $nextStep,
        array $formValues,
        $expectedSessionValue,
        Client $client
    ): void {
        if (!$formValues && !$expectedSessionValue) {
            static::markTestIncomplete('Missing form values for step '.$stepName);
        }
        // We need a simple session to be sure it's updated when submitting form.
        $session = $client->getContainer()->get('session');

        // Make the request.
        $crawler = $client->request('GET', '/fr/character/generate/'.$routeUri);

        // If it's not 200, session is probably invalid.
        $statusCode = $client->getResponse()->getStatusCode();
        $errorBlock = $crawler->filter('title');

        $msg = 'Could not execute step request...';
        $msg .= $errorBlock->count() ? ("\n".$errorBlock->text('', true)) : '';
        static::assertSame(200, $statusCode, $msg);

        // Prepare form values.
        $form = $crawler->filter('#generator_form')->form();

        // Sometimes, form can't be submitted properly,
        // like with "orientation" step.
        if ($formValues) {
            try {
                $form->setValues($formValues);
            } catch (\Exception $e) {
                static::fail($e->getMessage()."\nWith values:\n".\str_replace("\n", '', \var_export($formValues, true)));
            }
        }

        // Here, if the redirection is made for the next step, it means everything's valid.
        $crawler = $client->submit($form);

        // Parse better message to show in phpunit's output if there is an error in the submitted form.
        $msg = 'Request does not redirect to next step "'.$nextStep.'".';
        if ($crawler->filter('#flash-messages')->count()) {
            $msg .= $crawler->filter('#flash-messages')->text('', true);
        }

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/'.$nextStep), $msg);

        // We also make sure that the session has been correctly updated.
        $character = $session->get('character.corahn_rin');
        static::assertEquals(
            $expectedSessionValue,
            $character[$stepName],
            'Character values are not equal to session ones in step "'.$stepName.'"...'
        );
    }
}
