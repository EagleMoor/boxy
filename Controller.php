<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 13.04.15
 * Time: 18:25
 */

namespace yii\boxy;


use yii\filters\auth\HttpBearerAuth;

class Controller extends \yii\rest\Controller {
    protected $authExcept = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (false !== $this->authExcept) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
                'except' => $this->authExcept
            ];
        }

        return $behaviors;
    }
}