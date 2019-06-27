<?php

namespace twsihan\admin\components\filters;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\yii\helpers\StringHelper;
use twsihan\admin\components\web\Controller;
use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\filters\AccessRule;
use yii\web\ForbiddenHttpException;
use yii\base\InvalidConfigException;
use yii\web\User;

/**
 * Class Access
 *
 * @package twsihan\admin\components\filters
 * @author twsihan <twsihan@gmail.com>
 */
class Access extends ActionFilter
{
    /**
     * @var User|array|string|false the user object representing the authentication status or the ID of the user application component.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     * Starting from version 2.0.12, you can set it to `false` to explicitly switch this component support off for the filter.
     */
    public $user = 'user';
    /**
     * @var callable a callback that will be called if the access should be denied
     * to the current user. This is the case when either no rule matches, or a rule with
     * [[AccessRule::$allow|$allow]] set to `false` matches.
     * If not set, [[denyAccess()]] will be called.
     *
     * The signature of the callback should be as follows:
     *
     * ```php
     * function ($rule, $action)
     * ```
     *
     * where `$rule` is the rule that denies the user, and `$action` is the current [[Action|action]] object.
     * `$rule` can be `null` if access is denied because none of the rules matched.
     */
    public $denyCallback;
    /**
     * @var array the default configuration of access rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = ['class' => 'yii\filters\AccessRule'];
    /**
     * @var array a list of access rule objects or configuration arrays for creating the rule objects.
     * If a rule is specified via a configuration array, it will be merged with [[ruleConfig]] first
     * before it is used for creating the rule object.
     * @see ruleConfig
     */
    public $rules = [];

    public $allowAction = [];


    /**
     * @return User
     * @throws InvalidConfigException
     */
    public function getUser(): User
    {
        if (empty($this->user)) {
            $this->user = ParamsHelper::getUser();
        } else if (!$this->user instanceof User) {
            $this->user = Instance::ensure($this->user, User::class);
        }
        return $this->user;
    }

    /**
     * @param User|string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        parent::init();
        foreach ($this->rules as $i => $rule) {
            if (is_array($rule)) {
                $this->rules[$i] = Yii::createObject(array_merge($this->ruleConfig, $rule));
            }
        }
    }

    /**
     * @param Action $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     */
    public function beforeAction($action)
    {
        /* @var User $user */
        $user = $this->getUser();
        $request = Yii::$app->getRequest();

        /* @var AccessRule $rule */
        foreach ($this->rules as $rule) {
            if ($allow = $rule->allows($action, $user, $request)) {
                return true;
            } elseif ($allow === false) {
                if (isset($rule->denyCallback) && is_callable($rule->denyCallback)) {
                    call_user_func($rule->denyCallback, $rule, $action);
                } elseif (is_callable($this->denyCallback)) {
                    call_user_func($this->denyCallback, $rule, $action);
                } else {
                    $this->denyAccess($user);
                }

                return false;
            }
        }

        $uniqueId = $action->getUniqueId();
        if ($user->can($uniqueId, $request->get())) {
            return true;
        }

        if (is_callable($this->denyCallback)) {
            call_user_func($this->denyCallback, null, $action);
        } else {
            $this->denyAccess($user);
        }

        return false;
    }

    /**
     * @param User $user
     * @throws ForbiddenHttpException
     */
    protected function denyAccess($user)
    {
        if ($user !== false && $user instanceof User) {
            if ($user->getIsGuest()) {
                $user->loginRequired();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function isActive($action)
    {
        if ($this->allowAction($action)) {
            return false;
        }

        return parent::isActive($action);
    }

    private function allowAction($action)
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return true;
        }

        /* @var User $user */
        $user = $this->getUser();
        if ($user->getIsGuest()) {
            $loginUrl = null;
            if (is_array($user->loginUrl) && isset($user->loginUrl[0])) {
                $loginUrl = $user->loginUrl[0];
            } else if (is_string($user->loginUrl)) {
                $loginUrl = $user->loginUrl;
            }
            if (!is_null($loginUrl) && trim($loginUrl, '/') === $uniqueId) {
                return true;
            }
        }

        $actionId = $this->getActionId($action);

        $allow = false;
        /* @var Controller $controller */
        $controller = $action->controller;
        if ($controller->hasMethod('allowAction')) {
            $allowAction = $controller->allowAction();
            $allow = empty($allowAction) ? false : (in_array($actionId, $allowAction) ? true : false);
        }
        $allowAction = $this->allowAction;
        if (!$allow) {
            foreach ($allowAction as $pattern) {
                if (StringHelper::matchWildcard($pattern, $actionId)) {
                    $allow = true;
                    break;
                }
            }
        }

        return $allow;
    }
}
