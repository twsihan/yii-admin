<?php

namespace twsihan\admin\models\mysql;

use twsihan\yii\helpers\ArrayHelper;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Class Menu
 *
 * @package twsihan\admin\models\mysql
 * @author twsihan <twsihan@gmail.com>
 */
class Menu extends ActiveRecord
{
    /**
     * @const string Tree Cache Name
     */
    const CACHE_TAG = 'menu.items';
    /**
     * @const int Tree Cache time
     */
    const CACHE_DURATION = 3600;

    const PARENT_DEFAULT = 0;


    /**
     * @inheritdoc
     */
    public function getMenuParent()
    {
        return $this->hasOne(Menu::class, ['id' => 'parent']);
    }

    /**
     * 获取菜单下拉
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findParent($asArray = false)
    {
        return static::find()
            ->where('parent = :parent AND route = ""', [
                ':parent' => static::PARENT_DEFAULT,
            ])->asArray($asArray)
            ->all();
    }

    /**
     * 获取菜单下拉
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findSelect($asArray = false)
    {
        return ArrayHelper::map(static::find()
            ->where('route = ""')
            ->asArray($asArray)
            ->all(), 'id', 'name');
    }

    /**
     * 获取菜单下拉树
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findSelectTree($asArray = false)
    {
        return static::find()
            ->select(['value' => 'id', 'name', 'parent'])
            ->where('route = ""')
            ->indexBy('value')
            ->asArray($asArray)
            ->all();
    }

    /**
     * 查询一条
     * @param $id
     * @param bool $asArray
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findById($id, $asArray = false)
    {
        return static::find()
            ->where(['id' => $id])
            ->asArray($asArray)
            ->one();
    }

    /**
     * Use to get assigned menu of user.
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * callback should have format like
     *
     * ```
     * function ($menu) {
     *    return [
     *        'label' => $menu['name'],
     *        'url' => [$menu['route']],
     *        'options' => $data,
     *        'items' => $menu['children']
     *        ]
     *    ]
     * }
     * ```
     * @param boolean $refresh
     * @return array
     */
    public static function getAssignedMenu($userId, $root = 0, $callback = null, $refresh = false)
    {
        $manager = Yii::$app->authManager;
        $menus = Menu::find()->asArray()->indexBy('id')->all();
        $key = [__METHOD__, $userId, $manager->defaultRoles];
        $cache = Yii::$app->cache;
        if ($refresh || $cache === null || ($assigned = $cache->get($key)) === false) {
            $routes = $filter1 = $filter2 = [];
            if ($userId !== null) {
                foreach ($manager->getPermissionsByUser($userId) as $name => $value) {
                    $routes[] = $name;
                }
            }
            $routes = array_unique($routes);
            sort($routes);
            $prefix = '\\';
            foreach ($routes as $route) {
                if (strpos($route, $prefix) !== 0) {
                    if (substr($route, -1) === '/') {
                        $prefix = $route;
                        $filter1[] = $route . '%';
                    } else {
                        $filter2[] = $route;
                    }
                }
            }
            $assigned = [];
            $query = Menu::find()->select(['id'])->asArray();
            if (count($filter2)) {
                $assigned = $query->where(['route' => $filter2])->column();
            }
            if (count($filter1)) {
                $query->where('route like :filter');
                foreach ($filter1 as $filter) {
                    $assigned = array_merge($assigned, $query->params([':filter' => $filter])->column());
                }
            }
            $assigned = static::requiredParent($assigned, $menus);
            if ($cache !== null) {
                $cache->set($key, $assigned, static::CACHE_DURATION, new TagDependency([
                    'tags' => static::CACHE_TAG
                ]));
            }
        }
        $key = [__METHOD__, $assigned, $root];
        if ($refresh || $callback !== null || $cache === null || (($result = $cache->get($key)) === false)) {
            $result = static::normalizeMenu($assigned, $menus, $callback, $root);
            if ($cache !== null && $callback === null) {
                $cache->set($key, $result, static::CACHE_DURATION, new TagDependency([
                    'tags' => static::CACHE_TAG
                ]));
            }
        }
        return $result;
    }

    /**
     * Ensure all item menu has parent.
     * @param array $assigned
     * @param array $menus
     * @return array
     */
    private static function requiredParent($assigned, &$menus)
    {
        $l = count($assigned);
        for ($i = 0; $i < $l; $i++) {
            $id = $assigned[$i];
            $parentId = $menus[$id]['parent'];
            if ($parentId != 0 && !in_array($parentId, $assigned)) {
                $assigned[$l++] = $parentId;
            }
        }
        return $assigned;
    }

    /**
     * Parse route
     * @param string $route
     * @return mixed
     */
    public static function parseRoute($route)
    {
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }
            return $url;
        }
        return '#';
    }

    /**
     * Normalize menu
     * @param array $assigned
     * @param array $menus
     * @param Closure $callback
     * @param integer $parent
     * @return array
     */
    private static function normalizeMenu(&$assigned, &$menus, $callback, $parent = 0)
    {
        $result = [];
        $order = [];
        foreach ($assigned as $id) {
            $menu = $menus[$id];
            if ($menu['parent'] == $parent) {
                $menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
                if ($callback !== null) {
                    $item = call_user_func($callback, $menu);
                } else {
                    $item = [
                        'label' => $menu['name'],
                        'url' => static::parseRoute($menu['route']),
                    ];
                    if ($menu['children'] != []) {
                        $item['items'] = $menu['children'];
                    }
                }
                $result[] = $item;
                $order[] = $menu['sort'];
            }
        }
        if ($result != []) {
            array_multisort($order, $result);
        }
        return $result;
    }
}
