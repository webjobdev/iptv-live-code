<?php

namespace Contus\BulkUpload\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Channel\Model\Channel;
use Contus\Channel\Model\ChannelOrganization;
use Contus\Organizations\Model\Organization;
use Contus\BulkUpload\Model\M3UChannel;
use Contus\BulkUpload\Model\M3UVod;
use Contus\Video\Models\TvCategory;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class m3uVodRepository extends Repository
{
    protected $m3u;
    protected $channel;

    public function __construct(M3UVod $m3u, VideoOnDemad $channel)
    {
        parent::__construct();
        $this->m3u = $m3u;
        $this->channel = $channel;
    }

    public function getInfo()
    {
        return $this->successResponse('M3U Channel Info', []);
    }
    public function prepareGrid()
    {
        $this->setGridModel($this->m3u)
            ->setEagerLoadingModels('getVod');
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Vod Image', 'value' => '', 'sort' => true],
                ['name' => 'Vod Name', 'value' => '', 'sort' => true],
                // ['name' => 'Vod Epg Id', 'value' => '', 'sort' => true],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}
