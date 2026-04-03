<?php

namespace Contus\BulkUpload\Repositories;

use Contus\Base\Repository;
use Contus\BulkUpload\Model\M3UTvShow;

class M3uTvShowRepository extends Repository
{
    protected $m3u;

    public function __construct(M3UTvShow $m3u)
    {
        parent::__construct();
        $this->m3u = $m3u;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->m3u)
            ->setEagerLoadingModels('getTvShow');
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Tv Show Image', 'value' => '', 'sort' => true],
                ['name' => 'Tv Show Name', 'value' => '', 'sort' => true],
            ]
        ];
    }
}