<?php
/* @var $generator \mootensai\enhancedgii\Generator */
$tableSchema = $generator->getDbConnection()->getTableSchema($relations[3]);
$fk = $generator->generateFK($tableSchema);
//print_r($fk);
echo "<?php\n";
?>
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

Pjax::begin();
$dataProvider = new ArrayDataProvider([
    'allModels' => $row,
]);
echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'formName' => '<?= $relations[1];?>',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
<?php foreach ($tableSchema->getColumnNames() as $attribute) : 
    $column = $tableSchema->getColumn($attribute);
    if(!in_array($attribute, $generator->skippedColumns)) {
        echo "    [\n        " . $generator->generateTabularFormField($attribute,$fk, $tableSchema) . "\n    ],\n";
    }
endforeach;?>
        'del' => [
            'type' => TabularForm::INPUT_STATIC,
            'label' => '',
            'value' => function($model, $key) {
            return Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('frontend', 'Delete'), 'onClick' => 'delRow(' . $key . '); return false;', 'id' => '<?= yii\helpers\Inflector::camel2id($relations[1]) ?>-del-btn']);
            },
                ]
            ],
            'gridSettings' => [
                'panel' => [
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> ' . Yii::t('frontend', '<?= yii\helpers\Inflector::camel2words($relations[1]) ?>') . '  </h3>',
                    'type' => GridView::TYPE_PRIMARY,
                    'before' => false,
                    'footer' => false,
                    'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('frontend', 'Add Row'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRow()']),
                ]
            ]
]);
Pjax::end();
?>