<?php

namespace App\Containers\Constructor\Seo\Data\Dto;

use Illuminate\Support\Carbon;
use PopovAleksey\Mapper\Mapper;

class SeoDto extends Mapper
{
    private ?int    $id          = null;
    private ?int    $pageId      = null;
    private ?int    $pageFieldId = null;
    private ?int    $languageId  = null;
    private ?string $link        = null;
    private ?string $caseType    = null;
    private ?bool   $static      = null;
    private ?bool   $active      = null;
    private ?Carbon $createAt    = null;
    private ?Carbon $updateAt    = null;

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setPageId(?int $pageId): self
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getPageId(): ?int
    {
        return $this->pageId ?? null;
    }

    public function setPageFieldId(?int $pageFieldId): self
    {
        $this->pageFieldId = $pageFieldId;

        return $this;
    }

    public function getPageFieldId(): ?int
    {
        return $this->pageFieldId ?? null;
    }

    public function setLanguageId(?int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId ?? null;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link ?? null;
    }

    public function setCaseType(?string $caseType): self
    {
        $this->caseType = $caseType;

        return $this;
    }

    public function getCaseType(): ?string
    {
        return $this->caseType ?? null;
    }

    public function setStatic(?bool $static): self
    {
        $this->static = $static;

        return $this;
    }

    public function getStatic(): ?bool
    {
        return $this->static ?? null;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active ?? null;
    }

    public function setCreateAt(?Carbon $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateAt(): ?Carbon
    {
        return $this->createAt ?? null;
    }

    public function setUpdateAt(?Carbon $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUpdateAt(): ?Carbon
    {
        return $this->updateAt ?? null;
    }


}