<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::JSON)]
    private array $tags = [];

    #[ORM\Column(type: Types::JSON)]
    private array $idFormatComponents = [];

    #[ORM\Column]
    private int $nextSequence = 1;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(type: 'integer')]
    #[ORM\Version]
    private int $version = 1;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // ===== FIXED FIELD DEFINITIONS: String (3 max) =====
    #[ORM\Column]
    private bool $string1Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $string1Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $string1Desc = null;
    #[ORM\Column]
    private bool $string1InTable = true;

    #[ORM\Column]
    private bool $string2Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $string2Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $string2Desc = null;
    #[ORM\Column]
    private bool $string2InTable = true;

    #[ORM\Column]
    private bool $string3Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $string3Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $string3Desc = null;
    #[ORM\Column]
    private bool $string3InTable = true;

    // ===== FIXED FIELD DEFINITIONS: Text (3 max) =====
    #[ORM\Column]
    private bool $text1Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $text1Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text1Desc = null;
    #[ORM\Column]
    private bool $text1InTable = false;

    #[ORM\Column]
    private bool $text2Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $text2Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text2Desc = null;
    #[ORM\Column]
    private bool $text2InTable = false;

    #[ORM\Column]
    private bool $text3Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $text3Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text3Desc = null;
    #[ORM\Column]
    private bool $text3InTable = false;

    // ===== FIXED FIELD DEFINITIONS: Int (3 max) =====
    #[ORM\Column]
    private bool $int1Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $int1Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $int1Desc = null;
    #[ORM\Column]
    private bool $int1InTable = true;

    #[ORM\Column]
    private bool $int2Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $int2Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $int2Desc = null;
    #[ORM\Column]
    private bool $int2InTable = true;

    #[ORM\Column]
    private bool $int3Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $int3Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $int3Desc = null;
    #[ORM\Column]
    private bool $int3InTable = true;

    // ===== FIXED FIELD DEFINITIONS: Bool (3 max) =====
    #[ORM\Column]
    private bool $bool1Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $bool1Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bool1Desc = null;
    #[ORM\Column]
    private bool $bool1InTable = true;

    #[ORM\Column]
    private bool $bool2Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $bool2Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bool2Desc = null;
    #[ORM\Column]
    private bool $bool2InTable = true;

    #[ORM\Column]
    private bool $bool3Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $bool3Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bool3Desc = null;
    #[ORM\Column]
    private bool $bool3InTable = true;

    // ===== FIXED FIELD DEFINITIONS: Link (3 max) =====
    #[ORM\Column]
    private bool $link1Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link1Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link1Desc = null;
    #[ORM\Column]
    private bool $link1InTable = false;

    #[ORM\Column]
    private bool $link2Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link2Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link2Desc = null;
    #[ORM\Column]
    private bool $link2InTable = false;

    #[ORM\Column]
    private bool $link3Active = false;
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link3Name = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link3Desc = null;
    #[ORM\Column]
    private bool $link3InTable = false;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'inventory', cascade: ['remove'])]
    private Collection $items;

    /**
     * @var Collection<int, DiscussionPost>
     */
    #[ORM\OneToMany(targetEntity: DiscussionPost::class, mappedBy: 'inventory', cascade: ['remove'])]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $posts;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->idFormatComponents = [
            ['type' => 'text', 'value' => 'ITEM-'],
            ['type' => 'sequence', 'padding' => 4]
        ];
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
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
    public function getTags(): array
    {
        return $this->tags;
    }
    public function setTags(array $tags): static
    {
        $this->tags = $tags;
        return $this;
    }
    public function getIdFormatComponents(): array
    {
        return $this->idFormatComponents;
    }
    public function setIdFormatComponents(array $components): static
    {
        $this->idFormatComponents = $components;
        return $this;
    }
    public function getNextSequence(): int
    {
        return $this->nextSequence;
    }
    public function incrementNextSequence(): static
    {
        $this->nextSequence++;
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
    public function getVersion(): int
    {
        return $this->version;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Generate the next custom ID based on format components
     */
    public function generateNextCustomId(): string
    {
        $result = '';
        foreach ($this->idFormatComponents as $component) {
            switch ($component['type']) {
                case 'text':
                    $result .= $component['value'] ?? '';
                    break;
                case 'sequence':
                    $padding = $component['padding'] ?? 4;
                    $result .= str_pad((string)$this->nextSequence, $padding, '0', STR_PAD_LEFT);
                    break;
                case 'date':
                    $format = $component['format'] ?? 'Ymd';
                    $result .= date($format);
                    break;
                case 'random':
                    $digits = $component['digits'] ?? 6;
                    $result .= str_pad((string)random_int(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                    break;
                case 'guid':
                    $result .= substr(bin2hex(random_bytes(4)), 0, 8);
                    break;
            }
        }
        return $result;
    }

    /**
     * Get all active field definitions
     * @return array<array{type: string, index: int, name: string, desc: string, inTable: bool}>
     */
    public function getActiveFields(): array
    {
        $fields = [];
        foreach (['string', 'text', 'int', 'bool', 'link'] as $type) {
            for ($i = 1; $i <= 3; $i++) {
                $activeMethod = 'is' . ucfirst($type) . $i . 'Active';
                $nameMethod = 'get' . ucfirst($type) . $i . 'Name';
                $descMethod = 'get' . ucfirst($type) . $i . 'Desc';
                $inTableMethod = 'is' . ucfirst($type) . $i . 'InTable';

                if ($this->$activeMethod()) {
                    $fields[] = [
                        'type' => $type,
                        'index' => $i,
                        'name' => $this->$nameMethod() ?? ucfirst($type) . ' ' . $i,
                        'desc' => $this->$descMethod() ?? '',
                        'inTable' => $this->$inTableMethod(),
                    ];
                }
            }
        }
        return $fields;
    }

    // ===== Field Definition Accessors =====
    // String fields
    public function isString1Active(): bool
    {
        return $this->string1Active;
    }
    public function setString1Active(bool $v): static
    {
        $this->string1Active = $v;
        return $this;
    }
    public function getString1Name(): ?string
    {
        return $this->string1Name;
    }
    public function setString1Name(?string $v): static
    {
        $this->string1Name = $v;
        return $this;
    }
    public function getString1Desc(): ?string
    {
        return $this->string1Desc;
    }
    public function setString1Desc(?string $v): static
    {
        $this->string1Desc = $v;
        return $this;
    }
    public function isString1InTable(): bool
    {
        return $this->string1InTable;
    }
    public function setString1InTable(bool $v): static
    {
        $this->string1InTable = $v;
        return $this;
    }

    public function isString2Active(): bool
    {
        return $this->string2Active;
    }
    public function setString2Active(bool $v): static
    {
        $this->string2Active = $v;
        return $this;
    }
    public function getString2Name(): ?string
    {
        return $this->string2Name;
    }
    public function setString2Name(?string $v): static
    {
        $this->string2Name = $v;
        return $this;
    }
    public function getString2Desc(): ?string
    {
        return $this->string2Desc;
    }
    public function setString2Desc(?string $v): static
    {
        $this->string2Desc = $v;
        return $this;
    }
    public function isString2InTable(): bool
    {
        return $this->string2InTable;
    }
    public function setString2InTable(bool $v): static
    {
        $this->string2InTable = $v;
        return $this;
    }

    public function isString3Active(): bool
    {
        return $this->string3Active;
    }
    public function setString3Active(bool $v): static
    {
        $this->string3Active = $v;
        return $this;
    }
    public function getString3Name(): ?string
    {
        return $this->string3Name;
    }
    public function setString3Name(?string $v): static
    {
        $this->string3Name = $v;
        return $this;
    }
    public function getString3Desc(): ?string
    {
        return $this->string3Desc;
    }
    public function setString3Desc(?string $v): static
    {
        $this->string3Desc = $v;
        return $this;
    }
    public function isString3InTable(): bool
    {
        return $this->string3InTable;
    }
    public function setString3InTable(bool $v): static
    {
        $this->string3InTable = $v;
        return $this;
    }

    // Text fields
    public function isText1Active(): bool
    {
        return $this->text1Active;
    }
    public function setText1Active(bool $v): static
    {
        $this->text1Active = $v;
        return $this;
    }
    public function getText1Name(): ?string
    {
        return $this->text1Name;
    }
    public function setText1Name(?string $v): static
    {
        $this->text1Name = $v;
        return $this;
    }
    public function getText1Desc(): ?string
    {
        return $this->text1Desc;
    }
    public function setText1Desc(?string $v): static
    {
        $this->text1Desc = $v;
        return $this;
    }
    public function isText1InTable(): bool
    {
        return $this->text1InTable;
    }
    public function setText1InTable(bool $v): static
    {
        $this->text1InTable = $v;
        return $this;
    }

    public function isText2Active(): bool
    {
        return $this->text2Active;
    }
    public function setText2Active(bool $v): static
    {
        $this->text2Active = $v;
        return $this;
    }
    public function getText2Name(): ?string
    {
        return $this->text2Name;
    }
    public function setText2Name(?string $v): static
    {
        $this->text2Name = $v;
        return $this;
    }
    public function getText2Desc(): ?string
    {
        return $this->text2Desc;
    }
    public function setText2Desc(?string $v): static
    {
        $this->text2Desc = $v;
        return $this;
    }
    public function isText2InTable(): bool
    {
        return $this->text2InTable;
    }
    public function setText2InTable(bool $v): static
    {
        $this->text2InTable = $v;
        return $this;
    }

    public function isText3Active(): bool
    {
        return $this->text3Active;
    }
    public function setText3Active(bool $v): static
    {
        $this->text3Active = $v;
        return $this;
    }
    public function getText3Name(): ?string
    {
        return $this->text3Name;
    }
    public function setText3Name(?string $v): static
    {
        $this->text3Name = $v;
        return $this;
    }
    public function getText3Desc(): ?string
    {
        return $this->text3Desc;
    }
    public function setText3Desc(?string $v): static
    {
        $this->text3Desc = $v;
        return $this;
    }
    public function isText3InTable(): bool
    {
        return $this->text3InTable;
    }
    public function setText3InTable(bool $v): static
    {
        $this->text3InTable = $v;
        return $this;
    }

    // Int fields
    public function isInt1Active(): bool
    {
        return $this->int1Active;
    }
    public function setInt1Active(bool $v): static
    {
        $this->int1Active = $v;
        return $this;
    }
    public function getInt1Name(): ?string
    {
        return $this->int1Name;
    }
    public function setInt1Name(?string $v): static
    {
        $this->int1Name = $v;
        return $this;
    }
    public function getInt1Desc(): ?string
    {
        return $this->int1Desc;
    }
    public function setInt1Desc(?string $v): static
    {
        $this->int1Desc = $v;
        return $this;
    }
    public function isInt1InTable(): bool
    {
        return $this->int1InTable;
    }
    public function setInt1InTable(bool $v): static
    {
        $this->int1InTable = $v;
        return $this;
    }

    public function isInt2Active(): bool
    {
        return $this->int2Active;
    }
    public function setInt2Active(bool $v): static
    {
        $this->int2Active = $v;
        return $this;
    }
    public function getInt2Name(): ?string
    {
        return $this->int2Name;
    }
    public function setInt2Name(?string $v): static
    {
        $this->int2Name = $v;
        return $this;
    }
    public function getInt2Desc(): ?string
    {
        return $this->int2Desc;
    }
    public function setInt2Desc(?string $v): static
    {
        $this->int2Desc = $v;
        return $this;
    }
    public function isInt2InTable(): bool
    {
        return $this->int2InTable;
    }
    public function setInt2InTable(bool $v): static
    {
        $this->int2InTable = $v;
        return $this;
    }

    public function isInt3Active(): bool
    {
        return $this->int3Active;
    }
    public function setInt3Active(bool $v): static
    {
        $this->int3Active = $v;
        return $this;
    }
    public function getInt3Name(): ?string
    {
        return $this->int3Name;
    }
    public function setInt3Name(?string $v): static
    {
        $this->int3Name = $v;
        return $this;
    }
    public function getInt3Desc(): ?string
    {
        return $this->int3Desc;
    }
    public function setInt3Desc(?string $v): static
    {
        $this->int3Desc = $v;
        return $this;
    }
    public function isInt3InTable(): bool
    {
        return $this->int3InTable;
    }
    public function setInt3InTable(bool $v): static
    {
        $this->int3InTable = $v;
        return $this;
    }

    // Bool fields
    public function isBool1Active(): bool
    {
        return $this->bool1Active;
    }
    public function setBool1Active(bool $v): static
    {
        $this->bool1Active = $v;
        return $this;
    }
    public function getBool1Name(): ?string
    {
        return $this->bool1Name;
    }
    public function setBool1Name(?string $v): static
    {
        $this->bool1Name = $v;
        return $this;
    }
    public function getBool1Desc(): ?string
    {
        return $this->bool1Desc;
    }
    public function setBool1Desc(?string $v): static
    {
        $this->bool1Desc = $v;
        return $this;
    }
    public function isBool1InTable(): bool
    {
        return $this->bool1InTable;
    }
    public function setBool1InTable(bool $v): static
    {
        $this->bool1InTable = $v;
        return $this;
    }

    public function isBool2Active(): bool
    {
        return $this->bool2Active;
    }
    public function setBool2Active(bool $v): static
    {
        $this->bool2Active = $v;
        return $this;
    }
    public function getBool2Name(): ?string
    {
        return $this->bool2Name;
    }
    public function setBool2Name(?string $v): static
    {
        $this->bool2Name = $v;
        return $this;
    }
    public function getBool2Desc(): ?string
    {
        return $this->bool2Desc;
    }
    public function setBool2Desc(?string $v): static
    {
        $this->bool2Desc = $v;
        return $this;
    }
    public function isBool2InTable(): bool
    {
        return $this->bool2InTable;
    }
    public function setBool2InTable(bool $v): static
    {
        $this->bool2InTable = $v;
        return $this;
    }

    public function isBool3Active(): bool
    {
        return $this->bool3Active;
    }
    public function setBool3Active(bool $v): static
    {
        $this->bool3Active = $v;
        return $this;
    }
    public function getBool3Name(): ?string
    {
        return $this->bool3Name;
    }
    public function setBool3Name(?string $v): static
    {
        $this->bool3Name = $v;
        return $this;
    }
    public function getBool3Desc(): ?string
    {
        return $this->bool3Desc;
    }
    public function setBool3Desc(?string $v): static
    {
        $this->bool3Desc = $v;
        return $this;
    }
    public function isBool3InTable(): bool
    {
        return $this->bool3InTable;
    }
    public function setBool3InTable(bool $v): static
    {
        $this->bool3InTable = $v;
        return $this;
    }

    // Link fields
    public function isLink1Active(): bool
    {
        return $this->link1Active;
    }
    public function setLink1Active(bool $v): static
    {
        $this->link1Active = $v;
        return $this;
    }
    public function getLink1Name(): ?string
    {
        return $this->link1Name;
    }
    public function setLink1Name(?string $v): static
    {
        $this->link1Name = $v;
        return $this;
    }
    public function getLink1Desc(): ?string
    {
        return $this->link1Desc;
    }
    public function setLink1Desc(?string $v): static
    {
        $this->link1Desc = $v;
        return $this;
    }
    public function isLink1InTable(): bool
    {
        return $this->link1InTable;
    }
    public function setLink1InTable(bool $v): static
    {
        $this->link1InTable = $v;
        return $this;
    }

    public function isLink2Active(): bool
    {
        return $this->link2Active;
    }
    public function setLink2Active(bool $v): static
    {
        $this->link2Active = $v;
        return $this;
    }
    public function getLink2Name(): ?string
    {
        return $this->link2Name;
    }
    public function setLink2Name(?string $v): static
    {
        $this->link2Name = $v;
        return $this;
    }
    public function getLink2Desc(): ?string
    {
        return $this->link2Desc;
    }
    public function setLink2Desc(?string $v): static
    {
        $this->link2Desc = $v;
        return $this;
    }
    public function isLink2InTable(): bool
    {
        return $this->link2InTable;
    }
    public function setLink2InTable(bool $v): static
    {
        $this->link2InTable = $v;
        return $this;
    }

    public function isLink3Active(): bool
    {
        return $this->link3Active;
    }
    public function setLink3Active(bool $v): static
    {
        $this->link3Active = $v;
        return $this;
    }
    public function getLink3Name(): ?string
    {
        return $this->link3Name;
    }
    public function setLink3Name(?string $v): static
    {
        $this->link3Name = $v;
        return $this;
    }
    public function getLink3Desc(): ?string
    {
        return $this->link3Desc;
    }
    public function setLink3Desc(?string $v): static
    {
        $this->link3Desc = $v;
        return $this;
    }
    public function isLink3InTable(): bool
    {
        return $this->link3InTable;
    }
    public function setLink3InTable(bool $v): static
    {
        $this->link3InTable = $v;
        return $this;
    }

    /** @return Collection<int, Item> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /** @return Collection<int, DiscussionPost> */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
