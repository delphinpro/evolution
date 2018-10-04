<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class SystemInfo extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.sysinfo';

    /**
     * @var \EvolutionCMS\Interfaces\DatabaseInterface
     */
    protected $database;

    public function __construct(ManagerThemeInterface $managerTheme)
    {
        parent::__construct($managerTheme);
        $this->database = evolutionCMS()->getDatabase();
    }
    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasPermission('logs');
    }

    /**
     * @inheritdoc
     */
    public function getParameters(array $params = []): array
    {
        return [
            'serverArr' => $this->parameterServerArr(),
            'tables' => $this->parameterTablesInfo(),
            'truncateable' => $this->parameterTruncateableTables()
        ];
    }

    protected function parameterTruncateableTables()
    {
        return [
            $this->database->getTableName('event_log', false),
            $this->database->getTableName('manager_log', false)
        ];
    }

    protected function parameterTablesInfo() : array
    {
        $prefix = $this->database->escape($this->database->getConfig('prefix'));
        $sql = 'SHOW TABLE STATUS FROM `' . $this->database->getConfig('database') . '` LIKE "' . $prefix . '%"';

        return $this->database->makeArray(
            $this->database->query($sql)
        );
    }

    protected function resolveCharset()
    {
        $res = $this->database->query("show variables like 'character_set_database'");
        $charset = $this->database->getRow($res, 'num');

        return $charset[1];
    }

    protected function resolveCollation()
    {
        $res = $this->database->query("show variables like 'collation_database'");
        $collation = $this->database->getRow($res, 'num');

        return $collation[1];
    }

    protected function parameterServerArr(): Collection
    {
        return new Collection([
            'modx_version'       => [
                'is_lexicon' => true,
                'data'       => implode(' ', [
                    evolutionCMS()->getVersionData('version'),
                    evolutionCMS()->getVersionData('new_version')
                ])
            ],
            'release_date'       => [
                'is_lexicon' => true,
                'data'       => evolutionCMS()->getVersionData('release_date')
            ],
            'PHP Version'        => [
                'data' => phpversion(),
                'render' => 'manager::' . $this->getView() . '.phpversion'
            ],
            'access_permissions' => [
                'is_lexicon' => true,
                'data'       => $this->managerTheme->getLexicon(
                    (bool)evolutionCMS()->getConfig('use_udperms') ? 'enabled' : 'disabled'
                )
            ],
            'servertime'         => [
                'is_lexicon' => true,
                'data'       => strftime('%H:%M:%S', time())
            ],
            'localtime'          => [
                'is_lexicon' => true,
                'data'       => strftime('%H:%M:%S', time() + evolutionCMS()->getConfig('server_offset_time'))
            ],
            'serveroffset'       => [
                'is_lexicon' => true,
                'data'       => evolutionCMS()->getConfig('server_offset_time') / (60 * 60) . ' h'
            ],
            'database_name'      => [
                'is_lexicon' => true,
                'data'       => evolutionCMS()->getService('config')->get('database.connections.default.database')
            ],
            'database_server'    => [
                'is_lexicon' => true,
                'data'       => evolutionCMS()->getService('config')->get('database.connections.default.host')
            ],
            'database_version'   => [
                'is_lexicon' => true,
                'data'       => $this->database->getVersion()
            ],
            'database_charset'   => [
                'is_lexicon' => true,
                'data'       => $this->resolveCharset()
            ],
            'database_collation' => [
                'is_lexicon' => true,
                'data'       => $this->resolveCollation()
            ],
            'table_prefix'       => [
                'is_lexicon' => true,
                'data'       => evolutionCMS()->getService('config')->get('database.connections.default.prefix')
            ],
            'cfg_base_path'      => [
                'is_lexicon' => true,
                'data'       => MODX_BASE_PATH
            ],
            'cfg_base_url'       => [
                'is_lexicon' => true,
                'data'       => MODX_BASE_URL
            ],
            'cfg_manager_url'    => [
                'is_lexicon' => true,
                'data'       => MODX_MANAGER_URL
            ],
            'cfg_manager_path'   => [
                'is_lexicon' => true,
                'data'       => MODX_MANAGER_PATH
            ],
            'cfg_site_url'       => [
                'is_lexicon' => true,
                'data'       => MODX_SITE_URL
            ]
        ]);
    }
}
