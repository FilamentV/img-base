<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\UploadForm;
use yii\web\UploadedFile;
use frontend\models\Images;

class ImageController extends Controller
{
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                //save image to db
                $image = new Images();
                $image->origin_name = $model->imageFile->baseName;
                $image->translit_name = \yii\helpers\Inflector::slug($model->imageFile->baseName, '-');
                $image->path = $model->imagePath;
                $image->width = $model->getImageParams($model->imagePath)['width'];
                $image->hight = $model->getImageParams($model->imagePath)['hight'];
                $image->size = $model->imageFile->size;
                $image->user_id = \Yii::$app->user->identity->id;
                $image->created = date('Y-m-d H:i:s');
                if ($image->save()) {
                    echo 'saved!';
                }
                
                //var_dump($size = getimagesize($model->dir . $model->imageFile->baseName));die();
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }
}
