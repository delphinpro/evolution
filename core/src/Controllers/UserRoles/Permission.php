<?php namespace EvolutionCMS\Controllers\UserRoles;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Interfaces\ManagerTheme\PageControllerInterface;
use EvolutionCMS\Models\Permissions;
use EvolutionCMS\Facades\ManagerTheme;

class Permission extends AbstractController implements PageControllerInterface
{
    protected string $view = 'page.user_roles.permission';

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return ManagerTheme::getCore()->hasPermission('edit_role');
    }

    public function process() : bool
    {
        if(isset($_GET['action']) && $_GET['action'] == 'delete' ){
            Permissions::query()->where('id', $this->getElementId())->delete();
            header('Location: index.php?a=86&tab=2');
        }
        if (isset($_POST['a'])) {
            $this->updateOrCreate();
            return true;
        }

        return true;
    }

    public function updateOrCreate()
    {
        $group_id = $_POST['group_id'];
        if(isset($_POST['newcategory']) && $_POST['newcategory'] != ''){
            $group_id = PermissionsGroups::findCategoryOrNew($_POST['newcategory']);
        }
        if(!isset($_POST['disabled'])){
            $_POST['disabled'] = 0;
        }
        $id = $this->getElementId();
        $group = Permissions::findOrNew($id);
        $group->name = $_POST['name'];
        $group->lang_key = $_POST['lang_key'];
        $group->key = $_POST['key'];
        $group->group_id = $group_id;
        $group->disabled = $_POST['disabled'];
        $group->save();
        header('Location: index.php?a=135&id=' . $group->getKey() . '&r=9');

    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        $id = $this->getElementId();
        return [
            'permission' => Permissions::findOrNew($id),
            'categories' => Model::query()->select('id', 'name as category')
        ];
    }
}
