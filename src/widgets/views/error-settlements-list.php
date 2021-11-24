<?php
/* $this yii\web\View */
/* $data [] */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div>
	<table class="table">
		<caption>
			<h3>Населенные пункты:</h3>
		</caption>
<?$i=0;?>
<?foreach ($data as $item):?>
<?$i++;?>
		<tr>
			<td><?=$i?></td>
			<td><?=Html::a($item['name'],Url::toRoute(['/iit-partners/settlements/entity-edit',"entity_id"=>$item['id']]))?></td>
		</tr>
<?endforeach;?>
	</table>
</div>