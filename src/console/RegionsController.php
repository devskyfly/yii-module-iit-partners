<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\yiiModuleIitPartners\models\Region;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\Json;

class RegionsController extends Controller
{
    
    
    /**
     * Init region table from external file.
     * 
     * @return number
     */
    public function actionInit()
    {
        $db=Yii::$app->db;
        $transaction=$db->beginTransaction();
        
        try{
            $json_file_path=__DIR__.'/../migrations/regions.json';
            $json=file_get_contents($json_file_path);
            
            $result=Json::decode($json,true);
            
            foreach ($result as $item){
                $region=new Region();
                $region->active='Y';
                $region->create_date_time=(new \DateTime())->format(\DateTime::ATOM);
                $region->change_date_time=(new \DateTime())->format(\DateTime::ATOM);
                $region->name=$item['name'];
                $region->str_nmb=$item['str_nmb'];
                $region->saveLikeItem();
            }
        }catch(\Throwable $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        catch(\Exception $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $transaction->commit();
        return ExitCode::OK;
    }
    
    /**
     * Clear regions.
     * 
     * @return number
     */
    public function actionClear()
    {
        $result='';
        try {
            $result=Region::truncateLikeItems();
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