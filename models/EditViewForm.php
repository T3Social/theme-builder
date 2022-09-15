<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\models;

use yii\base\Model;


class EditViewForm extends Model
{
    public $template;

    public function rules()
    {
        return [
            [['template'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'template' => 'Template Code'
        ];
    }

}