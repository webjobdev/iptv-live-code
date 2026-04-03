<?php 

namespace App\Support;
use Storage;
use Contus\Video\Models\Video;

class GenerateSpriteImage {

	function create_sprite($videoInfo) {
		$result = [];
		$files = array();

		$this->filetypes 	= array('jpg'=>true,'png'=>true,'jpeg'=>true,'gif'=>true);
		$this->x 			= $videoInfo['width']; // Width of images to consider
		$this->y 			= $videoInfo['height']; // Heigh of images to consider
		$this->prefix 		= $videoInfo['prefix'];
		$this->presetId 	= $videoInfo['preset_id'];


		$directory = $this->prefix.DIRECTORY_SEPARATOR.'thumbnail'.DIRECTORY_SEPARATOR.$this->presetId;
		$this->files = Storage::disk('s3')->files($directory);

		ksort($this->files);

		// yy is the height of the sprite to be created, basically X * number of images
		$this->xx = $this->x * count($this->files);

		$im = imagecreatetruecolor($this->xx,$this->y);

		// Add alpha channel to image (transparency)
		imagesavealpha($im, true);
		$alpha 	= imagecolorallocatealpha($im, 0, 0, 0, 127);
		imagefill($im,0,0,$alpha);

		// Append images to sprite and generate CSS lines
		$i = $ii = 0;
		if(!empty($this->files)) {
			
			$output = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tempimage';
			if (!is_dir($output)) {
	            mkdir($output, 0777, true);
	            chmod($output, 0777);
	        }
	        
	        $output .= DIRECTORY_SEPARATOR.'sprite';

	        if (!is_dir($output)) {
	            mkdir($output, 0777, true);
	            chmod($output, 0777);
	        }

	        foreach($this->files as $key => $file) {
				$im2 = imagecreatefrompng(env('AWS_BUCKET_URL').$file);
				imagecopy($im,$im2,($this->x*$i),0,0,0,$this->x,$this->y);
				$i++;
			}

	        $fileName = time().'-sprite.png';
	        $localPath = $output.DIRECTORY_SEPARATOR.$fileName;
			imagepng($im,$localPath,9); // Save image to file

			// TO compress the image we using pngquant software
			exec('pngquant --force --quality=40-100 --verbose '.$localPath.' --output '.$localPath);

	        $s3Path = $this->prefix.DIRECTORY_SEPARATOR.'sprite.png';
			Storage::disk('s3')->put(
	            $s3Path,
	            file_get_contents($localPath)
	        );

	        $result['s3_path'] = $s3Path;
	        $result['local_path'] = $localPath;

	        Video::where('id', $videoInfo['video_id'])->update(['sprite_image_status' => 2, 'sprite_image' => $s3Path]);

			unlink($localPath);

			if(Storage::disk('s3')->has($directory)) {
				Storage::disk('s3')->deleteDirectory($directory);
			}
		}

		return $result;
	}
}


?>