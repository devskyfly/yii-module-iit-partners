<?php
/* $this yii/web/view */
/* $list []*/
/* $title string */
use devskyfly\yiiModuleAdminPanel\widgets\common\NavigationMenu;
use devskyfly\yiiModuleIitPartners\widgets\ErrorAgentsList;
use yii\base\Widget;
use devskyfly\yiiModuleIitPartners\widgets\ErrorSettlementsList;

?>
<?
$this->title=$title;
?>

<div class="col-xs-3">
<?=NavigationMenu::widget(['list'=>$list])?>
</div>
<div class="col-xs-9">
<?=ErrorAgentsList::widget()?>
<?=ErrorSettlementsList::widget()?>
</div>