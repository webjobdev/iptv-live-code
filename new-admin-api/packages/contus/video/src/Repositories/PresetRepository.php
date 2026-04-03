<?php

/**
 * Preset Repository
 *
 * To manage the functionalities related to Presets
 * @name       PresetRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IPresetRepository;
use Contus\Video\Models\VideoPreset;
use Contus\Base\Helpers\StringLiterals;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class PresetRepository extends BaseRepository implements IPresetRepository {
	public $videoPreset;
	/**
	 * Class property to hold AWS instance.
	 *
	 * @var \Aws\ElasticTranscoder\ElasticTranscoderClient
	 */
	public $awsClient;
	public $allowedPresetFormat;
	/**
	 * Construct method initialization
	 *
	 * Validation rule for user verification code and forgot password.
	 */
	public function __construct() {
		parent::__construct();
		$this->videoPreset = new VideoPreset;
		$this->allowedPresetFormat = 'ts';
	}
	/**
	 * Prepare the grid
	 * set the grid model and relation model to be loaded
	 *
	 * @vendor Contus
	 *
	 * @package Collection
	 * @return Contus\Collection\Repositories\Repository
	 */
	public function prepareGrid() {
		$this->setGridModel($this->videoPreset);
		return $this;
	}

	/**
	 * Get headings for grid
	 *
	 * @vendor Contus
	 *
	 * @package Collection
	 * @return array
	 */
	public function getGridHeadings() {
		return [
			StringLiterals::GRIDHEADING => [
				['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
				[
					'name' => trans('video::presets.preset_name'),
					StringLiterals::VALUE => 'name',
					'sort' => true,
					'class' => false,
				],
				[
					'name' => trans('video::presets.aws_identifier'),
					StringLiterals::VALUE => 'aws_id',
					'sort' => false,
					'class' => true,
				],
				[
					'name' => trans('video::presets.format'),
					StringLiterals::VALUE => 'format',
					'sort' => false,
					'class' => true,
				],
				[
					'name' => trans('video::presets.status'),
					StringLiterals::VALUE => 'is_active',
					'sort' => false,
					'class' => false,
				],
				[
					'name' => trans('video::presets.preset_max_height'),
					StringLiterals::VALUE => 'preset_max_height',
					'sort' => false,
					'class' => true,
				],

			]
		];
	}

	/**
	 * apply Search filter
	 *
	 * @param mixed $builder
	 * @return \Illuminate\Database\Eloquent\Builder $builder
	 */
	protected function searchFilter($builder) {
		$searchRecord = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
		$is_active = $name = $aws_id = $format = null;
		extract($searchRecord);

		if ($name) {
			$builder = $builder->where('name', 'like', '%' . $name . '%');
		}
		if ($aws_id) {
			$builder = $builder->where('aws_id', 'like', '%' . $aws_id . '%');
		}
		if ($format) {
			$builder = $builder->where('format', 'like', '%' . $format . '%');
		}
		if (is_numeric($is_active)) {
			$builder = $builder->where(StringLiterals::ISACTIVE, $is_active);
		}

		return $builder;
	}
	/**
	 * update grid records collection query
	 *
	 * @param mixed $builder
	 * @return mixed
	 */
	// protected function updateGridQuery($builder) {
	// 	return $builder->where('format',$this->allowedPresetFormat)->orderBy('is_active',1)->orderBy('id', 'desc');
	// }
	protected function updateGridQuery($builder) {
		return $builder->where('format', $this->allowedPresetFormat)
			->orderBy('is_active', 'desc')
			->orderBy('id', 'desc');
	}
	/**
	 * Function to get number of active presets.
	 *
	 * @return integer Number o active presets in the database.
	 */
	public function getNumberOfActivePresets() {
		return $this->videoPreset->where('is_active', 1)->count();
	}
	/**
	 * Method to get presets from AWS cloud.
	 * 
	 * @return boolean
	 */
	public function getPresetsFromCloud() {
		$credentials = array(
			'region' => env('AWS_REGION'),
			'version' => 'latest',
			'credentials' => [
				'key' => env('AWS_KEY'),
				'secret' => env('AWS_SECRET')
			]
		);
		$this->awsClient = ElasticTranscoderClient::factory($credentials);
		$this->getAllPresets();
		return true;
	}
	/**
	 * Function to get all the presets from AWS Elastic transcoder.
	 *
	 * @param string $nextPageToken
	 * Optional parameter which is used as a token reference to fetch next set of presets.
	 */
	public function getAllPresets($nextPageToken = '') {
		$client = $this->awsClient;

		if (empty($nextPageToken)) {
			$result = $client->listPresets();
		} else {
			$result = $client->listPresets(array('PageToken' => $nextPageToken));
		}
		$this->savePresets($result['Presets']);
		if (! empty($result['NextPageToken'])) {
			/**
			 * Call the current function recursively.
			 */
			$this->getAllPresets($result['NextPageToken']);
		}
	}
	/**
	 * Function to save presets into the database.
	 * This function checks where a preset is already available in the database.
	 * If yes, then the preset details are updated and the preset details are inserted if not.
	 *
	 * @param array $presets
	 * The presets returned by AWS SDK.
	 */
	public function savePresets($presets) {
		foreach ($presets as $preset) {
			/**
			 * Check if the current preset is a video preset or not.
			 * If it is not a video preset then skip it and save only the video presets in the database.
			 */
			if (empty($preset['Video'])) {
				continue;
			}
			/**
			 * Check and insert or update in database.
			 */
			$existingPresetId = $this->videoPreset->where('aws_id', $preset['Id'])->value('id');
			if ($existingPresetId) {
				/**
				 * Preset already avaliable.
				 * So update the database.
				 */
				$presetInstance = $this->videoPreset->findOrFail($existingPresetId);
			} else {
				$presetInstance = new VideoPreset();
			}

			$presetInstance->name = $preset['Name'];
			$presetInstance->description = !empty($preset['Description']) ? $preset['Description'] : '';
			$presetInstance->aws_id = $preset['Id'];
			$presetInstance->format = $preset['Container'];
			$presetInstance->thumbnail_format = $preset['Thumbnails']['Format'];
			$presetInstance->preset_max_height = !empty($preset['Video']['MaxHeight']) ? $preset['Video']['MaxHeight'] : 0;
			$presetInstance->save();
		}
	}

	/**
	 * Function to activate the Preset
	 *
	 * @param integer|array $ids
	 * The ids of the Preset which are to be activated.
	 * @return boolean True if the Preset are archived successfully and false if not.
	 */
	public function categoryActivateOrDeactivate($ids, $isStatus) {
		/**
		 * Delete the Preset by the given id
		 */
		$ids = is_array($ids) ? $ids : [$ids];
		/**
		 * Check if the status is activate.
		 * If yes, set is_active field to 1.
		 * If no, then set is_active field to 0.
		 */
		if ($isStatus == 'activate') {
			$status = empty($ids) ? StringLiterals::LITERALFALSE : $this->videoPreset->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);

			return $status;
		} else if ($isStatus == 'deactivate') {
			$status = empty($ids) ? StringLiterals::LITERALFALSE : $this->videoPreset->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);

			return $status;
		}
	}
}
