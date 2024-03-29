<?php

namespace App\Ship\Parents\Dto;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PopovAleksey\Mapper\Mapper;

final class TemplateWidgetDto extends Mapper
{
    private ?int    $id            = null;
    private ?int    $templateId    = null;
    private ?int    $countElements = null;
    private ?string $showBy        = null;
    /**
     * \App\Ship\Parents\Dto\ContentDto[]
     * @var \Illuminate\Support\Collection|null
     */
    private ?Collection $contents = null;
    private ?Carbon     $createAt = null;
    private ?Carbon     $updateAt = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return TemplateWidgetDto
     */
    public function setId(?int $id): TemplateWidgetDto
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }

    /**
     * @param int|null $templateId
     * @return TemplateWidgetDto
     */
    public function setTemplateId(?int $templateId): TemplateWidgetDto
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCountElements(): ?int
    {
        return $this->countElements;
    }

    /**
     * @param int|null $countElements
     * @return TemplateWidgetDto
     */
    public function setCountElements(?int $countElements): TemplateWidgetDto
    {
        $this->countElements = $countElements;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShowBy(): ?string
    {
        return $this->showBy;
    }

    /**
     * @param string|null $showBy
     * @return TemplateWidgetDto
     */
    public function setShowBy(?string $showBy): TemplateWidgetDto
    {
        $this->showBy = $showBy;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getCreateAt(): ?Carbon
    {
        return $this->createAt;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $createAt
     * @return TemplateWidgetDto
     */
    public function setCreateAt(?Carbon $createAt): TemplateWidgetDto
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getUpdateAt(): ?Carbon
    {
        return $this->updateAt;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $updateAt
     * @return TemplateWidgetDto
     */
    public function setUpdateAt(?Carbon $updateAt): TemplateWidgetDto
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getContents(): ?Collection
    {
        return $this->contents;
    }

    /**
     * @param \Illuminate\Support\Collection|null $contents
     * @return TemplateWidgetDto
     */
    public function setContents(?Collection $contents): TemplateWidgetDto
    {
        $this->contents = $contents;

        return $this;
    }
}
