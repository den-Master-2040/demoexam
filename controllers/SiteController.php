<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Requisitions;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionIndes()
    {
        $user = new User();
        
        $user->id = 2;
        $user->username = '2';
        $user->login = '2';
        $user->password = '2';
        $user->telephone = '2';
        $user->mailAddress = '2';  
        $user->authKey = '1';
        $user->accessToken = '1';  
        //$user->load(Yii::$app->request->post());   
        if($user->save(false))             
            return 1;
        else 
            return 0;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->load(Yii::$app->request->post()) ;
        $user = User::findOne(['username'=>$model->username]);
            
        if($model->username != 0)                
        {
            Yii::$app->user->login($user,3600*24*30);
            // добавление новой куки в HTTP-ответ
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
            'id' => $user->id
            ]));
            return $this->goHome();
        }    
        

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }



    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $request = new Requisitions();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            $user = Yii::$app->getUser();
            $user_ = new User();
            $cookies = Yii::$app->request->cookies;

            // получение куки с названием "language. Если кука не существует, "en"  будет возвращено как значение по-умолчанию.
            $language = $cookies->getValue('language', 'en');
            //$user_ = User::findOne(['id'=>Yii::$app->user->identity->id]);
            

            $request->fio_user = Yii::$app->user->identity->username;
            $request->gos_num = $model->gos_number;
            $request->description = $model->body;
            $request->status = "обработка";
            $request->save(false);
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays registrate page.
     *
     * @return string
     */
    public function actionRegistrate()
    {      
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = new User();
            //$user->id = 2;
            $user->login = $model->login;
            $user->username = $model->username;
            $user->password = $model->password;
            $user->fio = $model->fio;
            $user->telephone = $model->telephone;
            $user->mailAddress = $model->mailAddress;  
            $user->authKey = '1';
            $user->accessToken = '1';  
            //$user->load(Yii::$app->request->post()); 
            $user->save(false);

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                // добавление новой куки в HTTP-ответ
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                'id' => $user->id
                ]));

                return $this->goBack();
            }
            
            //Yii::$app->user->login($user, $model->rememberMe ? 3600*24*30 : 0);
            //if (!Yii::$app->user->isGuest) {
             //   return $this->goHome();
            //}
        }

        
        return $this->render('registrate', [
            'model' => $model,
        ]);
        
    }
}
