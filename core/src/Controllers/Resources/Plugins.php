<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use EvolutionCMS\Models\Category;
use EvolutionCMS\Models\SitePlugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use EvolutionCMS\Facades\ManagerTheme;

//'actions'=>array('edit'=>array(102,'edit_plugin'), 'duplicate'=>array(105,'new_plugin'), 'remove'=>array(104,'delete_plugin')),
class Plugins extends AbstractResources implements TabControllerInterface
{
    protected string $view = 'page.resources.plugins';

    /**
     * {@inheritdoc}
     */
    public function getTabName($withIndex = true): string
    {
        if ($withIndex) {
            return 'tabPlugins-' . $this->getIndex();
        }

        return 'tabPlugins';
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return ManagerTheme::getCore()->hasAnyPermissions([
            'new_plugin',
            'edit_plugin'
        ]);
    }

    protected function getBaseParams()
    {
        return array_merge(
            parent::getParameters(),
            [
                'tabPageName'      => $this->getTabName(false),
                'tabIndexPageName' => $this->getTabName(),
                'checkOldPlugins'  => $this->checkOldPlugins()
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []) : array
    {
        $params = array_merge($this->getBaseParams(), $params);

        if ($this->isNoData()) {
            return $params;
        }

        return array_merge([
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
        ], $params);
    }

    protected function parameterOutCategory(): Collection
    {
        return SitePlugin::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->lockedView()
            ->get();
    }

    protected function parameterCategories(): Collection
    {
        return Category::with('plugins')
            ->whereHas('plugins', function (Builder $builder) {
                return $builder->lockedView();
            })->orderBy('rank', 'ASC')
            ->get();
    }

    protected function checkOldPlugins(): bool
    {
        $p = SitePlugin::disabledAlternative()->get();
        return (bool)$p->count(
            function($alternative){
                return (int)($alternative->count() > 0);
            });
    }
}
