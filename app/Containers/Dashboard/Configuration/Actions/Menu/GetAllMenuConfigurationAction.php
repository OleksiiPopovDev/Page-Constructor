<?php

namespace App\Containers\Dashboard\Configuration\Actions\Menu;

use App\Containers\Dashboard\Configuration\Tasks\Menu\GetAllMenuConfigurationTaskInterface;
use App\Containers\Dashboard\Configuration\Tasks\Menu\GetAllMenuTemplateTaskInterface;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;

class GetAllMenuConfigurationAction extends Action implements GetAllMenuConfigurationActionInterface
{
    public function __construct(
        private readonly GetAllMenuConfigurationTaskInterface $getAllConfigurationTask,
        private readonly GetAllMenuTemplateTaskInterface      $getAllMenuTemplateTask
    )
    {
    }

    public function run(): Collection
    {
        $menuTemplates = $this->getAllMenuTemplateTask->run();
        $menuList      = $this->getAllConfigurationTask->run($menuTemplates);

        return collect([
            'templates' => $menuTemplates,
            'list'      => $menuList,
        ]);
    }
}
