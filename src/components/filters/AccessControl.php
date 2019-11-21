<?php

namespace twsihan\admin\components\filters;

use twsihan\admin\components\rest\ActiveController;
use twsihan\yii\helpers\StringHelper;
use Yii;
use yii\base\Action;
use yii\filters\AccessRule;
use yii\web\ForbiddenHttpException;
use yii\web\User;

/**
 * Class AccessControl
 *
 * @package twsihan\admin\components\filters
 * @author twsihan <twsihan@gmail.com>
 */
class AccessControl extends \yii\filters\AccessControl
{
    public $allowAction = [];


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
        $actionId = $this->getActionId($action);
        $allow = false;
        /* @var ActiveController $controller */
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

    /**
     * @param Action $action
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        /* @var User $user */
        $user = $this->user;
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
}
