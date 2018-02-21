<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = 'Контакты';
?>
<article class="col-xs-12 col-lg-6">

    <div class="row margin-null">
      
        <h3><?= Html::encode($this->title) ?></h3>
   
        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="alert alert-success">
                Спасибо за обращение к нам.<br>
                Мы постараемся ответить вам как можно скорее.
            </div>

        <?php else: ?>
        
            <p style="padding-top: 10px;">Ваши идеи, пожелания и предложения по сервису можно отправить через форму обратной связи:</p>
        
            <?php
            $form = ActiveForm::begin([
                        'id' => 'contact-form',
                        'options' => ['class' => 'form-horizontal'], 
                        'fieldConfig' => [
                            'template' => "<div class=\"col-lg-3\">{label}</div>\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-3 \">{error}</div>"
                        ],
            ]);
            ?>

            <?= $form->field($model, 'name')->textInput(array('placeholder' => 'Ваше имя', 'class'=>'form-control')); ?>
            <?= $form->field($model, 'email')->textInput(array('placeholder' => 'email@example.com', 'class'=>'form-control')); ?>
            <?= $form->field($model, 'body')->textArea(['rows' => 7]) ?>
            <?=
            $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/site/captcha',
                'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-7">{input}</div></div>',
            ])
            ?>
     
            <div class="form-group">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            </div>
        
    <?php ActiveForm::end(); ?>

<?php endif; ?>

    </div>
</article>

