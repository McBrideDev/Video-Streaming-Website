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

class ChangeDomain extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'changeDomain';

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
		$searchText = 'sexwithmaryjane.com';
		$rightDomain = 'blackxxx.me';

		$columNeedCheck = ['video_src', 'video_sd', 'poster', 'preview'];
		foreach ($columNeedCheck as $col) {
			$videos = VideoModel::where($col, 'like', '%'.$searchText.'%')->get();
			foreach ($videos as $video) {
				$video[$col] = str_replace($searchText, $rightDomain, $video[$col]);
				$video->save();
			}
		}
	}


}
