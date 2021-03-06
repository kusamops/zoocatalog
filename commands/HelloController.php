<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use linslin\yii2\curl;
use app\models\Call;
use app\models\Category;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{

    public $message;

    public function options($actionID)
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        echo $this->message."\n";

        return ExitCode::OK;
    }



    public function actionMake()
    {
        $rootPath = realpath(dirname(__FILE__).'/../');

        // print_r($rootPath);
        // die;

        //creating file
        $filename = date('H:i:s').'.txt';
        $folder = $rootPath.'/cronjob/'.$filename;

        $f = fopen($folder, 'w');
        $fw = fwrite($f, 'the time is : '.substr($filename, 0, -4));

        fclose($f);
    }

    public function actionGetFile()
    {        

        $rootPath = realpath(dirname(__FILE__).'/../');
        $filename = date('Y-m-d').'.csv';

        $folder = $rootPath.'/uploads/'.$filename;

        //$ch = curl_init("https://www.ftc.gov/system/files/attachments/do-not-call-dnc-reported-calls-data/dnc_complaint_numbers_"."2018-07-09.csv");
        $ch = curl_init("https://www.ftc.gov/system/files/attachments/do-not-call-dnc-reported-calls-data/dnc_complaint_numbers_".$filename);
        
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if($httpCode == 200) {
            curl_setopt($ch, CURLOPT_NOBODY, false);

            $fp = fopen($folder, "w");
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

        } else {
            echo "There is no data today. \n";
        }               
    }

    public function actionCsvToDatabase()
    {

        $filename = "2018-07-10.csv";
        define('CSV_PATH','uploads/');

        $csv_file = CSV_PATH . $filename;
        $filecsv = file($csv_file);
        

        foreach($filecsv as $data){
            if($data === reset($filecsv)) { continue; }
            $record = explode(",", $data);
            $newRecord = new Call;
            
            $newRecord->company_phone_number = $record[0];
            $newRecord->created_date = $record[1];
            $newRecord->violation_date = $record[2];
            $newRecord->consumer_city = $record[3];
            $newRecord->consumer_state = $record[4];
            $newRecord->subject = $record[5];
            $newRecord->recorded_message_or_robocall = ($record[6] == "Y") ? 1 : 0;

            $newRecord->save();

            // print_r($newRecord);
            // die;

        }

        // Yii::$app->db->createCommand()->batchInsert('animal',
        //         ['category_id', 'name', 'breed', 'age', 'photo'],
        //         $data)
        //         ->execute();
        
    }
}