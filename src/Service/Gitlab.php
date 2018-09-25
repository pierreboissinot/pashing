<?php

namespace App\Service;

use DateInterval;
use DateTime;

class Gitlab
{
    public function getProjectMetrics(string $id)
    {
        return [
            'opened_issues_total' => $this->getOpenedIssuesTotal($id),
            'closed_issues_total' => $this->getClosedIssuesTotal($id),
            'stale_issues_total' => $this->getStaleIssuesTotal($id),
            'new_issues_total' => $this->getNewIssuesTotal($id),
        ];
    }

    public function getOpenedIssuesTotal(string $id)
    {
        $url = getenv('GITLAB_URL');
        $privateToken = getenv('GITLAB_PRIVATE_TOKEN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}/api/v4/projects/${id}/issues?private_token={$privateToken}&state=opened&scope=all&per_page=100");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        return \count(json_decode($output));
    }

    public function getClosedIssuesTotal(string $id)
    {
        $url = getenv('GITLAB_URL');
        $privateToken = getenv('GITLAB_PRIVATE_TOKEN');
        $now = new DateTime();
        $thirtyDaysAgo = $now
            ->sub(new DateInterval('P30D'))
            ->format('Y-m-dT')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}/api/v4/projects/${id}/issues?private_token={$privateToken}&state=closed&created_after={$thirtyDaysAgo}&per_page=100");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        return \count(json_decode($output));
    }

    public function getStaleIssuesTotal(string $id)
    {
        $url = getenv('GITLAB_URL');
        $privateToken = getenv('GITLAB_PRIVATE_TOKEN');
        $now = new DateTime();
        $sixtyDaysAgo = $now
            ->sub(new DateInterval('P60D'))
            ->format('Y-m-dT')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}/api/v4/projects/${id}/issues?private_token={$privateToken}&state=opened&updated_before={$sixtyDaysAgo}&per_page=100");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        return \count(json_decode($output));
    }

    public function getNewIssuesTotal(string $id)
    {
        $url = getenv('GITLAB_URL');
        $privateToken = getenv('GITLAB_PRIVATE_TOKEN');
        $now = new DateTime();
        $thirtyDaysAgo = $now
            ->sub(new DateInterval('P30D'))
            ->format('Y-m-dT')
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}/api/v4/projects/${id}/issues?private_token={$privateToken}&created_after={$thirtyDaysAgo}&per_page=100");
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        return \count(json_decode($output));
    }
}
