<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\VideoModel;
use DateTime;
use Log;

class ConvertVideo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'videosConvert';

	private $timeout = 7200;
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [];
	}

	public function handle() {
		$ffmpegPath = env('FFMPEG_PATH', '/usr/local/bin/ffmpeg');
		$ffprobePath = env('FFPROBE_PATH', '/usr/local/bin/ffprobe');

		//find video which is being converted;
		$model = VideoModel::where('status', '=', VideoModel::IN_PROCESS)->first();
		//return false if there is processing video;
		if ($model) {
			Log::error('Converter in progress.');
			echo 'Converter in progress.';
			return false;
		}
		//find new video;
		$model = VideoModel::whereRaw('( status="'.VideoModel::CONVERT_STATUS.'" OR ( status="'.VideoModel::STATUS_FAILED.'" and convertCount <= 3 ))')->first();

		if (!$model) {
			Log::error('No video found');
			echo 'No video found';
			return false;
		}
		$model->status = VideoModel::IN_PROCESS;
		if (!$model->save()) {
			return false;
		}



		// $model = VideoModel::whereRaw('( status="'.VideoModel::CONVERT_STATUS.'" OR status="'.VideoModel::STATUS_FAILED.'" )')->first();

		// if(!empty($model)){
		// 	$model->status = VideoModel::IN_PROCESS;
		// 	$model->save();
		// }
		// if (!$model) {
		// 	echo 'No video found';
		// 	return false;
		// }

		//Get Path of video
		$get_path_date= date_format(new DateTime($model->created_at), 'Y-m-d');

		$videoInput = base_path() . DIRECTORY_SEPARATOR . 'videos'.DIRECTORY_SEPARATOR.$model->string_Id.'.'.$model->extension;
		$videoOutput = base_path() . DIRECTORY_SEPARATOR . 'videos'.DIRECTORY_SEPARATOR.'_'.$get_path_date.'-'.$model->string_Id.'.mp4';
		$videoSD = base_path() . DIRECTORY_SEPARATOR . 'videos'.DIRECTORY_SEPARATOR.'_'.$get_path_date.'-'.$model->string_Id.'-SD.mp4';
		$thumbnailPath = base_path() . DIRECTORY_SEPARATOR . 'videos'.DIRECTORY_SEPARATOR.'_'.$get_path_date.'-'.$model->string_Id.'.jpg';
		$previewPath = base_path() . DIRECTORY_SEPARATOR . 'videos'.DIRECTORY_SEPARATOR.'_'.$get_path_date.'-'.$model->string_Id.'-preview.jpg';

		try {
			// Check extension
			if($model->extension ==='mp4'){

				// $command = '/usr/local/bin/ffmpeg -y -i ' . $videoInput . ' -s 480x360  ' . $videoSD; //Convert to SD video
				$command = 'cp ' . $videoInput . ' ' . $videoSD; //Convert to SD video
				$command.= ' &&  '.$ffmpegPath.' -y -i ' . $videoInput . ' -deinterlace -an -ss 5 -f mjpeg -t 1 -r 1 -y -s 640x360 '.$thumbnailPath.' 2>&1'; // create thumbnail
				$process = new Process($command); // start process
				$process->setTimeout(7200);
				$process->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});

				$durationCommand = $ffprobePath. " -v quiet -of csv=p=0 -show_entries format=duration ".$videoInput ; // get video duration
				$processDuration = new Process($durationCommand); // start  process
				$processDuration->setTimeout(7200);
				$processDuration->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});
				if ($processDuration->isSuccessful()) {
					$model->duration = $processDuration->getOutput(); // add out put duration to model field
				}

				$getFrame = $ffprobePath." -i ".$videoInput." -show_frames 2>&1 | grep -c media_type=video";
				$processFrame = new Process($getFrame);
				$processFrame->setTimeout(7200);
				$processFrame->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});

				if ($processFrame->isSuccessful()) {
					$frame = $processFrame->getOutput()/100;
					$imagePreview = $ffmpegPath.' -y -i '.$videoInput.' -frames 1 -q:v 1 -vf "select=not(mod(n\,'.intval($frame).')),scale=-1:145,tile=12x1" '.$previewPath;
					$processPreview = new Process($imagePreview);
					$processPreview->setTimeout(7200);
					$processPreview->run();
					if($processPreview->isSuccessful()) {
					   $model->preview = asset('videos/_'.$get_path_date.'-'.$model->string_Id.'-preview.jpg');
					}
				}

				if ($process->isSuccessful()) {
					//change status and path if video convert successful.
					$model->video_src = asset('videos'. '/'.$model->string_Id.'.mp4');
					$model->video_sd = asset('videos'. '/_' .$get_path_date.'-'.$model->string_Id.'-SD.mp4');
					$model->poster = asset('videos'. '/_' .$get_path_date.'-'.$model->string_Id.'.jpg');
					$model->uploadName = json_encode(['SD' => '_'.$get_path_date.'-'.$model->string_Id.'-SD.mp4', 'HD' => $model->string_Id.'.mp4']);
					if ( $model->website == "upload" && !is_null($model->user_id) )
						$model->status = VideoModel::BLOCKED;
					else
						$model->status = VideoModel::STATUS_COMPLETED;
					if ($model->save()) {
						// @unlink($videoTmpPath);
						return true;
					}
				} else {
					Log::error('ERR: ' . $process->getOutput());
					echo $process->getOutput();
					throw new ProcessFailedException($process);
				}

			} else {
				$conver_in_array = $ffmpegPath. ' -y -i '.$videoInput.' -c:v libx264 '.$videoOutput;
				$processConvert = new Process($conver_in_array);
				$processConvert->setTimeout(7200);
				$processConvert->run();
				if ($processConvert->isSuccessful()) {
					// $addvideo->video_src = "".URL('/videos/')."/".$filenameOut."";
				}
				$videoInput = $videoOutput;

				$command = $ffmpegPath. ' -y -i ' . $videoInput . ' -s 480X360  ' . $videoSD;
				$command.= ' && '.$ffmpegPath.' -y -i ' . $videoInput . ' -deinterlace -an -ss 5 -f mjpeg -t 1 -r 1 -y -s 640x360 '.$thumbnailPath.' 2>&1';
				$process = new Process($command);
				$process->setTimeout(7200);
				$process->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});

				$durationCommand = $ffprobePath." -v quiet -of csv=p=0 -show_entries format=duration ".$videoInput ;
				$processDuration = new Process($durationCommand);
				$processDuration->setTimeout(7200);
				$processDuration->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});
				if ($processDuration->isSuccessful()) {
					$model->duration = $processDuration->getOutput();
				}

				$getFrame = $ffprobePath." -i ".$videoInput." -show_frames 2>&1 | grep -c media_type=video";
				$processFrame = new Process($getFrame);
				$processFrame->setTimeout(7200);
				$processFrame->run(function ($type, $buffer) {
					if (Process::ERR === $type) {
						Log::error('ERR > ' . $buffer);
						echo 'ERR > ' . $buffer;
					} else {
						echo 'OUT > ' . $buffer;
					}
				});

				if ($processFrame->isSuccessful()) {
					$frame = $processFrame->getOutput()/100;
					$imagePreview = $ffmpegPath. ' -y -i '.$videoInput.' -frames 1 -q:v 1 -vf "select=not(mod(n\,'.intval($frame).')),scale=-1:145,tile=12x1" '.$previewPath;
					$processPreview = new Process($imagePreview);
					$processPreview->setTimeout(7200);
					$processPreview->run();
					if($processPreview->isSuccessful()){
					   $model->preview = asset('videos/_'.$get_path_date.'-'.$model->string_Id.'-preview.jpg');
					}
				}

				if ($process->isSuccessful()) {
					//change status and path if video convert successful.
					$model->video_src = asset('videos'. '/_' .$get_path_date.'-'.$model->string_Id.'.mp4');
					$model->video_sd = asset('videos'. '/_' .$get_path_date.'-'.$model->string_Id.'-SD.mp4');
					$model->poster = asset('videos'. '/_' .$get_path_date.'-'.$model->string_Id.'.jpg');
					$model->uploadName = json_encode(['SD' => '_'.$get_path_date.'-'.$model->string_Id.'-SD.mp4', 'HD' => '_'.$get_path_date.'-'.$model->string_Id.'.mp4']);
					if ( $model->website == "upload" && !is_null($model->user_id) )
						$model->status = VideoModel::BLOCKED;
					else
						$model->status = VideoModel::STATUS_COMPLETED;
					if ($model->save()) {
						@unlink($videoInput);
						return true;
					}
				} else {
					Log::error('ERR: ' . $process->getOutput());
					echo $process->getOutput();
					throw new ProcessFailedException($process);
				}
			}
		} catch(ProcessFailedException $e) {

			Log::error('ERR: ' . $e);

			//change status if video convert fail.
			$model->status = VideoModel::STATUS_FAILED;
			$curentFailed = $model->convertCount;
			$model->convertCount =  $curentFailed + 1;
			$model->save();
			return false;
		}
	}


}
