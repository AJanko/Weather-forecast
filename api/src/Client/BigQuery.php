<?php

namespace App\Client;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\QueryResults;

class BigQuery
{
    private BigQueryClient $client;

    public function __construct(BigQueryClient $client)
    {
        $this->client = $client;
    }

    public function query(string $query): QueryResults
    {
        $queryJobConfig = $this->client->query($query);

        return $this->client->runQuery($queryJobConfig);
    }
}
