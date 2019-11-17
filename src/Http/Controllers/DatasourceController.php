<?php

namespace JPeters\Architect\Http\Controllers;

use JPeters\Architect\DataSources\DataSourceException;
use JPeters\Architect\Http\Requests\DatasourceRequest;

class DatasourceController extends BaseController
{
    public function list(DatasourceRequest $request)
    {
        try {
            $datasource = $this->architect->dataSourceManager->findDatasource($request->input('dataSource'));

            return $datasource::getArchitectDataResource($request->get('searchTerm'));
        }
        catch (DataSourceException $e) {
            abort(422, $e->getMessage());
        }
    }
}