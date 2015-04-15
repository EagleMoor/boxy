<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 13.04.15
 * Time: 18:25
 */

namespace yii\boxy;


use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;

class Controller extends \yii\rest\Controller {

    public $modelClass;

    protected $authExcept = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (false !== $this->authExcept) {
            $behaviors['authenticator'] = [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],
                'except' => $this->authExcept
            ];
        }

        return $behaviors;
    }

    /**
     * @param $id
     * @param string $modelClass
     * @return \yii\db\ActiveRecordInterface
     * @throws NotFoundHttpException
     */
    public function findModel($id, $modelClass = null)
    {
        if (null === $modelClass) $modelClass = $this->modelClass;

        $object = $modelClass::findOne($id);
        if (!$object) {
            throw new NotFoundHttpException("Object not found: $id");
        } else {
            return $object;
        }
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function extraParams()
    {
        /** @var \yii\rest\Serializer $serializer */
        $serializer = \Yii::createObject($this->serializer);
        return array_merge($serializer->requestedFields[0], $serializer->requestedFields[1]);
    }
}