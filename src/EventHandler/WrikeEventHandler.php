<?php

namespace App\EventHandler;


use DateInterval;
use DateTime;
use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Psr\Log\LoggerInterface;
use Sse\Event;

class WrikeEventHandler implements Event {
    
    public function update(){
        $wrikeUrl = getenv('WRIKE_URL');
        $folderId = getenv('WRIKE_FOLDER_ID');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $now = new DateTime();
        $thirtyDaysAgo = $now
            ->sub(new DateInterval('P30D'))
            ->format('Y-m-d\TH:i:s\Z')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "${wrikeUrl}/api/v3/folders/${folderId}/timelogs?createdDate={\"start\":\"${thirtyDaysAgo}\"}");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$token}",
            'Content-Type: application/json'
        ));
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        $timelogs = json_decode($output, true)['data'];
        $sum = 0;
        foreach ($timelogs as $timelog) {
            $sum += $timelog['hours'];
        }
        $wrikeTimeFormatted = $sum;
        return json_encode([
            'current' => $wrikeTimeFormatted,
            'status' => 'ok',
            'updatedAt' => time()
        ]);
    }
    
    public function check(){
        //Here's the place to check when the data needs update
        sleep(2);
        return true;
    }
}