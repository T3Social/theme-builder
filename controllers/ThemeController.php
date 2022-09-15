<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\controllers;

use humhub\components\Theme;
use humhub\modules\admin\components\Controller;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\themebuilder\helpers\ThemeHelper;
use humhub\modules\themebuilder\models\IconForm;
use humhub\modules\themebuilder\models\LessVariables;
use humhub\modules\themebuilder\models\LoginForm;
use humhub\modules\themebuilder\Module;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * Class ThemeController
 *
 * @property Module $module
 * @package humhub\modules\themebuilder\controllers
 */
class ThemeController extends Controller
{

    /**
     * @var Theme
     */
    public $theme = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        /*
        $behaviors[] = [
            'class' => 'yii\filters\HttpCache',
            'only' => ['load-file'],
            'lastModified' => function ($action, $params) {
                if (strpos(Yii::$app->request->get('file'), 'variables')) {
                    return time() + 10000;
                }
                return filemtime($this->parseFileName(Yii::$app->request->get('file')));
            },
        ];
        */

        return $behaviors;
    }

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        if ($action->id === 'load-file') {
            return parent::beforeAction($action);
        }

        $themeName = Yii::$app->request->get('name');

        foreach ($this->module->getEditableThemes() as $theme) {
            if ($theme->name == $themeName) {
                $this->theme = $theme;
            }
        }

        if ($this->theme === null) {
            throw new HttpException(404, 'Could not find theme!');
        }

        Yii::$app->settings->delete('themeParents');
        Yii::$app->getView()->theme = $this->theme;
        $this->theme->register();

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if ($action->id !== 'load-file') {
            Yii::$app->settings->delete('themeParents');
        }

        return parent::afterAction($action, $result);
    }


    public function actionIndex()
    {
        ThemeHelper::upgrade($this->theme);

        $viewCount = count(FileHelper::findFiles($this->theme->getBasePath() . '/views'));
        $lastBuilt = filemtime($this->theme->getBasePath() . '/css/theme.css');
        $hasLoginBackground = (
            file_exists($this->theme->getBasePath() . '/img/login-bg.png') ||
            file_exists($this->theme->getBasePath() . '/img/login-bg.jpg'));

        try {
            $themeParents = array_keys(ThemeHelper::getThemeTree($this->theme));
            if (isset($themeParents[0])) {
                unset($themeParents[0]);
            }
        } catch (Exception $e) {
            $themeParents = [];
        }

        return $this->render('index', [
            'theme' => $this->theme,
            'viewCount' => $viewCount,
            'lastBuilt' => $lastBuilt,
            'hasLoginBackground' => $hasLoginBackground,
            'themeParents' => $themeParents
        ]);
    }

    public function actionLess()
    {
        ThemeHelper::upgrade($this->theme);

        $model = new LessVariables();
        $model->theme = $this->theme;
        $model->loadVariables();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->view->saved();
            return $this->redirect(['index', 'name' => $this->theme->name]);
        }

        return $this->render('less', [
            'theme' => $this->theme,
            'model' => $model
        ]);
    }

    /**
     * @param $file
     * @throws HttpException
     */
    public function actionLoadFile($file)
    {
        $fileName = realpath($this->parseFileName($file));

        if (strpos($file, 'variables') !== false) {
            Yii::$app->response->headers->set('Cache-Control', 'no-cache');
        } else {
            Yii::$app->response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($fileName)) . ' GMT');

        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        $pathInfo = pathinfo($fileName);

        if (!isset($pathInfo['extension'])) {
            return '';
        }


        if ($pathInfo['extension'] != 'less') {
            throw new HttpException(404, 'Invalid: ' . $fileName);
        }

        if (!file_exists($fileName)) {
            throw new HttpException(404, 'Not found: ' . $fileName);
        }


        return file_get_contents($fileName);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->theme = $this->theme;

        if (Yii::$app->request->get('delete')) {
            $model->delete();
            $this->view->saved();
            return $this->redirect(['index', 'name' => $this->theme->name]);
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->backgroundFile = UploadedFile::getInstance($model, 'backgroundFile');
            if ($model->validate() && $model->save()) {
                $this->view->saved();
                return $this->redirect(['login', 'name' => $this->theme->name]);
            }
        }

        return $this->render('login', ['model' => $model]);
    }

    public function actionViews()
    {
        $views = [];
        $basePath = $this->theme->getBasePath() . '/views';
        foreach (FileHelper::findFiles($basePath) as $file) {
            $views[] = str_replace($basePath, '', $file);
        }

        $deleteFile = Yii::$app->request->get('deleteFile');
        if (in_array($deleteFile, $views)) {
            unlink($basePath . $deleteFile);
            $this->view->saved();
            return $this->redirect(['views', 'name' => $this->theme->name]);
        }


        return $this->render('views', [
            'theme' => $this->theme,
            'views' => $views
        ]);
    }

    public function actionEditView($view)
    {
        /*
        $views = [];
        $basePath = $this->theme->getBasePath() . '/views';
        foreach (FileHelper::findFiles($basePath) as $file) {
            $views[] = str_replace($basePath, '', $file);
        }

        $deleteFile = Yii::$app->request->get('deleteFile');
        if (in_array($deleteFile, $views)) {
            unlink($basePath . $deleteFile);
            $this->view->saved();
            return $this->redirect(['views', 'name' => $this->theme->name]);
        }
        */

        return $this->render('edit-view', [
            'theme' => $this->theme,
            'view' => $view
        ]);
    }


    public function actionIcon()
    {
        $model = new IconForm();
        $model->theme = $this->theme;

        if (Yii::$app->request->get('delete')) {
            $model->delete();
            $this->view->saved();
            return $this->redirect(['icon', 'name' => $this->theme->name]);
        }

        require Yii::getAlias('@theme-builder/vendor/class-php-ico.php');

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->iconFile = UploadedFile::getInstance($model, 'iconFile');
            if ($model->validate() && $model->save()) {
                $this->view->saved();
                return $this->redirect(['icon', 'name' => $this->theme->name]);
            }
        }

        return $this->render('icon', ['model' => $model]);
    }

    /**
     * Returns the filename by given URL
     *
     * @param $file
     * @return mixed
     */
    protected function parseFileName($file)
    {
        $webroot = Yii::getAlias('@webroot');
        $vendor = Yii::getAlias('@vendor');
        $webrootStatic = Yii::getAlias('@webroot-static');

        $file = str_replace('\\', '/', $file);
        $webroot = str_replace('\\', '/', $webroot);
        $vendor = str_replace('\\', '/', $vendor);
        $webrootStatic = str_replace('\\', '/', $webrootStatic);

        if (strpos($file, $webroot) === false) {
            $file = str_replace(Url::base(true), $webroot, $file);
        } else {
            $file = str_replace(Url::base(true) . '/', '', $file);
        }

        $file = str_replace($webroot . '/static', $webrootStatic, $file);
        $file = str_replace($webroot . '/protected/vendor', $vendor, $file);

        return $file;
    }

}