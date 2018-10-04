<?php

namespace App\Controller;

use App\Service\Gitlab;
use App\Service\Wrike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrometheusController extends AbstractController
{
    const APPLICATION_PREFIX = 'pashing_';
    /**
     * @var Wrike
     */
    private $wrike;
    /**
     * @var Gitlab
     */
    private $gitlab;

    public function __construct(Wrike $wrike, Gitlab $gitlab)
    {
        $this->wrike = $wrike;
        $this->gitlab = $gitlab;
    }

    /**
     * @Route("/metrics")
     */
    public function metrics(): Response
    {
        $counter = '';

        // Wrike metrics

        $folders = $this->wrike->getProjects();
        foreach ($folders as $folder) {
            $metrics = json_decode($this->wrike->getFolderMetrics($folder), true);
            $label = $metrics['title'];

            $metricName = self::APPLICATION_PREFIX."{$folder}_budget_euros_total";
            $counter .= "# HELP {$metricName} The total budget.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['budget']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_budget_pilotage_euros";
            $counter .= "# HELP {$metricName} The total budget for pilotage.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['budgetPilotage']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_budget_conception_euros";
            $counter .= "# HELP {$metricName} The total budget for conception.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['budgetConception']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_budget_realisation_euros";
            $counter .= "# HELP {$metricName} The total budget for realisation.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['budgetRealisation']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_reserve_euros_total";
            $counter .= "# HELP {$metricName} The money left for the inital budget.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['reserve']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_pilotage_euros_total";
            $counter .= "# HELP {$metricName} The total amount for time spent with category pilotage.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['pilotage']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_conception_euros_total";
            $counter .= "# HELP {$metricName} The total amount for time spent with category conception.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['conception']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_realisation_euros_total";
            $counter .= "# HELP {$metricName} The total amount for time spent with category realisation.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['realisation']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_sold_time_hours";
            $counter .= "# HELP {$metricName} The number of hours sold.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['totalHoursSold']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_pilotage_sold_time_hours";
            $counter .= "# HELP {$metricName} The number of pilotage hours sold.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['pilotageHoursSold']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_conception_sold_time_hours";
            $counter .= "# HELP {$metricName} The number of conception hours sold.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['conceptionHoursSold']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_realisation_sold_time_hours";
            $counter .= "# HELP {$metricName} The number of realisation hours sold.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['realisationHoursSold']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_time_spent_hours_total";
            $counter .= "# HELP {$metricName} The total number of hours spent.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['hoursSpent']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_pilotage_time_spent_hours";
            $counter .= "# HELP {$metricName} The total number of hours spent with category pilotage.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['pilotageHoursSpent']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_conception_time_spent_hours";
            $counter .= "# HELP {$metricName} The total number of hours spent with category conception.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['conceptionHoursSpent']}\n";

            $metricName = self::APPLICATION_PREFIX."{$folder}_realisation_time_spent_hours";
            $counter .= "# HELP {$metricName} The total number of hours spent with category realisation.\n";
            $counter .= "# TYPE {$metricName} gauge\n";
            $counter .= "{$metricName}{folder=\"{$label}\"} {$metrics['realisationHoursSpent']}\n";
        }

        // Gitlab metrics

        $gitlabProjectId = getenv('GITLAB_PROJECT_ID');
        $gitlabMetrics = $this->gitlab->getProjectMetrics($gitlabProjectId);

        $metricName = self::APPLICATION_PREFIX."{$gitlabProjectId}_opened_issues_total";
        $counter .= "# HELP {$metricName} The total number of opened issues.\n";
        $counter .= "# TYPE {$metricName} gauge\n";
        $counter .= "{$metricName}{projectId=\"{$gitlabProjectId}\",label=\"opened issues\"} {$gitlabMetrics['opened_issues_total']}\n";

        $metricName = self::APPLICATION_PREFIX."{$gitlabProjectId}_closed_issues_total";
        $counter .= "# HELP {$metricName} The total number of closed issues.\n";
        $counter .= "# TYPE {$metricName} gauge\n";
        $counter .= "{$metricName}{projectId=\"{$gitlabProjectId}\",label=\"closed issues last 30 days\"} {$gitlabMetrics['closed_issues_total']}\n";

        $metricName = self::APPLICATION_PREFIX."{$gitlabProjectId}_stale_issues_total";
        $counter .= "# HELP {$metricName} The total number of stale issues.\n";
        $counter .= "# TYPE {$metricName} gauge\n";
        $counter .= "{$metricName}{projectId=\"{$gitlabProjectId}\",label=\"stale issues last 60 days\"} {$gitlabMetrics['stale_issues_total']}\n";

        $metricName = self::APPLICATION_PREFIX."{$gitlabProjectId}_new_issues_total";
        $counter .= "# HELP {$metricName} The total number of new issues.\n";
        $counter .= "# TYPE {$metricName} gauge\n";
        $counter .= "{$metricName}{projectId=\"{$gitlabProjectId}\",label=\"new issues last 30 days\"} {$gitlabMetrics['new_issues_total']}\n";

        $metricName = self::APPLICATION_PREFIX."{$gitlabProjectId}_time_spent_hours";
        $counter .= "# HELP {$metricName} The total number of time spent in hours.\n";
        $counter .= "# TYPE {$metricName} gauge\n";
        $counter .= "{$metricName}{projectId=\"{$gitlabProjectId}\",label=\"time spent\"} {$this->wrike->getTimeSpentTotal(getenv('WRIKE_CPS_FOLDER_ID'))}\n";

        return new Response($counter, RESPONSE::HTTP_OK, [
            'Content-type: '.'text/plain; version=0.0.4',
        ]);
    }
}
