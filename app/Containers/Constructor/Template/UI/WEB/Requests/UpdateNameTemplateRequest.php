<?php

namespace App\Containers\Constructor\Template\UI\WEB\Requests;

use App\Ship\Parents\Dto\TemplateDto;
use App\Ship\Parents\Requests\Request;

class UpdateNameTemplateRequest extends Request
{
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string'],
            'parent_template_id' => ['integer'],
        ];
    }

    public function mapped(): TemplateDto
    {
        return (new TemplateDto())
            ->setName($this->get('name'))
            ->setParentTemplateId($this->get('parent_template_id'));
    }
}
