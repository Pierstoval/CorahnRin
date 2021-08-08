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

namespace Main\Controller\Dev;

use Main\DependencyInjection\PublicService;
use Main\Model\ContactMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class EmailTemplateController implements PublicService
{
    public function __construct(private Environment $twig)
    {
    }

    /**
     * Route is located in config/routes/dev/templates.yaml.
     * No annotation here because it's only for debug in dev.
     */
    public function __invoke(Request $request): Response
    {
        $message = new ContactMessage();

        $message->setName($request->get('name', 'John Doe'));
        $message->setEmail($request->get('email', 'john@doe.com'));
        $message->setSubject($request->get('subject', ContactMessage::SUBJECT_APPLICATION));
        $message->setTitle($request->get('title', 'This is just a contact email'));
        $message->setMessage($request->get('message', "Lorem ipsum dolor sit amet, consectetur adipisicing elit.\nAccusantium aspernatur assumenda blanditiis ducimus eligendi eveniet fugiat laborum magni maxime quae reiciendis, veritatis, vitae voluptas.\nAd et excepturi facilis recusandae ullam."));

        return new Response($this->twig->render('email/contact_email.html.twig', [
            'message' => $message,
        ]));
    }
}
