<?php

namespace App\Containers\Dashboard\Configuration\Actions;

use App\Containers\Dashboard\Configuration\Tasks\GetAllCommonConfigurationTaskInterface;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Dto\ConfigurationCommonDto;

class GetAllCommonConfigurationAction extends Action implements GetAllCommonConfigurationActionInterface
{
    public function __construct(
        private GetAllCommonConfigurationTaskInterface $getAllConfigurationTask
    )
    {
    }

    public function run(): ConfigurationCommonDto
    {
        return $this->getAllConfigurationTask->run();
    }
}