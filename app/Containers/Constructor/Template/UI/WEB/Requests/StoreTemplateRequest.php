<?php

namespace App\Containers\Constructor\Template\UI\WEB\Requests;

use App\Ship\Parents\Dto\LanguageDto;
use App\Ship\Parents\Dto\PageDto;
use App\Ship\Parents\Dto\TemplateDto;
use App\Ship\Parents\Dto\ThemeDto;
use App\Ship\Parents\Models\TemplateInterface;
use App\Ship\Parents\Requests\Request;

class StoreTemplateRequest extends Request
{
    public function rules(): array
    {
        $types = collect([
            TemplateInterface::PAGE_TYPE,
            TemplateInterface::BASE_TYPE,
            TemplateInterface::CSS_TYPE,
            TemplateInterface::JS_TYPE,
            TemplateInterface::MENU_TYPE,
        ])->implode(',');

        return [
            'type'        => ['required', 'in:' . $types],
            'name'        => ['nullable', 'string'],
            'theme_id'    => ['required', 'integer'],
            'page_id'     => ['required_if:templateType,' . TemplateInterface::PAGE_TYPE, 'integer'],
            'language_id' => ['integer', 'nullable'],
        ];
    }

    public function mapped(): TemplateDto
    {
        $pageDto     = (new PageDto())->setId($this->get('page_id'));
        $languageDto = (new LanguageDto())->setId($this->get('language_id'));
        $themeDto    = (new ThemeDto())->setId($this->get('theme_id'));

        $data = $this->validated();

        return (new TemplateDto())
            ->setType(data_get($data, 'type'))
            ->setName(data_get($data, 'name'))
            ->setTheme($themeDto)
            ->setPage($pageDto)
            ->setLanguage($languageDto);
    }
}
