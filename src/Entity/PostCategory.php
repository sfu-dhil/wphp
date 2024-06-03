<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'nines_blog_post_category')]
#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
class PostCategory extends AbstractTerm {
    /**
     * @var Collection<int,Post>|Post[]
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'category')]
    private array|Collection $posts;

    public function __construct() {
        parent::__construct();
        $this->posts = new ArrayCollection();
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
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post) : self {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
