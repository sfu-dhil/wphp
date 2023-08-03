<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nines\MediaBundle\Entity\Pdf;
use Nines\MediaBundle\Service\PdfManager;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    final public const FILES = [
        'holmes_1.pdf',
        'holmes_2.pdf',
        'holmes_3.pdf',
        'holmes_4.pdf',
        'holmes_5.pdf',
    ];

    private ?PdfManager $pdfManager = null;

    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        $this->pdfManager->setCopy(true);
        for ($i = 1; $i <= 5; $i++) {
            $fixture = new Post();
            $fixture->setIncludeComments(0 === $i % 2);
            $fixture->setTitle('Title ' . $i);
            $fixture->setExcerpt("<p>This is paragraph {$i}</p>");
            $fixture->setContent("<p>This is paragraph {$i}</p>");
            $fixture->setCategory($this->getReference('postcategory.' . $i));
            $fixture->setStatus($this->getReference('poststatus.' . $i));
            $fixture->setUser($this->getReference('user.inactive'));
            $manager->persist($fixture);
            $manager->flush();

            $file = self::FILES[$i - 1];
            $upload = new UploadedFile(dirname(__DIR__, 2) . '/vendor/sfu-dhil/nines/MediaBundle/Tests/data/pdf/' . $file, $file, 'application/pdf', null, true);
            $pdf = new Pdf();
            $pdf->setFile($upload);
            $pdf->setOriginalName($file);
            $pdf->setDescription("<p>This is paragraph {$i}</p>");
            $pdf->setLicense("<p>This is paragraph {$i}</p>");
            $pdf->setEntity($fixture);
            $manager->persist($pdf);
            $manager->flush();

            $this->setReference('post.' . $i, $fixture);
        }
        $this->pdfManager->setCopy(false);
    }

    /**
     * @return array<string>
     */
    public function getDependencies() : array {
        return [
            PostCategoryFixtures::class,
            PostStatusFixtures::class,
            UserFixtures::class,
        ];
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setPdfManager(PdfManager $pdfManager) : void {
        $this->pdfManager = $pdfManager;
    }
}
