<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 26.07.18
 * Time: 9:00
 */

namespace App\Http\TicketBags;

use Illuminate\Support\Facades\Log;

trait GetStatistics
{
	public $result_arr = [];
	private $compare_string = '';

	/**
	 * Check existing file
	 *
	 * @param $file_name
	 * @return bool
	 */
	private function fileExist(string &$file_name)
	{
		$ch = curl_init($file_name);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$http_header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// $http >= 400 -> not found, $http = 200, found.
		curl_close($ch);
		switch ($http_header) {
			case 200:
				return True;
			default:
				return False;
		}
	}

	/**
	 * Get file context
	 *
	 * @param $file_name
	 * @return array
	 */
	private function getFile(&$file_name)
	{
		$file = fopen($file_name, 'r');
		$result_arr = [];
		foreach ($this->getLine($file) as $val) {
			if ($this->getCompareStr() !== $val)
				$result_arr[] = $this->unsetTempArr($val);
			$this->setCompareStr($val);
		}
		fclose($file);
		return $result_arr;
	}

	/**
	 * Get line from file
	 * @param $file
	 * @return \Generator
	 */
	private function getLine(&$file)
	{
		while (!feof($file)) {
			$line = fgets($file);
			yield $line;
		}
	}

	/**
	 * Get array from string
	 * unset useless variables, replace double quotes
	 *
	 * @param $str
	 * @return array
	 */
	private function unsetTempArr(string $str)
	{
		$temp_arr = explode(';', str_replace('"', '', $str));
//		unset($temp_arr[1],$temp_arr[2]);
		return $this->addKeys2Arr($temp_arr);
	}

	/**
	 * add specific keys into array
	 * @param array $temp_arr
	 * @return array
	 */
	private function addKeys2Arr(array $temp_arr)
	{
		$res_arr = [];
		foreach ($temp_arr as $key => $val) {
			switch ($key) {
				case 0:
					$res_arr['lastreply'] = $val;
					break;
				case 1:
					$res_arr['time_uses'] = $val;
					break;
				case 3:
					$res_arr['subject'] = trim($val);
					break;
				case 4:
					$res_arr['admin'] = $val;
					break;
				case 5:
					$res_arr['ticketid'] = $val;
					break;
			}
		}
		return $res_arr;
	}

	/**
	 * Storing comparing string
	 * @param $str
	 */
	private function setCompareStr(string $str): void
	{
		$this->compare_string = $str;
	}

	/**
	 * Get stored comparing string
	 * @return string
	 */
	private function getCompareStr()
	{
		return $this->compare_string;
	}

	/**
	 * @param string $service
	 * @param string $file_name
	 * @return array
	 */
	public function getStatistic(string &$service,string &$file_name)
	{
			if ($this->fileExist($file_name)) {
				$result_arr = $this->getFile($file_name);
			}
			else{
				Log::error(sprintf('File %s$2 on service %s1$ not found',$service,$file_name));
				$result_arr=[];
			}
		return $result_arr;
	}
}