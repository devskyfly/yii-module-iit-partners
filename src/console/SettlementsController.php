<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\yiiModuleIitPartners\models\Settlement;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class SettlementsController extends Controller
{
    /**
     * Delete Settlements items.
     * 
     * @return number
     */
    public function actionClear()
    {
        $result='';
        try {
            $result=Settlement::truncateLikeItems();
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch (\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }
        BaseConsole::stdout('Удалено: '.$result.' строк.'.PHP_EOL);
        return 0;
    }
}