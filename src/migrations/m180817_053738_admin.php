<?php

use yii\db\Migration;

/**
 * Class m180817_053738_admin
 */
class m180817_053738_admin extends Migration
{
    private $table = '{{%admin}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "管理员表"';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('主键'),
            'username' => $this->string(64)->notNull()->comment('账号')->unique(),
            'email' => $this->string(64)->notNull()->comment('邮箱')->unique(),
            'real_name' => $this->string(30)->notNull()->defaultValue('')->comment('姓名'),
            'avatar' => $this->string(100)->notNull()->defaultValue('')->comment('头像'),
            'address' => $this->string(100)->defaultValue('')->notNull()->comment('地址'),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'access_token' => $this->string(255)->unique(),
            'status' => $this->boolean()->notNull()->defaultValue(10)->comment('状态'),
            'last_time' => $this->Integer(11)->defaultValue(0)->notNull()->comment('上一次登录时间'),
            'last_ip' => $this->char(15)->defaultValue('')->notNull()->comment('上一次登录的IP'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
        ], $tableOptions);

        $time = time();

        $this->batchInsert($this->table, [
            'username',
            'email',
            'real_name',
            'address',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'last_time',
            'last_ip',
            'updated_at',
            'created_at',
        ], [
            [
                'admin',
                'admin@admin.com',
                '管理员',
                '湖南省,岳阳市,岳阳县',
                'gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM',
                Yii::$app->security->generatePasswordHash('admin'),
                '5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015',
                $time,
                '127.0.0.1',
                $time,
                $time,
            ],
            [
                'guest',
                'guest@admin.com',
                '客户',
                '湖南省,岳阳市,岳阳县',
                'tArp_Kv4z1JlzBUZYCL33N24AZL-_77p',
                Yii::$app->security->generatePasswordHash('guest'),
                'CgScbf1E96N3pqH01b0mVi_Z58j8QsRV_1501916190',
                $time,
                '127.0.0.1',
                $time,
                $time,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);

        echo "m180817_053738_admin cannot be reverted.\n";

        return false;
    }
}
