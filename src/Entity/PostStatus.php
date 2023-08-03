<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'nines_blog_post_status')]
#[ORM\Entity(repositoryClass: PostStatusRepository::class)]
class PostStatus extends AbstractTerm {
    #[ORM\Column(type: 'boolean')]
    private bool $public = false;

    /**
     * @var Collection<int,Post>|Post[]
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'status')]
    private Collection|array $posts;

    /**
     * Build the post.
     */
    public function __construct() {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }

    public function getPublic() : bool {
        return $this->public;
    }

    public function setPublic(bool $public) : self {
        $this->public = $public;

        return $this;
    }

    /**
     * @return Collection<int,Post>|Post[]
     */
    public function getPosts() : Collection {
        return $this->posts;
    }

    public function addPost(Post $post) : self {
        if ( ! $this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setStatus($this);
        }

        return $this;
    }

    public function removePost(Post $post) : self {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getStatus() === $this) {
                $post->setStatus(null);
            }
        }

        return $this;
    }
}
