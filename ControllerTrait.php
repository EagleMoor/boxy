<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 15.04.15
 * Time: 17:54
 */

namespace yii\boxy;

trait ControllerTrait {

    protected function authExcept() {
        return [];
    }

    /**
     * Добавление модуля авторизации
     *
     * ```php
     * $authExcept = false — отключить авторизацию
     * $authExcept = [] — включить авторизацию
     * $authExcept = ['auth'] — включить авторизацию везде, кроме actionAuth
     * ```
     *
     * @return mixed
     */
    public function behaviors() {
        $behaviors = parent::behaviors();

        $authExcept = $this->authExcept();

        if (false !== $authExcept) {
            $behaviors['authenticator'] = [
                'class' => \yii\filters\auth\CompositeAuth::className(),
                'authMethods' => [
                    \yii\filters\auth\HttpBearerAuth::className(),
                    \yii\filters\auth\QueryParamAuth::className(),
                ],
                'except' => $authExcept
            ];
        }

        return $behaviors;
    }

    /**
     * Поиск модели по id
     *
     * ```php
     * $modelClass = 'app\models\User';
     * ```
     *
     * @param $id
     * @param string $modelClass
     *
     * @return \yii\db\ActiveRecordInterface
     * @throws \yii\filters\auth\NotFoundHttpException
     */
    public function findModel($id, $modelClass = null) {
        $findModelClass = $modelClass;

        $fields = \Yii::getObjectVars($this);

        if (isset($fields['modelClass']) && $fields['modelClass'])
            $findModelClass = $fields['modelClass'];

        if (!$findModelClass) {
            throw new \yii\base\InvalidConfigException('Not set $modelClass');
        }

        $object = $findModelClass::findOne($id);
        if (!$object) {
            throw new \yii\filters\auth\NotFoundHttpException("Object not found: $id");
        } else {
            return $object;
        }
    }

    /**
     * Все параметры по fields & expand
     *
     * ```php
     * url?fields=id,name&expand=object
     * ```
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function extraParams() {
        $params = \Yii::getObjectVars($this);
        $serializer = 'yii\rest\Serializer';
        if (isset($params['serializer'])) {
            $serializer = $params['serializer'];
        }

        /** @var \yii\rest\Serializer $serializer */
        $serializer = \Yii::createObject($this->serializer);

        return array_merge($serializer->requestedFields[0], $serializer->requestedFields[1]);
    }
}