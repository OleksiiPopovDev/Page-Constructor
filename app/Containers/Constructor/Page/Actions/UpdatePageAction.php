<?php

namespace App\Containers\Constructor\Page\Actions;

use App\Containers\Constructor\Page\Tasks\Field\CreateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\Field\DeleteFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\Field\UpdateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\Page\UpdatePageTaskInterface;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Dto\PageDto;
use App\Ship\Parents\Dto\PageFieldDto;
use Illuminate\Support\Facades\DB;

class UpdatePageAction extends Action implements UpdatePageActionInterface
{
    public function __construct(
        private readonly FindPageByIdActionInterface $findPageByIdAction,
        private readonly UpdatePageTaskInterface     $updatePageTask,
        private readonly CreateFieldTaskInterface    $createFieldTask,
        private readonly UpdateFieldTaskInterface    $updateFieldTask,
        private readonly DeleteFieldTaskInterface    $deleteFieldTask
    )
    {
    }

    /**
     * @param \App\Ship\Parents\Dto\PageDto $data
     * @return \App\Ship\Parents\Dto\PageDto
     * @throws \Throwable
     */
    public function run(PageDto $data): PageDto
    {
        $formFields = $data->getFields();

        $currentFields = $this->findPageByIdAction->run($data->getId(), true);
        $currentFields = $currentFields->getFields();

        $currentFieldsIds = $currentFields->map(fn(PageFieldDto $field) => $field->getId())->toArray();
        $formFieldsIds    = $formFields->map(fn(PageFieldDto $field) => $field->getId())->reject(fn($fieldId) => $fieldId === null)->toArray();

        $createFields = $formFields->filter(fn(PageFieldDto $item) => $item->getId() === null);
        $updateFields = $formFields->reject(fn(PageFieldDto $item) => !in_array($item->getId(), $currentFieldsIds, true));
        $deleteFields = $currentFields->reject(fn(PageFieldDto $item) => in_array($item->getId(), $formFieldsIds, true));

        return DB::transaction(function () use ($data, $createFields, $updateFields, $deleteFields) {
            $createFields->each(fn(PageFieldDto $field) => $this->createFieldTask->run($field));
            $updateFields->each(fn(PageFieldDto $field) => $this->updateFieldTask->run($field));
            $deleteFields->each(fn(PageFieldDto $field) => $this->deleteFieldTask->run($field->getId()));

            return $this->updatePageTask->run($data);
        });
    }
}
