<?php

namespace App\Ship\Parents\Dto;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PopovAleksey\Mapper\Mapper;

final class PageDto extends Mapper
{
    private ?int    $id     = null;
    private ?string $name   = null;
    private ?string $type   = null;
    private ?bool   $active = null;
    /**
     * \App\Ship\Parents\Dto\PageFieldDto[]
     * @var \Illuminate\Support\Collection|null
     */
    private ?Collection $fields         = null;
    private ?int        $parent_page_id = null;
    private ?PageDto    $childPage      = null;
    private ?Carbon     $createAt       = null;
    private ?Carbon     $updateAt       = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active ?? false;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * \App\Ship\Parents\Dto\PageFieldDto[]
     * @return \Illuminate\Support\Collection
     */
    public function getFields(): Collection
    {
        return $this->fields ?? collect();
    }

    /**
     * @param \App\Ship\Parents\Dto\PageFieldDto[] $fields
     */
    public function setFields(?array $fields): self
    {
        $this->fields = collect($fields);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentPageId(): ?int
    {
        return $this->parent_page_id;
    }

    /**
     * @param int|null $parent_page_id
     * @return $this
     */
    public function setParentPageId(?int $parent_page_id): self
    {
        $this->parent_page_id = $parent_page_id;

        return $this;
    }

    /**
     * @return \App\Ship\Parents\Dto\PageDto|null
     */
    public function getChildPage(): ?PageDto
    {
        return $this->childPage;
    }

    /**
     * @param \App\Ship\Parents\Dto\PageDto $childPage
     * @return $this
     */
    public function setChildPage(PageDto $childPage): self
    {
        $this->childPage = $childPage;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getCreateAt(): ?Carbon
    {
        return $this->createAt ?? null;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $createAt
     * @return $this
     */
    public function setCreateAt(?Carbon $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getUpdateAt(): ?Carbon
    {
        return $this->updateAt ?? null;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $updateAt
     * @return $this
     */
    public function setUpdateAt(?Carbon $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }


}