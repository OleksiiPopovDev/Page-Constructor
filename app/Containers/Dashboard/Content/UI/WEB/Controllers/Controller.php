<?php

namespace App\Containers\Dashboard\Content\UI\WEB\Controllers;

# @TODO Don't use Actions/Tasks from another containers. Create this functional separately into currently container
use App\Containers\Constructor\Language\Actions\GetAllLanguagesActionInterface;
use App\Containers\Constructor\Page\Actions\FindPageByIdActionInterface;
use App\Containers\Dashboard\Content\Actions\CreateContentActionInterface;
use App\Containers\Dashboard\Content\Actions\DeleteContentActionInterface;
use App\Containers\Dashboard\Content\Actions\FindContentByIdActionInterface;
use App\Containers\Dashboard\Content\Actions\GetAllContentActionInterface;
use App\Containers\Dashboard\Content\Actions\UpdateContentActionInterface;
use App\Containers\Dashboard\Content\UI\WEB\Requests\StoreContentRequest;
use App\Ship\Parents\Controllers\DashboardController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class Controller extends DashboardController
{
    public function __construct(
        private FindPageByIdActionInterface    $findPageByIdAction,
        private GetAllLanguagesActionInterface $getAllLanguagesAction,
        private FindContentByIdActionInterface $findContentByIdAction,
        private CreateContentActionInterface   $createContentAction,
        private GetAllContentActionInterface   $getAllContentsAction,
        private UpdateContentActionInterface   $updateContentAction,
        private DeleteContentActionInterface   $deleteContentAction
    )
    {
    }


    public function index(): View|Factory|Redirector|RedirectResponse|Application
    {
        $menu = $this->menuBuilder();
        /**
         * @var \App\Ship\Parents\Dto\PageDto|null $pageDto
         */
        $pageDto = $menu->get('menu')?->first();

        if ($pageDto === null) {
            return view('dashboard.base', $menu);
        }

        return redirect(route('dashboard_page_show', ['id' => $pageDto->getId()]));
    }


    public function showPage(int $pageId, int $contentId = null): Factory|View|Application
    {
        $contents = $this->getAllContentsAction->run($pageId, $contentId);
        $field    = collect($contents->first()?->getPage()->getFields())->first();

        return view(
            'dashboard@content::list',
            $this->menuBuilder()->merge([
                'pageId'    => $pageId,
                'contentId' => $contentId,
                'field'     => $field,
                'contents'  => $contents,
            ]));
    }


    public function create(int $pageId, int $contentId = null): View|Factory|Redirector|RedirectResponse|Application
    {
        $page      = $this->findPageByIdAction->run($pageId, withFields: true);
        $languages = $this->getAllLanguagesAction->run(getOnlyActive: true);

        if ($page->isActive() === false) {
            return redirect(route('dashboard_content_index'));
        }

        return view(
            'dashboard@content::content-form',
            $this->menuBuilder()->merge([
                'pageId'          => $pageId,
                'contentId'       => null,
                'parentContentId' => $contentId,
                'page'            => $page,
                'values'          => [],
                'languages'       => $languages,
            ]));
    }


    public function edit(int $contentId): Factory|View|Application
    {
        $content   = $this->findContentByIdAction->run($contentId);
        $languages = $this->getAllLanguagesAction->run(getOnlyActive: true);

        return view(
            'dashboard@content::content-form',
            $this->menuBuilder()->merge([
                'pageId'          => $content->getPageId(),
                'contentId'       => $content->getId(),
                'parentContentId' => $contentId,
                'page'            => $content->getPage(),
                'values'          => $content->getValues()->toArray(),
                'languages'       => $languages,
            ]));
    }


    public function store(StoreContentRequest $request): JsonResponse
    {
        $contentId = $this->createContentAction->run($request->mapped());

        return response()->json(['id' => $contentId])->setStatusCode(200);
    }


    public function update(int $id, StoreContentRequest $request): JsonResponse
    {
        $data = $request->mapped()->setId($id);
        $this->updateContentAction->run($data);

        return response()->json(['id' => $id])->setStatusCode(200);
    }


    public function destroy(int $id): JsonResponse
    {
        $this->deleteContentAction->run($id);

        return response()->json()->setStatusCode(200);
    }
}
