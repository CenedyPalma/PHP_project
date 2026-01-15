<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'inventory_custom_id_unique', columns: ['inventory_id', 'custom_id'])]
#[ORM\Index(columns: ['inventory_id', 'custom_id'], name: 'idx_inventory_custom_id')]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Inventory::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Inventory $inventory = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(length: 100)]
    private ?string $customId = null;

    // ===== FIXED COLUMNS: String Fields (3 max) =====
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $string1Value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $string2Value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $string3Value = null;

    // ===== FIXED COLUMNS: Text Fields (3 max) =====
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text1Value = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text2Value = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text3Value = null;

    // ===== FIXED COLUMNS: Integer Fields (3 max) =====
    #[ORM\Column(nullable: true)]
    private ?int $int1Value = null;

    #[ORM\Column(nullable: true)]
    private ?int $int2Value = null;

    #[ORM\Column(nullable: true)]
    private ?int $int3Value = null;

    // ===== FIXED COLUMNS: Boolean Fields (3 max) =====
    #[ORM\Column(nullable: true)]
    private ?bool $bool1Value = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bool2Value = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bool3Value = null;

    // ===== FIXED COLUMNS: Link/URL Fields (3 max) =====
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $link1Value = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $link2Value = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $link3Value = null;

    #[ORM\Column(type: 'integer')]
    #[ORM\Version]
    private int $version = 1;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, ItemLike>
     */
    #[ORM\OneToMany(targetEntity: ItemLike::class, mappedBy: 'item', cascade: ['remove'])]
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(string $customId): static
    {
        $this->customId = $customId;
        return $this;
    }

    // ===== String Value Getters/Setters =====
    public function getString1Value(): ?string
    {
        return $this->string1Value;
    }
    public function setString1Value(?string $v): static
    {
        $this->string1Value = $v;
        return $this;
    }
    public function getString2Value(): ?string
    {
        return $this->string2Value;
    }
    public function setString2Value(?string $v): static
    {
        $this->string2Value = $v;
        return $this;
    }
    public function getString3Value(): ?string
    {
        return $this->string3Value;
    }
    public function setString3Value(?string $v): static
    {
        $this->string3Value = $v;
        return $this;
    }

    // ===== Text Value Getters/Setters =====
    public function getText1Value(): ?string
    {
        return $this->text1Value;
    }
    public function setText1Value(?string $v): static
    {
        $this->text1Value = $v;
        return $this;
    }
    public function getText2Value(): ?string
    {
        return $this->text2Value;
    }
    public function setText2Value(?string $v): static
    {
        $this->text2Value = $v;
        return $this;
    }
    public function getText3Value(): ?string
    {
        return $this->text3Value;
    }
    public function setText3Value(?string $v): static
    {
        $this->text3Value = $v;
        return $this;
    }

    // ===== Int Value Getters/Setters =====
    public function getInt1Value(): ?int
    {
        return $this->int1Value;
    }
    public function setInt1Value(?int $v): static
    {
        $this->int1Value = $v;
        return $this;
    }
    public function getInt2Value(): ?int
    {
        return $this->int2Value;
    }
    public function setInt2Value(?int $v): static
    {
        $this->int2Value = $v;
        return $this;
    }
    public function getInt3Value(): ?int
    {
        return $this->int3Value;
    }
    public function setInt3Value(?int $v): static
    {
        $this->int3Value = $v;
        return $this;
    }

    // ===== Bool Value Getters/Setters =====
    public function getBool1Value(): ?bool
    {
        return $this->bool1Value;
    }
    public function setBool1Value(?bool $v): static
    {
        $this->bool1Value = $v;
        return $this;
    }
    public function getBool2Value(): ?bool
    {
        return $this->bool2Value;
    }
    public function setBool2Value(?bool $v): static
    {
        $this->bool2Value = $v;
        return $this;
    }
    public function getBool3Value(): ?bool
    {
        return $this->bool3Value;
    }
    public function setBool3Value(?bool $v): static
    {
        $this->bool3Value = $v;
        return $this;
    }

    // ===== Link Value Getters/Setters =====
    public function getLink1Value(): ?string
    {
        return $this->link1Value;
    }
    public function setLink1Value(?string $v): static
    {
        $this->link1Value = $v;
        return $this;
    }
    public function getLink2Value(): ?string
    {
        return $this->link2Value;
    }
    public function setLink2Value(?string $v): static
    {
        $this->link2Value = $v;
        return $this;
    }
    public function getLink3Value(): ?string
    {
        return $this->link3Value;
    }
    public function setLink3Value(?string $v): static
    {
        $this->link3Value = $v;
        return $this;
    }

    /**
     * Generic getter by field type and index (1-3)
     */
    public function getFieldValue(string $type, int $index): mixed
    {
        $method = 'get' . ucfirst($type) . $index . 'Value';
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        return null;
    }

    /**
     * Generic setter by field type and index (1-3)
     */
    public function setFieldValue(string $type, int $index, mixed $value): static
    {
        $method = 'set' . ucfirst($type) . $index . 'Value';
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, ItemLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function getLikesCount(): int
    {
        return $this->likes->count();
    }

    public function isLikedByUser(?User $user): bool
    {
        if ($user === null) {
            return false;
        }
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
