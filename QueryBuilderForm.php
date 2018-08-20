<?php

namespace leandrogehlen\querybuilder;


use yii\base\InvalidConfigException;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;


/**
 * QueryBuilderForm renders a form for to submit rule information.
 *
 * This form renders hidden input with name defined into [[rulesParam]].
 * The hidden input will be used to send JSON rules into string format.
 *
 * The typical usage of QueryBuilderForm is as follows,
 *
 * ```php
 * <?php QueryBuilderForm::begin([
 *    'rules' => $rules,
 *    'builder' => [
 *        'id' => 'query-builder',
 *        'pluginOptions' => [
 *            'filters' => [
 *                ['id' => 'id', 'label' => 'Id', 'type' => 'integer'],
 *                ['id' => 'name', 'label' => 'Name', 'type' => 'string'],
 *                ['id' => 'lastName', 'label' => 'Last Name', 'type' => 'string']
 *            ]
 *        ]
 *    ]
 * ])?>
 *
 *    <?= Html::submitButton('Apply'); ?>
 *
 * <?php QueryBuilderForm::end() ?>
 * ```
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilderForm extends Widget
{
    /**
     * @param array|string $action the form action URL. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @see method for specifying the HTTP method for this form.
     */
    public $action = [''];

    /**
     * @var string the form submission method. This should be either 'post' or 'get'. Defaults to 'get'.
     *
     * When you set this to 'get' you may see the url parameters repeated on each request.
     * This is because the default value of [[action]] is set to be the current request url and each submit
     * will add new parameters instead of replacing existing ones.
     */
    public $method = 'get';

    /**
     * @var array the HTML attributes (name-value pairs) for the form tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string the hidden input name that will be used to send JSON rules into string format
     */
    public $rulesParam = 'rules';

    /**
     * @var array|QueryBuilder QueryBuilder column configuration.
     * For example,
     *
     * ```php
     * <?= QueryBuilderForm::widget([
     *    'builder' => [
     *        'id' => 'query-builder',
     *        'filters' => [
     *            ['id' => 'id', 'label' => 'Id', 'type' => 'integer'],
     *            ['id' => 'name', 'label' => 'Name', 'type' => 'string'],
     *            ['id' => 'lastName', 'label' => 'Last Name', 'type' => 'string']
     *        ]
     *    ]
     *]) ?>
     * ```
     */
    public $builder;

    /**
     * @var string JSON rules representation into array format
     */
    public $rules;

    /**
     * @inheritdoc
     */
    public function init()
    {

        if (is_array($this->builder)) {
            //lai fa 5x
            $this -> builder['pluginOptions']['icons'] =[
                'add_group' => 'fal fa-plus-circle',
                'add_rule' => 'fal fa-plus',
                'remove_group' => 'fal fa-minus-circle',
                'remove_rule' => 'fal fa-minus',
                'error' => 'fal fa-exclamation-triangle'
            ];
            //lai tulkojums no db
            $this -> builder['pluginOptions']['lang'] => [
                "add_rule" => Yii::t("app","Add rule"),
                "add_group" => Yii::t("app","Add group"),
                "delete_rule" => Yii::t("app","Delete"),
                "delete_group" => Yii::t("app","Delete"),
                "conditions" => [
                    "AND" => Yii::t("app","AND"),
                    "OR" => Yii::t("app","OR"),
                ],
                "operators" => [
                    "equal" => Yii::t("app","equal"),
                    "not_equal" => Yii::t("app","not equal"),
                    "in" => Yii::t("app","in"),
                    "not_in" => Yii::t("app","not in"),
                    "less" => Yii::t("app","less"),
                    "less_or_equal" => Yii::t("app","less or equal"),
                    "greater" => Yii::t("app","greater"),
                    "greater_or_equal" => Yii::t("app","greater or equal"),
                    "between" => Yii::t("app","between"),
                    "not_between" => Yii::t("app","not between"),
                    "begins_with" => Yii::t("app","begins with"),
                    "not_begins_with" => Yii::t("app","doesn't begin with"),
                    "contains" => Yii::t("app","contains"),
                    "not_contains" => Yii::t("app","doesn't contain"),
                    "ends_with" => Yii::t("app","ends with"),
                    "not_ends_with" => Yii::t("app","doesn't end with"),
                    "is_empty" => Yii::t("app","is empty"),
                    "is_not_empty" => Yii::t("app","is not empty"),
                    "is_null" => Yii::t("app","is null"),
                    "is_not_null" => Yii::t("app","is not null"),
                ],
                "errors" => [
                    "no_filter" => Yii::t("app","No filter selected"),
                    "empty_group" => Yii::t("app","The group is empty"),
                    "radio_empty" => Yii::t("app","No value selected"),
                    "checkbox_empty" => Yii::t("app","No value selected"),
                    "select_empty" => Yii::t("app","No value selected"),
                    "string_empty" => Yii::t("app","Empty value"),
                    "string_exceed_min_length" => Yii::t("app","Must contain at least {0} characters"),
                    "string_exceed_max_length" => Yii::t("app","Must not contain more than {0} characters"),
                    "string_invalid_format" => Yii::t("app","Invalid format ({0})"),
                    "number_nan" => Yii::t("app","Not a number"),
                    "number_not_integer" => Yii::t("app","Not an integer"),
                    "number_not_double" => Yii::t("app","Not a real number"),
                    "number_exceed_min" => Yii::t("app","Must be greater than {0}"),
                    "number_exceed_max" => Yii::t("app","Must be lower than {0}"),
                    "number_wrong_step" => Yii::t("app","Must be a multiple of {0}"),
                    "number_between_invalid" => Yii::t("app","Invalid values, {0} is greater than {1}"),
                    "datetime_empty" => Yii::t("app","Empty value"),
                    "datetime_invalid" => Yii::t("app","Invalid date format ({0})"),
                    "datetime_exceed_min" => Yii::t("app","Must be after {0}"),
                    "datetime_exceed_max" => Yii::t("app","Must be before {0}"),
                    "datetime_between_invalid" => Yii::t("app","Invalid values, {0} is greater than {1}"),
                    "boolean_not_valid" => Yii::t("app","Not a boolean"),
                    "operator_not_multiple" => Yii::t("app","Operator \"{1}\" cannot accept multiple values"),
                ],
                "invert" => Yii::t("app","Invert"),
                "NOT" => Yii::t("app","NOT"),
            ];


            $this->builder = Yii::createObject(array_merge([
                    'class' => QueryBuilder::className(),
                ], $this->builder)
            );
        }

        if (!$this->builder instanceof QueryBuilder) {
            throw new InvalidConfigException('The "builder" property must be instance of "QueryBuilder');
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        echo $this->builder->run();
        echo Html::beginForm($this->action, $this->method, $this->options);
        echo Html::hiddenInput($this->rulesParam);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endForm();

        $id = $this->options['id'];
        $builderId = $this->builder->getId();
        $view = $this->getView();
        if ($this->rules) {
            $rules = Json::encode($this->rules);
            $view->registerJs("$('#{$builderId}').queryBuilder('setRules', {$rules});");
        }

        $frm = Inflector::variablize("frm-$id-querybuilder");
        $btn = Inflector::variablize("btn-$id-querybuilder-reset");

        $view->registerJs("var $frm = $('#{$id}');");
        $view->registerJs(<<<JS
    var $btn = {$frm}.find('button:reset:first');
    if ($btn.length){
        $btn.on('click', function(){
            $('#{$builderId}').queryBuilder('reset');
        });
    }
JS
        );

        $view->registerJs(<<<JS
{$frm}.on('submit', function(){
    var rules = $('#{$builderId}').queryBuilder('getRules');
    if ($.isEmptyObject(rules)) {
        return false;
    } else {
        var input = $(this).find("input[name='{$this->rulesParam}']:first");
        input.val(JSON.stringify(rules));
    }
});
JS
        );
    }
}
