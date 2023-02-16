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

namespace Tests\Admin;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\GetHttpClientTestTrait;

abstract class AbstractEasyAdminTest extends WebTestCase
{
    use GetHttpClientTestTrait {
        getHttpClient as baseGetClient;
    }

    /**
     * Returns the entity name in the backend.
     *
     * @return string
     */
    abstract public function getEntityName();

    /**
     * The entity full qualified class name to be used when managing entities.
     *
     * @return string
     */
    abstract public function getEntityClass();

    /**
     * Returns the list of fields you expect to see in the backend.
     * Return "false" if you don't want to test native listing.
     *
     * @return array|false
     */
    abstract public function provideListingFields();

    /**
     * A simple array of data to submit in the "new" form.
     * Keys of the array must correspond to the property field as specified in EasyAdmin config.
     * Return "false" if you don't want to test "new" form.
     *
     * @return array|false
     */
    abstract public function provideNewFormData();

    /**
     * A simple object to submit in the "edit" form.
     * Keys of the array must correspond to the property field as specified in EasyAdmin config.
     * Must specify an "id" attribute, else it will fail.
     * To be sure the test works, you might need to add fixtures with proper ID forced in the database.
     * Return "false" if you don't want to test "edit" form.
     *
     * @return array|false
     */
    abstract public function provideEditFormData();

    /**
     * Provides an ID to test the "delete" action.
     * To be sure the test works, you might need to add fixtures with proper ID forced in the database.
     * Return "false" if you don't want to test "delete" form.
     *
     * @return false|int
     */
    public function provideIdToDelete()
    {
        return false;
    }

    /**
     * @group functional
     */
    public function testListingFields(): void
    {
        $client = $this->getHttpClient();

        $entityName = $this->getEntityName();

        $crawler = $client->request('GET', "/fr/admin/{$entityName}/list");

        $wishedColumns = $this->provideListingFields();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $wishedColumns) {
            static::assertTrue(true);

            return;
        }

        if (!$wishedColumns) {
            static::markTestIncomplete('No columns to test the listing page.');
        }

        static::assertSame(200, $client->getResponse()->getStatusCode(), $entityName);

        /** @var Crawler|\DOMElement[] $nodeHeaders */
        $nodeHeaders = $crawler->filter('#main table thead tr th[class]');

        static::assertCount(\count($wishedColumns), $nodeHeaders, $entityName);

        foreach ($nodeHeaders as $k => $node) {
            static::assertArrayHasKey($k, $wishedColumns, $entityName);
        }
    }

    /**
     * @group functional
     */
    public function testListingContentsIsNotEmpty(): void
    {
        $client = $this->getHttpClient();

        $entityName = $this->getEntityName();

        $crawler = $client->request('GET', "/fr/admin/{$entityName}/list");

        static::assertSame(200, $client->getResponse()->getStatusCode(), $entityName."\n".$crawler->filter('title')->text('', true));

        $count = $crawler->filter('#main table tr[data-id]')->count();

        if (0 === $count) {
            static::markTestIncomplete('No data to test the "list" action for the entity "'.$entityName.'"');
        }
    }

    /**
     * @group functional
     */
    public function testNewAction(): void
    {
        $data = $this->provideNewFormData();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $data) {
            static::assertTrue(true);

            return;
        }

        $this->submitData($data['data_to_submit'], $data['expected_data'], $data['search_data'], 'new');
    }

    /**
     * @depends testNewAction
     *
     * @group functional
     */
    public function testEditAction(): void
    {
        $data = $this->provideEditFormData();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $data) {
            static::assertTrue(true);

            return;
        }

        $this->submitData($data['data_to_submit'], $data['expected_data'], $data['search_data'], 'edit');
    }

    /**
     * @depends testNewAction
     *
     * @group functional
     */
    public function testDeleteAction(): void
    {
        $id = $this->provideIdToDelete();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $id) {
            static::assertTrue(true);

            return;
        }

        $entityName = $this->getEntityName();

        if (!$id) {
            static::markTestIncomplete('No data to test the "delete" action for entity "'.$entityName.'".');
        }

        $client = $this->getHttpClient();

        // We'll make the DELETE request starting from the EDIT page.

        $deleteForm = $client->request('DELETE', \sprintf('/fr/admin/%s/edit/%s?referer=/', $entityName, $id))->filter('#delete_form_submit');

        static::assertCount(1, $deleteForm, $entityName);

        $form = $deleteForm->form();

        $client->submit($form);

        // If redirects to list, it means it's correct, else it would redirect to "list" action.
        static::assertSame(302, $client->getResponse()->getStatusCode(), $entityName);
        static::assertSame('/', $client->getResponse()->headers->get('location'), $entityName);

        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $object = $em->find($this->getEntityClass(), $id);

        static::assertFalse((bool) $object, $entityName);
    }

    /**
     * @return object last entity submitted
     */
    protected function submitData(array $dataToSubmit, array $expectedData, array $searchData, string $view, KernelBrowser $client = null)
    {
        $id = $dataToSubmit['id'] ?? $expectedData['id'] ?? null;
        if ('edit' === $view && !$id) {
            static::fail('You must specify an ID for the edit mode.');
        }

        if (!$client) {
            // In case client is created before, we use it instead of restarting the kernel.
            $client = $this->getHttpClient();
        }

        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $entityName = $this->getEntityName();

        $crawler = $client->request('GET', "/fr/admin/{$entityName}/{$view}".($id ? "/{$id}" : ''));

        static::assertSame(200, $client->getResponse()->getStatusCode(), $entityName);

        $formEntityFieldName = \mb_strtolower($entityName);

        /** @var Crawler $formNode */
        $formNode = $crawler->filter(\sprintf('#%s-%s-form', $view, $formEntityFieldName));

        static::assertCount(1, $formNode, $entityName);

        $form = $formNode->form();

        foreach ($dataToSubmit as $field => $expectedValue) {
            if ('id' === $field || '.result-data-to-find' === $field) {
                continue;
            }
            $form
                ->get($formEntityFieldName.'['.$field.']')
                ->setValue($expectedValue instanceof UploadedFile ? $expectedValue->getRealPath() : $expectedValue)
            ;
        }

        $crawler = $client->submit($form);

        $response = $client->getResponse();

        $message = '';
        // If redirects to list, it means it's correct, else it would redirect to "new" action.
        if (200 === $response->getStatusCode()) {
            foreach ($crawler->filter('.invalid-feedback') as $error) {
                $message .= "\n".\trim($error->textContent);
            }
        } elseif (500 === $response->getStatusCode()) {
            foreach ($crawler->filter('.exception-message') as $error) {
                $message .= "\n".\trim($error->textContent);
            }
        }

        static::assertSame(302, $response->getStatusCode(), \sprintf(
            'Not redirecting after submitting %s action %s%s',
            $view,
            $entityName,
            $message
        ));
        static::assertSame("/fr/admin/{$entityName}/list", $response->headers->get('location'), $entityName);

        $crawler->clear();
        $client->followRedirect();

        static::assertSame(200, $client->getResponse()->getStatusCode(), $entityName);

        $lastEntity = $em->getRepository($this->getEntityClass())->findOneBy($searchData);

        static::assertNotNull($lastEntity);

        foreach ($expectedData as $field => $expectedValue) {
            if ('.result-data-to-find' === $field) {
                continue;
            }
            $methodExists = false;
            $methodName = null;

            if (\method_exists($lastEntity, 'get'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'get'.\ucfirst($field);
            } elseif (\method_exists($lastEntity, 'is'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'is'.\ucfirst($field);
            } elseif (\method_exists($lastEntity, 'has'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'has'.\ucfirst($field);
            }

            if ($methodExists) {
                $valueToCompare = $lastEntity->{$methodName}();
                if (\is_object($valueToCompare)) {
                    if ($valueToCompare instanceof \DateTimeInterface) {
                        $valueToCompare = $valueToCompare->format('Y-m-d H:i:s');
                    } elseif (\method_exists($valueToCompare, 'getId')) {
                        $valueToCompare = $valueToCompare->getId();
                    }
                }
                static::assertSame($expectedValue, $valueToCompare, 'Error for class property '.$entityName.'::$'.$field);
            } else {
                static::fail('No getter found for property '.$entityName.'::$'.$field.'.');
            }
        }

        return $lastEntity;
    }

    protected function getHttpClient(): KernelBrowser
    {
        $client = $this->baseGetClient();
        $this->loginAsUser($client, 'pierstoval');

        return $client;
    }
}
