<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 13.04.15
 * Time: 18:07
 */

namespace yii\boxy\components;

use yii\base\InvalidConfigException;

/**
 * Class User
 * @package yii\boxy\components
 *
 *
 * ```
 * 'user' => [
 *     'identityClass' => 'app\models\User', // User must expand yii\boxy\User and implement the IdentityInterface
 *     'accessTokenClass' => 'yii\boxy\AccessToken'
 *     // ...
 * ]
 * ```
 */
class User extends \yii\web\User {
    public $accessTokenClass;

    /**
     * Initializes the application component.
     */
    public function init() {
        parent::init();

        if ($this->accessTokenClass === null) {
            throw new InvalidConfigException('User::accessTokenClass must be set.');
        }
    }
}