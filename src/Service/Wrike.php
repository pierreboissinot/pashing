<?php

namespace App\Service;

use DateInterval;
use DateTime;

class Wrike
{
    public function getFolderMetrics(string $id)
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $token = getenv('WRIKE_PERMANENT_TOKEN');

        $ch = curl_init("$wrikeUrl/api/v3/folders/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            'Content-Type: application/json',
        ]);
        $output = curl_exec($ch);
        curl_close($ch);
        $folder = json_decode($output, true)['data'][0]; // get the first folder in response
        $budgetDetails = $this->getBudgetDetails(null === $folder['customFields'] ? [] : $folder['customFields']);
        $costDetails = $this->getCostdetails($id);

        return json_encode([
            'id' => $id,
            'title' => $folder['title'],
            'budget' => $budgetDetails['total'],
            'budgetPilotage' => $budgetDetails['pilotage'],
            'budgetConception' => $budgetDetails['conception'],
            'budgetRealisation' => $budgetDetails['realisation'],
            'reserve' => $budgetDetails['total'] - $costDetails['total'],
            'pilotage' => $costDetails['pilotage'],
            'conception' => $costDetails['conception'],
            'realisation' => $costDetails['realisation'],
            'totalHoursSold' => $budgetDetails['totalHoursSold'],
            'pilotageHoursSold' => $budgetDetails['pilotageHoursSold'],
            'conceptionHoursSold' => $budgetDetails['conceptionHoursSold'],
            'realisationHoursSold' => $budgetDetails['realisationHoursSold'],
            'hoursSpent' => $costDetails['hoursSpent'],
            'pilotageHoursSpent' => $costDetails['pilotageHoursSpent'],
            'conceptionHoursSpent' => $costDetails['conceptionHoursSpent'],
            'realisationHoursSpent' => $costDetails['realisationHoursSpent'],
            'status' => 'ok',
            'updatedAt' => time(),
        ]);
    }

    public function getProjects()
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $folderId = getenv('WRIKE_PROJECTS_FOLDER_ID');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "${wrikeUrl}/api/v3/folders/${folderId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            'Content-Type: application/json',
        ]);
        $output = curl_exec($ch);
        $projects = json_decode($output, true)['data'][0]['childIds'];
        curl_close($ch);

        return $projects;
    }

    private function getBudgetDetails(array $customFields): array
    {
        $budget = 0;
        $pilotageBudget = 0;
        $conceptionBudget = 0;
        $realisationBudget = 0;
        $totalHours = 0;
        $pilotageHoursSold = 0;
        $conceptionHoursSold = 0;
        $realisationHoursSold = 0;
        foreach ($customFields as $customField) {
            $stringValue = $customField['value'];
            if (empty($stringValue) || !\in_array($customField['id'], [
                    getenv('WRIKE_CUSTOM_FIELD_CONCEPTION'),
                    getenv('WRIKE_CUSTOM_FIELD_REALISATION'),
                    getenv('WRIKE_CUSTOM_FIELD_PILOTAGE'),
                ], true)
            ) {
                continue;
            }
            $timeNumbers = explode(':', $stringValue);
            $hours = eval("return {$timeNumbers[0]}.{$timeNumbers[1]};");
            switch ($customField['id']) {
                case getenv('WRIKE_CUSTOM_FIELD_CONCEPTION'):
                    $conceptionBudget = $hours * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                    $conceptionHoursSold = $hours;
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_REALISATION'):
                    $realisationBudget = $hours * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    $realisationHoursSold = $hours;
                    break;
                case getenv('WRIKE_CUSTOM_FIELD_PILOTAGE'):
                    $pilotageBudget = $hours * eval('return '.getenv('PILOTAGE_HOUR_COST').';');
                    $pilotageHoursSold = $hours;
                    break;
                default:
                    break;
            }
        }

        $budget += $pilotageBudget + $conceptionBudget + $realisationBudget;
        $totalHours += $pilotageHoursSold + $conceptionHoursSold + $realisationHoursSold;

        return [
            'total' => (int) $budget,
            'pilotage' => (int) $pilotageBudget,
            'conception' => (int) $conceptionBudget,
            'realisation' => (int) $realisationBudget,
            'totalHoursSold' => $totalHours,
            'pilotageHoursSold' => $pilotageHoursSold,
            'conceptionHoursSold' => $conceptionHoursSold,
            'realisationHoursSold' => $realisationHoursSold,
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            'Content-Type: application/json',
        ]);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        $timelogs = json_decode($output, true)['data'];
        $sum = 0;
        $pilotageSum = 0;
        $conceptionSum = 0;
        $realisationSum = 0;
        $hoursSpent = 0;
        $pilotageHoursSpent = 0;
        $conceptionHoursSpent = 0;
        $realisationHoursSpent = 0;
        if (null !== $timelogs) {
            foreach ($timelogs as $timelog) {
                if (isset($timelog['categoryId'])) {
                    switch ($timelog['categoryId']) {
                        case getenv('WRIKE_CATEGORY_ID_CONCEPTION'):
                            $conceptionSum += $timelog['hours'] * eval('return '.getenv('CONCEPTION_HOUR_COST').';');
                            $conceptionHoursSpent += $timelog['hours'];
                            break;
                        case getenv('WRIKE_CATEGORY_ID_REALISATION'):
                            $realisationSum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                            $realisationHoursSpent += $timelog['hours'];
                            break;
                        case getenv('WRIKE_CATEGORY_ID_PILOTAGE'):
                            $pilotageSum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                            $pilotageHoursSpent += $timelog['hours'];
                            break;
                        default:
                            $sum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                            $hoursSpent += $timelog['hours'];
                            break;
                    }
                } else {
                    $sum += $timelog['hours'] * eval('return '.getenv('REALISATION_HOUR_COST').';');
                    $hoursSpent += $timelog['hours'];
                }
            }
        }

        $sum += $pilotageSum + $conceptionSum + $realisationSum;
        $hoursSpent += $pilotageHoursSpent + $conceptionHoursSpent + $realisationHoursSpent;

        return [
            'total' => $sum,
            'pilotage' => $pilotageSum,
            'conception' => $conceptionSum,
            'realisation' => $realisationSum,
            'hoursSpent' => $hoursSpent,
            'pilotageHoursSpent' => $pilotageHoursSpent,
            'conceptionHoursSpent' => $conceptionHoursSpent,
            'realisationHoursSpent' => $realisationHoursSpent,
        ];
    }

    public function getTimeSpentTotalLastThirtyDays(string $id)
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $now = new DateTime();
        $thirtyDaysAgo = $now
            ->sub(new DateInterval('P30D'))
            ->format('Y-m-d\TH:i:s\Z')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "${wrikeUrl}/api/v3/folders/${id}/timelogs?createdDate={\"start\":\"${thirtyDaysAgo}\"}");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            'Content-Type: application/json',
        ]);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        $timelogs = json_decode($output, true)['data'];
        $sum = 0;
        foreach ($timelogs as $timelog) {
            $sum += $timelog['hours'];
        }

        return $sum;
    }

    public function getTimeSpentTotal(string $id)
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "${wrikeUrl}/api/v3/folders/${id}/timelogs");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            'Content-Type: application/json',
        ]);
        $output = curl_exec($ch);
        curl_close($ch);
        $timelogs = json_decode($output, true)['data'];
        $sum = 0;
        foreach ($timelogs as $timelog) {
            $sum += $timelog['hours'];
        }

        return $sum;
    }
}
