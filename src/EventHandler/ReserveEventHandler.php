<?php

namespace App\EventHandler;


use Sse\Event;

class ReserveEventHandler implements Event
{
    /**
     * @var string
     */
    private $folderId;
    
    public function __construct(string $folderId)
    {
        $this->folderId = $folderId;
    }
    
    public function update()
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        
        $ch = curl_init("$wrikeUrl/api/v3/folders/{$this->folderId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            'Content-Type: application/json',
        ]);
        $output = curl_exec($ch);
        curl_close($ch);
        $folder = json_decode($output, true)['data'][0]; // get the first folder in response
        $budget = $this->getBudget($folder['customFields']);
        return json_encode([
            'title' => $folder['title'],
            'budget' => $budget,
            'reserve' => $budget - $this->getCost($this->folderId),
            'status' => 'ok',
            'updatedAt' => time(),
        ]);
        
    }
    
    public function check()
    {
        return true;
    }
    
    private function getBudget(array $customFields): int
    {
        $budget = 0;
        foreach ($customFields as $customField) {
            $stringValue = $customField['value'];
            // TODO: manage minutes
            $hours = (int) substr($stringValue, 0, 2);
            switch ($customField['id']){
                case getenv('WRIKE_CUSTOM_FIELD_CONCEPTION'):
                    $budget += $hours * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_REALISATION'):
                    $budget += $hours * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_PILOTAGE'):
                    $budget += $hours * eval('return '.getenv('PILOTAGE_HOUR_COST').';');
                    break;
            }
        }
        return intval($budget);
    }
    
    private function getCost($folderId): int
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$wrikeUrl/api/v3/folders/${folderId}/timelogs");
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
            if (!in_array('categoryId', $timelog)) {
                break;
            }
            switch ($timelog['categoryId']){
                case getenv('WRIKE_CATEGORY_ID_CONCEPTION'):
                    $sum += $timelog['hours'] * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CATEGORY_ID_REALISATION'):
                    $sum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CATEGORY_ID_PILOTAGE'):
                    $sum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    break;
            }
        }
        
        return intval($sum);
    }
}