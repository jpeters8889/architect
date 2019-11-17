<?php

namespace JPeters\Architect\DataSources;

use ReflectionClass;

class DataSourceManager
{
    private $dataSources = [];

    public function registerDataSource($key, $dataSource) {
        $this->dataSources[$key] = $dataSource;
    }

    public function findDatasource($datasource)
    {
        if(!array_key_exists($datasource, $this->dataSources)) {
            throw new DataSourceException('Unknown data source');
        }

        $reflection = new ReflectionClass($this->dataSources[$datasource]);

        if(!$reflection->hasMethod('getArchitectDataResource')) {
            throw new DataSourceException('Datasource doesn\'t implement getArchitectDataResource method');
        }

        return $this->dataSources[$datasource];
    }
}