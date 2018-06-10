<?php

namespace App\EventHandler;


use DateInterval;
use DateTime;
use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Psr\Log\LoggerInterface;
use Sse\Event;

class ClosedIssuesEventHandler implements Event {
    
    public function update(){
        //Here's the place to send data
        $url = getenv('GITLAB_URL');
        $privateToken = getenv('GITLAB_PRIVATE_TOKEN');
        $gitlabProjectId = getenv('GITLAB_PROJECT_ID');
        $now = new DateTime();
        $thirtyDaysAgo = $now
            ->sub(new DateInterval('P30D'))
            ->format('Y-m-dT')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}/api/v4/projects/{$gitlabProjectId}/issues?private_token={$privateToken}&state=closed&created_after={$thirtyDaysAgo}&per_page=100");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
    
        $count = count(json_decode($output));
        return json_encode([
            'current' => $count,
            'status' => 'ok',
            'updatedAt' => time()
        ]);
    }
    
    public function check(){
        //Here's the place to check when the data needs update
        //sleep(2);
        return true;
    }
}