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

namespace Tests\CorahnRin\Step;

use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

abstract class AbstractStepTest extends WebTestCase
{
    use GetHttpClientTestTrait {
        getHttpClient as baseGetClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function getHttpClient(): Client
    {
        $client = $this->baseGetClient();

        // Make a first request so that the Session object is instantiated.
        $client->request('GET', '/fr/character/generate');
        static::assertResponseRedirects('/fr/character/generate/01_people');

        return $client;
    }

    protected function getStepName(): string
    {
        return \preg_replace_callback('~^Tests\\\\CorahnRin\\\\Step\\\\Step(.+)Test$~isUu', function ($matches) {
            return \preg_replace_callback('~[A-Z]~', function ($matches) {
                return '_'.\mb_strtolower($matches[0]);
            }, $matches[1]);
        }, static::class);
    }

    protected function submitAction(array $sessionValues = [], array $formValues = [], string $queryString = ''): StepActionTestResult
    {
        $client = $this->getHttpClient();

        // We need a simple session to be sure it's updated when submitting form.
        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', $sessionValues);
        $session->save();

        // Make the request.
        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName().$queryString);

        // If it's not 200, it certainly session is invalid.
        $statusCode = $client->getResponse()->getStatusCode();
        $errorBlock = $crawler->filter('title');

        $msg = 'Could not execute step request...';
        $msg .= $errorBlock->count() ? ("\n".$errorBlock->text('', true)) : (' For step "'.$this->getStepName().'"');
        static::assertSame(200, $statusCode, $msg);

        // Prepare form values.
        $form = $crawler->filter('#generator_form')->form();

        // Sometimes, form can't be submitted properly,
        // like with "orientation" step.
        if ($formValues) {
            try {
                $form
                    ->disableValidation()
                    ->setValues($formValues)
                ;
            } catch (\Exception $e) {
                static::fail($e->getMessage()."\nWith values:\n".\preg_replace('~\s\s+~', ' ', \str_replace(["\r", "\n"], ' ', \json_encode($formValues))));
            }
        }

        $crawler = $client->submit($form);

        // Here, if the redirection is made for the next step, it means everything's valid.
        return new StepActionTestResult($crawler, $client);
    }
}
