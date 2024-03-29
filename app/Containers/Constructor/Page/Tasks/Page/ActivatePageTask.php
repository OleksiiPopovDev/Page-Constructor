<?php

namespace App\Containers\Constructor\Page\Tasks\Page;

use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Dto\PageDto;
use App\Ship\Parents\Repositories\PageRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Exception;

class ActivatePageTask extends Task implements ActivatePageTaskInterface
{
    public function __construct(
        private readonly PageRepositoryInterface $repository
    )
    {
    }

    /**
     * @param \App\Ship\Parents\Dto\PageDto $data
     * @return \App\Ship\Parents\Dto\PageDto
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run(PageDto $data): PageDto
    {
        try {
            /**
             * @var \App\Ship\Parents\Models\PageInterface $page
             */
            $page = $this->repository->update(['active' => $data->isActive()], $data->getId());

            return (new PageDto())
                ->setId($page->id)
                ->setName($page->name)
                ->setType($page->type)
                ->setActive($page->active)
                ->setCreateAt($page->created_at)
                ->setUpdateAt($page->updated_at);

        } catch (Exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
