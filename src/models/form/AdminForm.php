<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\web\UploadedFile;
use twsihan\admin\models\mysql\Admin;
use Yii;
use yii\base\Model;

/**
 * Class AdminForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class AdminForm extends Model
{
    public $username;
    public $password;
    public $cPassword;
    public $realName;
    public $avatar;
    public $email;
    public $address;
    public $roleName;
    public $status;
    public $rememberMe = true;

    /**
     * @var Admin
     */
    protected $_user;
    /**
     * @var UploadedFile Model upload files
     */
    private $_uploadModel;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                    'password',
                ],
                'required',
                'message' => '{attribute}必填',
                'on' => ['create', 'login'],
            ],
            [
                [
                    'avatar',
                ],
                'file',
                'on' => ['create', 'update', 'profile'],
            ],
            [
                [
                    'roleName',
                    'status'
                ],
                'required',
                'message' => '{attribute}必选',
                'on' => ['create', 'update'],
            ],
            [
                [
                    'username',
                    'realName',
                    'email',
                    'address',
                ],
                'safe',
                'on' => ['create', 'update', 'profile'],
            ],
            [
                [
                    'password',
                    'cPassword'
                ],
                'required',
                'message' => '{attribute}必填',
                'on' => ['password'],
            ],
            ['cPassword', 'confirmPassword', 'on' => ['password']],
            ['password', 'rulesPassword', 'on' => ['login']],
            [
                [
                    'username',
                    'email',
                    'roleName',
                ],
                'safe',
                'on' => ['search'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rulesPassword($attribute)
    {
        if (!$this->_user || !$this->_user->validatePassword($this->password)) {
            $this->addError($attribute, '账户密码错误');
        }
        if ($this->_user && $this->_user->status == 1) {
            $this->addError($attribute, '账户已被禁用');
        }
    }

    /**
     * @inheritdoc
     */
    public function confirmPassword($attribute)
    {
        if ($this->password !== $this->cPassword) {
            $this->addError($attribute, '密码不一致');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '账户',
            'password' => '密码',
            'cPassword' => '确认密码',
            'avatar' => '头像',
            'realName' => '姓名',
            'email' => '邮箱',
            'address' => '地址',
            'roleName' => '所属角色',
            'status' => '状态',
            'rememberMe' => '请记住我',
        ];
    }

    /**
     * login
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function login()
    {
        $this->_user = Admin::findByUsername($this->username);

        if ($this->validate()) {
            Admin::updateAll(
                [
                    'last_time' => time(),
                    'last_ip' => Yii::$app->request->userIP,
                ], [
                    'username' => $this->username,
                    'status' => Admin::STATUS_ACTIVE
                ]
            );

            $this->_user = Admin::findByUsername($this->username);

            return Admin::appUser()->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * getUploadModel
     * @return UploadedFile
     */
    public function getUploadModel()
    {
        return $this->_uploadModel;
    }

    /**
     * setUploadModel
     * @param $uploadModel
     * @throws \yii\base\InvalidConfigException
     */
    public function setUploadModel($uploadModel)
    {
        $this->_uploadModel = Yii::createObject($uploadModel);
    }
}
