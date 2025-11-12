<?php

namespace backend\controllers;

use common\models\page\PageAboutSectionConfig;
use common\models\page\PageAboutSectionImageConfig;
use common\models\page\PageBrandColorsConfig;
use common\models\page\PageCallToActionConfig;
use common\models\page\PageFooterContentConfig;
use common\models\page\PageGoogleTagManagerConfig;
use common\models\page\PageHeroSectionConfig;
use common\models\page\PagePortfolioImageConfig;
use common\models\page\PagePortfolioSectionConfig;
use common\models\page\PageSiteConfig;
use common\models\page\PageSocialLinkConfig;
use common\models\page\PageCustomScriptConfig;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PageConfig controller for managing page configuration
 */
class PageConfigController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'update-social-link' => ['get', 'post'],
                    'update-portfolio-image' => ['get', 'post'],
                    'update-about-section-image' => ['post'],
                    'create-portfolio-image' => ['get', 'post'],
                    'create-social-link' => ['get', 'post'],
                    'create-custom-script' => ['get', 'post'],
                    'update-custom-script' => ['get', 'post'],
                    'delete-social-link' => ['post'],
                    'delete-custom-script' => ['post'],
                    'delete-portfolio-image' => ['post'],
                    'delete-about-section-image' => ['post'],
                    'upload-image' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays the page configuration index
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Site Config page
     * @return string|\yii\web\Response
     */
    public function actionSiteConfig()
    {
        $model = PageSiteConfig::getConfig() ?: new PageSiteConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Site configuration saved successfully');
            return $this->refresh();
        }

        return $this->render('site-config', [
            'model' => $model,
        ]);
    }

    /**
     * Brand Colors page
     * @return string|\yii\web\Response
     */
    public function actionBrandColors()
    {
        $model = PageBrandColorsConfig::getConfig() ?: new PageBrandColorsConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Brand colors saved successfully');
            return $this->refresh();
        }

        return $this->render('brand-colors', [
            'model' => $model,
        ]);
    }

    /**
     * Hero Section page
     * @return string|\yii\web\Response
     */
    public function actionHeroSection()
    {
        $model = PageHeroSectionConfig::getConfig() ?: new PageHeroSectionConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Hero section saved successfully');
            return $this->refresh();
        }

        // var_dump($model->errors);
    

        return $this->render('hero-section', [
            'model' => $model,
        ]);
    }

    /**
     * Portfolio Section page
     * @return string|\yii\web\Response
     */
    public function actionPortfolioSection()
    {
        $model = PagePortfolioSectionConfig::getConfig() ?: new PagePortfolioSectionConfig();
        
        $dataProvider = new ActiveDataProvider([
            'query' => PagePortfolioImageConfig::find()->orderBy(['sort_order' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Portfolio section saved successfully');
            return $this->refresh();
        }

        return $this->render('portfolio-section', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Call to Action page
     * @return string|\yii\web\Response
     */
    public function actionCallToAction()
    {
        $model = PageCallToActionConfig::getConfig() ?: new PageCallToActionConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Call to action saved successfully');
            return $this->refresh();
        }

        return $this->render('call-to-action', [
            'model' => $model,
        ]);
    }

    /**
     * About Section page
     * @return string|\yii\web\Response
     */
    public function actionAboutSection()
    {
        $model = PageAboutSectionConfig::getConfig() ?: new PageAboutSectionConfig();
        $aboutSectionImages = PageAboutSectionImageConfig::find()->orderBy(['sort_order' => SORT_ASC])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'About section saved successfully');
            return $this->refresh();
        }

        return $this->render('about-section', [
            'model' => $model,
            'aboutSectionImages' => $aboutSectionImages,
        ]);
    }

    /**
     * Footer Content page
     * @return string|\yii\web\Response
     */
    public function actionFooterContent()
    {
        $model = PageFooterContentConfig::getConfig() ?: new PageFooterContentConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Footer content saved successfully');
            return $this->refresh();
        }

        return $this->render('footer-content', [
            'model' => $model,
        ]);
    }

    /**
     * Social Links page
     * @return string|\yii\web\Response
     */
    public function actionSocialLinks()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PageSocialLinkConfig::find()->orderBy(['sort_order' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('social-links', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Google Tag Manager page
     * @return string|\yii\web\Response
     */
    public function actionGoogleTagManager()
    {
        $model = PageGoogleTagManagerConfig::getConfig() ?: new PageGoogleTagManagerConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Google Tag Manager configuration saved successfully');
            return $this->refresh();
        }

        return $this->render('google-tag-manager', [
            'model' => $model,
        ]);
    }

    /**
     * Custom Scripts page
     * @return string
     */
    public function actionCustomScripts()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PageCustomScriptConfig::find()->orderBy(['sort_order' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('custom-scripts', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create social link
     * @return string|\yii\web\Response
     */
    public function actionCreateSocialLink()
    {
        $model = new PageSocialLinkConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Social link created successfully');
            return $this->redirect(['social-links']);
        }

        return $this->render('social-link-form', [
            'model' => $model,
        ]);
    }

    /**
     * Update social link
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateSocialLink($id)
    {
        $model = $this->findSocialLinkModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Social link updated successfully');
            return $this->redirect(['social-links']);
        }

        return $this->render('social-link-form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PageSocialLinkConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return PageSocialLinkConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSocialLinkModel($id)
    {
        if (($model = PageSocialLinkConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested social link does not exist.');
    }

    /**
     * Create portfolio image
     * @return string|\yii\web\Response
     */
    public function actionCreatePortfolioImage()
    {
        $model = new PagePortfolioImageConfig();
        
        // Set portfolio_section_id if not provided
        $portfolioSection = PagePortfolioSectionConfig::getConfig();
        if ($portfolioSection) {
            $model->portfolio_section_id = $portfolioSection->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Portfolio image created successfully');
            return $this->redirect(['portfolio-section']);
        }

        return $this->render('portfolio-image-form', [
            'model' => $model,
        ]);
    }

    /**
     * Update portfolio image
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdatePortfolioImage($id)
    {
        $model = $this->findPortfolioImageModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Portfolio image updated successfully');
            return $this->redirect(['portfolio-section']);
        }

        return $this->render('portfolio-image-form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PagePortfolioImageConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return PagePortfolioImageConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPortfolioImageModel($id)
    {
        if (($model = PagePortfolioImageConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested portfolio image does not exist.');
    }

    /**
     * Update about section image
     * @return \yii\web\Response
     */
    public function actionUpdateAboutSectionImage()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'] ?? null;
        
        $model = $id ? PageAboutSectionImageConfig::findOne($id) : new PageAboutSectionImageConfig();

        if ($id && !$model) {
            Yii::$app->session->setFlash('error', 'About section image not found');
            return $this->redirect(['about-section']);
        }

        // Handle array data from form
        $data = [];
        foreach ($post as $key => $value) {
            if (strpos($key, 'PageAboutSectionImageConfig') === 0) {
                preg_match('/\[(\d+)\]\[(\w+)\]/', $key, $matches);
                if (isset($matches[2])) {
                    $data[$matches[2]] = $value;
                }
            } else {
                $data[$key] = $value;
            }
        }

        // Set about_section_id if not provided
        if (empty($data['about_section_id'])) {
            $aboutSection = PageAboutSectionConfig::getConfig();
            if ($aboutSection) {
                $data['about_section_id'] = $aboutSection->id;
            }
        }

        if ($model->load($data, '') && $model->save()) {
            Yii::$app->session->setFlash('success', 'About section image saved successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Error saving about section image: ' . implode(', ', $model->getFirstErrors()));
        }

        return $this->redirect(['about-section']);
    }

    /**
     * Delete social link
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDeleteSocialLink($id)
    {
        $model = PageSocialLinkConfig::findOne($id);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Social link deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Error deleting social link');
        }

        return $this->redirect(['social-links']);
    }

    /**
     * Delete portfolio image
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDeletePortfolioImage($id)
    {
        $model = PagePortfolioImageConfig::findOne($id);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Portfolio image deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Error deleting portfolio image');
        }

        return $this->redirect(['portfolio-section']);
    }

    /**
     * Delete about section image
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDeleteAboutSectionImage($id)
    {
        $model = PageAboutSectionImageConfig::findOne($id);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'About section image deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Error deleting about section image');
        }

        return $this->redirect(['about-section']);
    }

    /**
     * Create custom script
     * @return string|\yii\web\Response
     */
    public function actionCreateCustomScript()
    {
        $model = new PageCustomScriptConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Custom script created successfully');
            return $this->redirect(['custom-scripts']);
        }

        return $this->render('custom-script-form', [
            'model' => $model,
        ]);
    }

    /**
     * Update custom script
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateCustomScript($id)
    {
        $model = $this->findCustomScriptModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Custom script updated successfully');
            return $this->redirect(['custom-scripts']);
        }

        return $this->render('custom-script-form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PageCustomScriptConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return PageCustomScriptConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCustomScriptModel($id)
    {
        if (($model = PageCustomScriptConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested custom script does not exist.');
    }

    /**
     * Delete custom script
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDeleteCustomScript($id)
    {
        $model = PageCustomScriptConfig::findOne($id);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Custom script deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Error deleting custom script');
        }

        return $this->redirect(['custom-scripts']);
    }

    /**
     * Upload image file
     * @return \yii\web\Response
     */
    public function actionUploadImage()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = \yii\web\UploadedFile::getInstanceByName('file');
        
        if (!$file) {
            return [
                'success' => false,
                'message' => 'No se recibió ningún archivo'
            ];
        }

        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->type, $allowedTypes)) {
            return [
                'success' => false,
                'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes (JPEG, PNG, GIF, WEBP)'
            ];
        }

        // Validar tamaño (max 5MB)
        if ($file->size > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'El archivo es demasiado grande. Máximo 5MB'
            ];
        }

        // Crear directorios de uploads si no existen
        $backendUploadDir = Yii::getAlias('@backend/web/uploads/images');
        $frontendUploadDir = Yii::getAlias('@frontend/web/uploads/images');
        
        if (!file_exists($backendUploadDir)) {
            mkdir($backendUploadDir, 0755, true);
        }
        if (!file_exists($frontendUploadDir)) {
            mkdir($frontendUploadDir, 0755, true);
        }

        // Usar el nombre original del archivo (sanitizado)
        // Si hay duplicados, se sobrescribirá el archivo existente
        $originalName = $file->name;
        // Sanitizar el nombre del archivo: eliminar caracteres especiales y espacios
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $file->extension;
        $fileName = $fileName . '.' . $extension;
        
        $backendFilePath = $backendUploadDir . '/' . $fileName;
        $frontendFilePath = $frontendUploadDir . '/' . $fileName;

        // Eliminar archivos existentes si ya existen (para permitir sobrescritura)
        if (file_exists($backendFilePath)) {
            unlink($backendFilePath);
        }
        if (file_exists($frontendFilePath)) {
            unlink($frontendFilePath);
        }

        // Guardar archivo en backend
        if ($file->saveAs($backendFilePath)) {
            // Copiar archivo al frontend también
            if (copy($backendFilePath, $frontendFilePath)) {
                // Generar URL relativa accesible desde la web (usando backend)
                $baseUrl = Yii::getAlias('@web');
                $relativeUrl = $baseUrl . '/uploads/images/' . $fileName;
                
                return [
                    'success' => true,
                    'url' => $relativeUrl,
                    'message' => 'Imagen subida exitosamente en backend y frontend'
                ];
            } else {
                // Si falla la copia al frontend, al menos el backend está guardado
                $baseUrl = Yii::getAlias('@web');
                $relativeUrl = $baseUrl . '/uploads/images/' . $fileName;
                
                return [
                    'success' => true,
                    'url' => $relativeUrl,
                    'message' => 'Imagen subida en backend, pero falló al copiar al frontend'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Error al guardar el archivo'
            ];
        }
    }
}
