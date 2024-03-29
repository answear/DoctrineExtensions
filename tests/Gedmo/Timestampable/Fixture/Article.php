<?php

declare(strict_types=1);

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gedmo\Tests\Timestampable\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Timestampable;

#[ORM\Entity]
class Article implements Timestampable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'title', type: Types::STRING, length: 128)]
    private ?string $title = null;

    #[ORM\Column(name: 'body', type: Types::STRING)]
    private ?string $body = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private Collection $comments;

    #[ORM\Embedded(class: Author::class)]
    private ?Author $author = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created', type: Types::DATE_MUTABLE)]
    private ?\DateTime $created = null;

    #[ORM\Column(name: 'updated', type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable]
    private ?\DateTime $updated = null;

    #[ORM\Column(name: 'published', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'change', field: 'type.title', value: 'Published')]
    private ?\DateTime $published = null;

    #[ORM\Column(name: 'content_changed', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'change', field: ['title', 'body'])]
    private ?\DateTimeInterface $contentChanged = null;

    #[ORM\Column(name: 'author_changed', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'change', field: ['author.name', 'author.email'])]
    private ?\DateTimeInterface $authorChanged = null;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'articles')]
    private ?Type $type = null;

    #[ORM\Column(name: 'level', type: Types::INTEGER)]
    private int $level = 0;

    /**
     * We use the value "10" as string here in order to check the behavior of `AbstractTrackingListener`
     */
    #[ORM\Column(name: 'reached_relevant_level', type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'change', field: 'level', value: '10')]
    private ?\DateTimeInterface $reachedRelevantLevel = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function addComment(Comment $comment): void
    {
        $comment->setArticle($this);
        $this->comments[] = $comment;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    public function getPublished(): ?\DateTime
    {
        return $this->published;
    }

    public function setPublished(\DateTime $published): void
    {
        $this->published = $published;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }

    public function setContentChanged(\DateTime $contentChanged): void
    {
        $this->contentChanged = $contentChanged;
    }

    public function getContentChanged(): ?\DateTimeInterface
    {
        return $this->contentChanged;
    }

    public function getAuthorChanged(): ?\DateTimeInterface
    {
        return $this->authorChanged;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getReachedRelevantLevel(): ?\DateTimeInterface
    {
        return $this->reachedRelevantLevel;
    }
}
