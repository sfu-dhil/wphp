<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use Nines\MediaBundle\Repository\PdfRepository;
use Nines\MediaBundle\Service\PdfManager;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostTest extends ControllerTestCase {
    // Change this to HTTP_OK when the site is public.
    private const ANON_RESPONSE_CODE = Response::HTTP_OK;

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('New')->count());
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
    }

    public function testAnonSearch() : void {
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        if (self::ANON_RESPONSE_CODE === Response::HTTP_FOUND) {
            // If authentication is required stop here.
            return;
        }

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testUserSearch() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminSearch() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/1', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testAnonNewPopup() : void {
        $crawler = $this->client->request('GET', '/blog/post/new_popup');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testUserNewPopup() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/new_popup');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/6', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAdminNewPopup() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/7', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAnonNewPdf() : void {
        $crawler = $this->client->request('GET', '/blog/post/1/new_pdf');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserNewPdf() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1/new_pdf');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNewPdf() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/1/new_pdf');
        $this->assertResponseIsSuccessful();

        $manager = self::$container->get(PdfManager::class);
        $manager->setCopy(true);

        $form = $crawler->selectButton('Create')->form([
            'pdf[public]' => 1,
            'pdf[description]' => 'Description',
            'pdf[license]' => 'License',
        ]);
        $form['pdf[file]']->upload(dirname(__FILE__, 3) . '/vendor/ubermichael/nines/MediaBundle/Tests/data/pdf/holmes_2.pdf');
        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/1');
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $manager->setCopy(false);
    }

    public function testAnonEditPdf() : void {
        $crawler = $this->client->request('GET', '/blog/post/1/edit_pdf/1');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserEditPdf() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1/edit_pdf/1');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditPdf() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/1/edit_pdf/1');
        $this->assertResponseIsSuccessful();

        $manager = self::$container->get(PdfManager::class);
        $manager->setCopy(true);

        $form = $crawler->selectButton('Update')->form([
            'pdf[public]' => 0,
            'pdf[description]' => 'Updated Description',
            'pdf[license]' => 'Updated License',
        ]);
        $form['pdf[newFile]']->upload(dirname(__FILE__, 3) . '/vendor/ubermichael/nines/MediaBundle/Tests/data/pdf/holmes_2.pdf');
        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/1');
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $manager->setCopy(false);
    }

    public function testAnonDeletePdf() : void {
        $crawler = $this->client->request('DELETE', '/blog/post/1/delete_pdf/1');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserDeletePdf() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('DELETE', '/blog/post/1/delete_pdf/1');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDeletePdf() : void {
        $repo = self::$container->get(PdfRepository::class);
        $preCount = count($repo->findAll());

        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/4');
        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form.delete-form[action="/blog/post/4/delete_pdf/4"]')->form();
        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/4');
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->em->clear();
        $postCount = count($repo->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAdminDeleteWrongPdf() : void {
        $repo = self::$container->get(PdfRepository::class);
        $preCount = count($repo->findAll());

        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/4');
        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form.delete-form[action="/blog/post/4/delete_pdf/4"]')->form();
        $form->getNode()->setAttribute('action', '/blog/post/3/delete_pdf/4');

        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $this->em->clear();
        $postCount = count($repo->findAll());
        $this->assertSame($preCount, $postCount);
    }
}
