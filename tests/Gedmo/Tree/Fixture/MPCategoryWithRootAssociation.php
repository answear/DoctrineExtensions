<?php

declare(strict_types=1);

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gedmo\Tests\Tree\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

#[ORM\Entity(repositoryClass: MaterializedPathRepository::class)]
#[Gedmo\Tree(type: 'materializedPath')]
class MPCategoryWithRootAssociation
{
    /**
     * @var int|null
     *
     *
     *      *      *      */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Gedmo\TreePathSource]
    private ?int $id = null;

    #[ORM\Column(name: 'path', type: Types::STRING, length: 3000, nullable: true)]
    #[Gedmo\TreePath]
    private ?string $path = null;

    #[ORM\Column(name: 'title', type: Types::STRING, length: 64)]
    private ?string $title = null;

    /**
     *      *      *          * })
     */
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\TreeParent]
    private ?MPCategoryWithRootAssociation $parentId = null;

    /**
     * @var int|null
     *
     *
     *      */
    #[ORM\Column(name: 'lvl', type: Types::INTEGER, nullable: true)]
    #[Gedmo\TreeLevel]
    private ?int $level = null;

    /**
     * @var self|null
     *
     *      *      *          * })
     */
    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'tree_root_entity', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\TreeRoot]
    private ?MPCategoryWithRootAssociation $treeRootEntity = null;

    /**
     * @var Collection<int, self>
     *
     *      */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Collection<int, Article>
     *
     *      */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'category')]
    private Collection $comments;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function setParent(?self $parent = null): void
    {
        $this->parentId = $parent;
    }

    public function getParent(): ?self
    {
        return $this->parentId;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function getTreeRootEntity(): ?self
    {
        return $this->treeRootEntity;
    }
}
