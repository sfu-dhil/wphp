<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\AasMarc;
use App\Entity\En;
use App\Entity\EstcMarc;
use App\Entity\Jackson;
use App\Entity\OrlandoBiblio;
use App\Entity\OsborneMarc;
use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Construct links to various sources.
 */
class SourceLinker {
    /**
     * SourceLinker constructor.
     */
    public function __construct(private EntityManagerInterface $em, private UrlGeneratorInterface $generator, private RoleChecker $checker) {
    }

    /**
     * Set the role checker.
     */
    public function setRoleChecker(RoleChecker $checker) : void {
        $this->checker = $checker;
    }

    /**
     * Generate an ESTC link. The link will be internal if the user is logged in or to the ESTC website
     * otherwise.
     *
     * @param string $data
     *
     * @return null|string
     */
    public function estc($data) {
        if ( ! $this->checker->hasRole('ROLE_USER')) {
            return 'http://estc.bl.uk/' . $data;
        }
        $repo = $this->em->getRepository(EstcMarc::class);
        $record = $repo->findOneBy([
            'fieldData' => $data,
            'field' => '001',
        ]);

        if ($record) {
            return $this->generator->generate('resource_estc_show', [
                'id' => $record->getTitleId(),
            ]);
        }

    }

    /**
     * Generate an AAS link. The link will be internal if the user is logged in or to the AAS website
     * otherwise.
     *
     * @param string $data
     *
     * @return null|string
     */
    public function aas($data) {
        if ( ! $this->checker->hasRole('ROLE_USER')) {
            return 'https://catalog.mwa.org/vwebv/holdingsInfo?bibId=' . $data;
        }
        $repo = $this->em->getRepository(AasMarc::class);
        $record = $repo->findOneBy([
            'fieldData' => $data,
            'field' => '001',
        ]);
        if ($record) {
            return $this->generator->generate('resource_aas_show', [
                'id' => $record->getTitleId(),
            ]);
        }

    }

    /**
     * Generate an Orlando link. The link will be internal if the user is logged in or to the Orlando website
     * otherwise.
     */
    public function orlando(string $data) : ?string {
        if ( ! $this->checker->hasRole('ROLE_USER')) {
            return null;
        }
        $repo = $this->em->getRepository(OrlandoBiblio::class);
        $record = $repo->findOneBy([
            'orlandoId' => $data,
        ]);
        if ($record) {
            return $this->generator->generate('resource_orlando_biblio_show', [
                'id' => $record->getId(),
            ]);
        }

        return null;
    }

    /**
     * Generate a Jackson link. The link will be internal if the user is logged in or null otherwise.
     *
     * @param string $data
     *
     * @return null|string
     */
    public function jackson($data) {
        if ($this->checker->hasRole('ROLE_USER')) {
            $repo = $this->em->getRepository(Jackson::class);
            $record = $repo->findOneBy(['jbid' => $data]);
            if ($record) {
                return $this->generator->generate('resource_jackson_show', ['id' => $record->getId()]);
            }
        }

        return "https://jacksonbibliography.library.utoronto.ca/search/details/{$data}";
    }

    /**
     * Generate a English Novel link. The link will be internal if the user is logged in or null otherwise.
     */
    public function en(string $data) : ?string {
        if ( ! $this->checker->hasRole('ROLE_USER')) {
            return null;
        }
        $repo = $this->em->getRepository(En::class);
        $record = $repo->findOneBy([
            'enId' => $data,
        ]);
        if ($record) {
            return $this->generator->generate('resource_en_show', [
                'id' => $record->getId(),
            ]);
        }

        return null;
    }

    /**
     * Generate an Osborne link. The link will be internal if the user is logged in or null otherwise.
     */
    public function osborne(string $data) : ?string {
        if ( ! $this->checker->hasRole('ROLE_USER')) {
            return null;
        }
        $repo = $this->em->getRepository(OsborneMarc::class);
        $record = $repo->findOneBy([
            'fieldData' => $data,
            'field' => '001',
        ]);
        if ($record) {
            return $this->generator->generate('resource_osborne_show', [
                'id' => $record->getTitleId(),
            ]);
        }

        return null;
    }

    /**
     * Generate a link to the ECCO website for all users.
     *
     * @param string $data
     */
    public function ecco($data) : string {
        // No role checking for this one.
        $id = substr_replace($data, '0', 2, 0);

        return "http://link.galegroup.com/apps/doc/{$id}/ECCO?sid=WomenPrintHistProject";
    }

    /**
     * If the data matches https? it will be returned as is. Otherwise generate a URL based on the source.
     */
    public function url(Source $source, string $data) : ?string {
        if (preg_match('/https?:/', $data)) {
            return $data;
        }

        switch ($source->getName()) {
            case 'ESTC':
                return $this->estc($data);

            case 'Orlando':
                return $this->orlando($data);

            case 'Jackson Bibliography':
                return $this->jackson($data);

            case 'The English Novel 1770-1829':
            case 'The English Novel 1830-1836':
                return $this->en($data);

            case "Osborne Collection of Early Children's Books":
                return $this->osborne($data);

            case 'ECCO':
                return $this->ecco($data);

            case 'American Antiquarian Society':
                return $this->aas($data);

            default:
                return null;
        }
    }
}
