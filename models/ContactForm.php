<?php

namespace app\models;

use Yii;
use yii\base\Model;


class ContactForm extends Model {

    public $name, $email, $subject, $body, $verifyCode;


    public function rules() {
        return [
            [['name', 'email', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha', 'captchaAction' => 'site/captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'verifyCode' => 'Подтвердите код',
            'name' => 'Имя',
            'email' => 'Эл. почта',
            'subject' => 'Тема',
            'body' => 'Сообщение',
        ];
    }

    public function contact($emailto) {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                    ->setFrom(['noreply@combination.cf' => 'noreply@combination.cf']) 
                    ->setTo($emailto)
                    ->setSubject('Контакты - combination.cf')
                    ->setTextBody(
                            'От: ' . $this->name . PHP_EOL .
                            'Эл. почта: ' . $this->email . PHP_EOL .
                            'Сообщение: ' . $this->body)
                    ->send(); 

            return true;
        } else {
            return false;
        }
    }

}
