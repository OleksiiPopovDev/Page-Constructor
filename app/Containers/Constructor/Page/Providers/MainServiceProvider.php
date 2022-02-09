<?php

namespace App\Containers\Constructor\Page\Providers;

use App\Containers\Constructor\Page\Actions\ActivatePageAction;
use App\Containers\Constructor\Page\Actions\ActivatePageActionInterface;
use App\Containers\Constructor\Page\Actions\CreatePageAction;
use App\Containers\Constructor\Page\Actions\CreatePageActionInterface;
use App\Containers\Constructor\Page\Actions\DeletePageAction;
use App\Containers\Constructor\Page\Actions\DeletePageActionInterface;
use App\Containers\Constructor\Page\Actions\FindPageByIdAction;
use App\Containers\Constructor\Page\Actions\FindPageByIdActionInterface;
use App\Containers\Constructor\Page\Actions\GetAllPagesAction;
use App\Containers\Constructor\Page\Actions\GetAllPagesActionInterface;
use App\Containers\Constructor\Page\Actions\UpdatePageAction;
use App\Containers\Constructor\Page\Actions\UpdatePageActionInterface;
use App\Containers\Constructor\Page\Data\Repositories\PageFieldRepository;
use App\Containers\Constructor\Page\Data\Repositories\PageFieldRepositoryInterface;
use App\Containers\Constructor\Page\Data\Repositories\PageRepository;
use App\Containers\Constructor\Page\Data\Repositories\PageRepositoryInterface;
use App\Containers\Constructor\Page\Models\Page;
use App\Containers\Constructor\Page\Models\PageField;
use App\Containers\Constructor\Page\Models\PageFieldInterface;
use App\Containers\Constructor\Page\Models\PageInterface;
use App\Containers\Constructor\Page\Tasks\ActivatePageTask;
use App\Containers\Constructor\Page\Tasks\ActivatePageTaskInterface;
use App\Containers\Constructor\Page\Tasks\CreateFieldTask;
use App\Containers\Constructor\Page\Tasks\CreateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\CreatePageTask;
use App\Containers\Constructor\Page\Tasks\CreatePageTaskInterface;
use App\Containers\Constructor\Page\Tasks\DeleteFieldTask;
use App\Containers\Constructor\Page\Tasks\DeleteFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\DeletePageTask;
use App\Containers\Constructor\Page\Tasks\DeletePageTaskInterface;
use App\Containers\Constructor\Page\Tasks\FindPageByIdTask;
use App\Containers\Constructor\Page\Tasks\FindPageByIdTaskInterface;
use App\Containers\Constructor\Page\Tasks\GetAllPagesTask;
use App\Containers\Constructor\Page\Tasks\GetAllPagesTaskInterface;
use App\Containers\Constructor\Page\Tasks\UpdateFieldTask;
use App\Containers\Constructor\Page\Tasks\UpdateFieldTaskInterface;
use App\Containers\Constructor\Page\Tasks\UpdatePageTask;
use App\Containers\Constructor\Page\Tasks\UpdatePageTaskInterface;
use App\Ship\Parents\Providers\MainProvider;


class MainServiceProvider extends MainProvider
{
    public function register(): void
    {
        parent::register();

        $this->bindActions();
        $this->bindTasks();
        $this->bindRepositories();
        $this->bindModels();
    }

    private function bindActions(): void
    {
        $this->app->bind(CreatePageActionInterface::class, CreatePageAction::class);
        $this->app->bind(DeletePageActionInterface::class, DeletePageAction::class);
        $this->app->bind(FindPageByIdActionInterface::class, FindPageByIdAction::class);
        $this->app->bind(GetAllPagesActionInterface::class, GetAllPagesAction::class);
        $this->app->bind(UpdatePageActionInterface::class, UpdatePageAction::class);
        $this->app->bind(ActivatePageActionInterface::class, ActivatePageAction::class);
    }

    private function bindTasks(): void
    {
        $this->app->bind(CreatePageTaskInterface::class, CreatePageTask::class);
        $this->app->bind(DeletePageTaskInterface::class, DeletePageTask::class);
        $this->app->bind(FindPageByIdTaskInterface::class, FindPageByIdTask::class);
        $this->app->bind(GetAllPagesTaskInterface::class, GetAllPagesTask::class);
        $this->app->bind(UpdatePageTaskInterface::class, UpdatePageTask::class);
        
        $this->app->bind(ActivatePageTaskInterface::class, ActivatePageTask::class);

        $this->app->bind(CreateFieldTaskInterface::class, CreateFieldTask::class);
        $this->app->bind(UpdateFieldTaskInterface::class, UpdateFieldTask::class);
        $this->app->bind(DeleteFieldTaskInterface::class, DeleteFieldTask::class);
    }

    private function bindRepositories(): void
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(PageFieldRepositoryInterface::class, PageFieldRepository::class);
    }

    private function bindModels(): void
    {
        $this->app->bind(PageInterface::class, Page::class);
        $this->app->bind(PageFieldInterface::class, PageField::class);
    }
}