<?php

namespace App\Containers\Constructor\Seo\Actions;

use App\Containers\Constructor\Seo\Tasks\UpdateSeoTaskInterface;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Dto\SeoDto;

class UpdateSeoAction extends Action implements UpdateSeoActionInterface
{
    public function __construct(
        private readonly UpdateSeoTaskInterface $updateSeoTask
    )
    {
    }

    public function run(SeoDto $data): void
    {
        $this->updateSeoTask->run($data);
    }
}
