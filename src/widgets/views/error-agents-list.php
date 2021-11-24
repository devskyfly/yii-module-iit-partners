<?php
/* $this yii\web\View */
/* $data [] */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div>
	<table class="table">
		<caption>
			<h3>Агенты:</h3>
		</caption>
<?$i=0;?>
<?foreach ($data as $item):?>
<?$i++;?>
		<tr>
			<td><?=$i?></td>
			<td><?=Html::a($item['obj']['name'],Url::toRoute(['/iit-partners/agents/entity-edit',"entity_id"=>$item['obj']['id']]))?></td>
			<td><?=$item['msg']?></td>
		</tr>
<?endforeach;?>
	</table>
</div>