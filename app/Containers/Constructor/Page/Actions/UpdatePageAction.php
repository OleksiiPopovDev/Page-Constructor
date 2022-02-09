<?php

namespace App\Containers\Constructor\Page\Actions;

use App\Containers\Constructor\Page\Data\Dto\PageDto;
use App\Containers\Constructor\Page\Data\Dto\PageFieldDto;
use App\Containers\Constructor\Page\Tasks\CreateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\DeleteFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\UpdateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\UpdatePageTaskInterface;
use App\Ship\Parents\Actions\Action;

class UpdatePageAction extends Action implements UpdatePageActionInterface
{
    public function __construct(
        private FindPageByIdActionInterface $findPageByIdAction,
        private UpdatePageTaskInterface     $updatePageTask,
        private CreateFieldTaskInterface    $createFieldTask,
        private UpdateFieldTaskInterface    $updateFieldTask,
        private DeleteFieldTaskInterface    $deleteFieldTask
    )
    {
    }

    public function run(PageDto $data): PageDto
    {
        $formFields = collect($data->getFields());

        $currentFields = $this->findPageByIdAction->run($data->getId(), true);
        $currentFields = collect($currentFields->getFields());

        $currentFieldsIds = $currentFields->map(fn(PageFieldDto $field) => $field->getId())->toArray();
        $formFieldsIds    = $formFields->map(fn(PageFieldDto $field) => $field->getId())->reject(fn($fieldId) => $fieldId === null)->toArray();

        $createFields = $formFields->filter(fn(PageFieldDto $item) => $item->getId() === null);
        $updateFields = $formFields->reject(fn(PageFieldDto $item) => !in_array($item->getId(), $currentFieldsIds, true));
        $deleteFields = $currentFields->reject(fn(PageFieldDto $item) => in_array($item->getId(), $formFieldsIds, true));

        $createFields->each(fn(PageFieldDto $field) => $this->createFieldTask->run($field));
        $updateFields->each(fn(PageFieldDto $field) => $this->updateFieldTask->run($field));
        $deleteFields->each(fn(PageFieldDto $field) => $this->deleteFieldTask->run($field->getId()));

        return $this->updatePageTask->run($data);
    }
}