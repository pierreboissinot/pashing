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
        $budgetDetails = $this->getBudgetDetails($folder['customFields']);
        $costDetails = $this->getCostdetails($this->folderId);
        return json_encode([
            'title' => $folder['title'],
            'budget' => $budgetDetails['total'],
            'budgetPilotage' => $budgetDetails['pilotage'],
            'budgetConception' => $budgetDetails['conception'],
            'budgetRealisation' => $budgetDetails['realisation'],
            'reserve' => $budgetDetails['total'] - $costDetails['total'],
            'pilotage' => $costDetails['pilotage'],
            'conception' => $costDetails['conception'],
            'realisation' => $costDetails['realisation'],
            'status' => 'ok',
            'updatedAt' => time(),
        ]);
        
    }
    
    public function check()
    {
        return true;
    }
    
    private function getBudgetDetails(array $customFields): array
    {
        $budget = 0;
        $pilotageBudget = 0;
        $conceptionBudget = 0;
        $realisationBudget = 0;
        foreach ($customFields as $customField) {
            $stringValue = $customField['value'];
            // TODO: manage minutes
            $hours = (int) substr($stringValue, 0, 2);
            switch ($customField['id']){
                case getenv('WRIKE_CUSTOM_FIELD_CONCEPTION'):
                    $conceptionBudget += $hours * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_REALISATION'):
                    $realisationBudget += $hours * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_PILOTAGE'):
                    $pilotageBudget += $hours * eval('return '.getenv('PILOTAGE_HOUR_COST').';');
                    break;
                default:
                    $budget += $hours * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                    break;
            }
        }
        
        $budget += $pilotageBudget + $conceptionBudget + $realisationBudget;
        
        return [
            'total' => intval($budget),
            'pilotage' => intval($pilotageBudget),
            'conception' => intval($conceptionBudget),
            'realisation' => intval($realisationBudget),
        ];
    }
    
    private function getCostDetails($folderId): array
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
        $pilotageSum = 0;
        $conceptionSum = 0;
        $realisationSum = 0;
        foreach ($timelogs as $timelog) {
            if(isset($timelog['categoryId'])) {
                switch ($timelog['categoryId']){
                    case getenv('WRIKE_CATEGORY_ID_CONCEPTION'):
                        $conceptionSum += $timelog['hours'] * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                        break;
                    case getenv('WRIKE_CATEGORY_ID_REALISATION'):
                        $realisationSum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                        break;
                    case getenv('WRIKE_CATEGORY_ID_PILOTAGE'):
                        $pilotageSum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                        break;
                    default:
                        $sum += $timelog['hours'] * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                        break;
                }
            } else {
                $sum += $timelog['hours'] * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
            }
        }
        
        $sum += $pilotageSum + $conceptionSum + $realisationSum;
        
        return [
            'total' => intval($sum),
            'pilotage' => intval($pilotageSum),
            'conception' => intval($conceptionSum),
            'realisation' => intval($realisationSum),
        ];
    }
}