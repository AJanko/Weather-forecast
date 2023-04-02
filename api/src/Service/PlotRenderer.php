<?php

namespace App\Service;

use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Plot\LinePlot;

class PlotRenderer
{
    private string $dirPath;

    public function __construct(string $dirPath)
    {
        $this->dirPath = $dirPath;
    }

    public function drawTargetsCompare(array $targets1, array $targets2): void
    {
        $graph = new Graph(2000, 200);
        $graph->SetScale('intlin');
        $graph->title->Set('Compare targets');

        $this->addLine($graph, $targets1, 'red');
        $this->addLine($graph, $targets2, 'blue');

        $graph->Stroke($this->generateFilePath());
    }

    private function addLine(Graph $graph, array $data, string $color): void
    {
        $line = new LinePlot($data);
        $line->SetColor($color);

        $graph->Add($line);
    }

    private function generateFilePath(): string
    {
        $time = (new \DateTime())->getTimestamp();

        return "$this->dirPath/plot_date_$time.png";
    }
}
