<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\PdfContainerInterface;
use Nines\MediaBundle\Entity\PdfContainerTrait;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

#[ORM\Table(name: 'nines_blog_post')]
#[ORM\Index(name: 'blog_post_ft', columns: ['title', 'searchable'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Post extends AbstractEntity implements ContentEntityInterface, PdfContainerInterface {
    use ContentExcerptTrait;
    use PdfContainerTrait {
        PdfContainerTrait::__construct as pdf_constructor;
    }

    #[ORM\Column(name: 'include_comments', type: 'boolean', nullable: false)]
    private bool $includeComments = false;

    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private string $title = '';

    #[ORM\Column(name: 'searchable', type: 'text', nullable: false)]
    private string $searchable = '';

    #[ORM\ManyToOne(targetEntity: PostCategory::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCategory $category = null;

    #[ORM\ManyToOne(targetEntity: PostStatus::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostStatus $status = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct() {
        parent::__construct();
        $this->pdf_constructor();
    }

    public function __toString() : string {
        return $this->title;
    }

    public function getIncludeComments() : bool {
        return $this->includeComments;
    }

    public function setIncludeComments(bool $includeComments) : self {
        $this->includeComments = $includeComments;

        return $this;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getSearchable() : string {
        return $this->searchable;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setSearchable() : self {
        if ($this->content) {
            $this->searchable = strip_tags($this->content);
        }

        return $this;
    }

    public function getCategory() : ?PostCategory {
        return $this->category;
    }

    public function setCategory(?PostCategory $category) : self {
        $this->category = $category;

        return $this;
    }

    public function getStatus() : ?PostStatus {
        return $this->status;
    }

    public function setStatus(?PostStatus $status) : self {
        $this->status = $status;

        return $this;
    }

    public function getUser() : ?User {
        return $this->user;
    }

    public function setUser(?User $user) : self {
        $this->user = $user;

        return $this;
    }
}
