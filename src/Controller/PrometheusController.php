<?php

namespace App\Controller;

use App\Service\Wrike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrometheusController extends AbstractController
{
    /**
     * @var Wrike
     */
    private $wrike;

    public function __construct(Wrike $wrike)
    {
        $this->wrike = $wrike;
    }

    /**
     * @Route("/metrics")
     */
    public function metrics(): Response
    {
        $counter = '';
        $folders = $this->wrike->getProjects();
        foreach ($folders as $folder) {
            $metrics = json_decode($this->wrike->getFolderMetrics($folder), true);
            $label = $metrics['title'];
            $counter .= "{$folder}_budget{folder=\"{$label}\"} {$metrics['budget']}\n";
            $counter .= "{$folder}_budget_pilotage{folder=\"{$label}\"} {$metrics['budgetPilotage']}\n";
            $counter .= "{$folder}_budget_conception{folder=\"{$label}\"} {$metrics['budgetConception']}\n";
            $counter .= "{$folder}_budget_realisation{folder=\"{$label}\"} {$metrics['budgetRealisation']}\n";
            $counter .= "{$folder}_reserve{folder=\"{$label}\"} {$metrics['reserve']}\n";
            $counter .= "{$folder}_pilotage{folder=\"{$label}\"} {$metrics['pilotage']}\n";
            $counter .= "{$folder}_conception{folder=\"{$label}\"} {$metrics['conception']}\n";
            $counter .= "{$folder}_realisation{folder=\"{$label}\"} {$metrics['realisation']}\n";
            $counter .= "{$folder}_total_hours_sold{folder=\"{$label}\"} {$metrics['totalHoursSold']}\n";
            $counter .= "{$folder}_pilotage_hours_sold{folder=\"{$label}\"} {$metrics['pilotageHoursSold']}\n";
            $counter .= "{$folder}_conception_hours_sold{folder=\"{$label}\"} {$metrics['conceptionHoursSold']}\n";
            $counter .= "{$folder}_realisation_hours_sold{folder=\"{$label}\"} {$metrics['realisationHoursSold']}\n";
            $counter .= "{$folder}_hours_spent{folder=\"{$label}\"} {$metrics['hoursSpent']}\n";
            $counter .= "{$folder}_pilotage_hours_spent{folder=\"{$label}\"} {$metrics['pilotageHoursSpent']}\n";
            $counter .= "{$folder}_conception_hours_sold{folder=\"{$label}\"} {$metrics['conceptionHoursSold']}\n";
            $counter .= "{$folder}_realisation_hours_sold{folder=\"{$label}\"} {$metrics['realisationHoursSold']}\n";
        }

        return new Response($counter, RESPONSE::HTTP_OK, [
            'Content-type: '.'text/plain; version=0.0.4',
        ]);
    }
}
