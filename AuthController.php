<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 13.04.15
 * Time: 18:27
 */

namespace yii\boxy;


use yii\rest\Controller;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller {
    use ControllerTrait;

    public function authExcept() {
        return ['auth'];
    }

    public $modelClass;

    public function init() {
        if ($this->modelClass === null) {
            $this->modelClass = \Yii::$app->user->identityClass;
        }

        parent::init();
    }

    /**
     * Залогинивание и получение token для работы в системе
     *
     * @param string $login
     * @param string $password
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function actionAuth($login, $password) {
        /** @var User $modelClass */
        $modelClass = $this->modelClass;
        $user = $modelClass::findByLogin($login);
        if (!$user) {
            throw new UnauthorizedHttpException("User with login, email or phone not found");
        }

        if (!$user->validatePassword($password)) {
            throw new UnauthorizedHttpException("Not valid password");
        }

        $token = AccessToken::generateForUser($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Выход и удаление токена
     *
     * @throws ServerErrorHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionLogout() {
        $token = \Yii::$app->user->getIdentity()->accessToken;
        $accessToken = AccessToken::findOne(['id' => $token, 'user_uid' => \Yii::$app->user->getId()]);

        if ($accessToken->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        \Yii::$app->getResponse()->setStatusCode(204);
    }
}