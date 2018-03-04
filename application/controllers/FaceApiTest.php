<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once (APPPATH.'libraries/FaceApiLib/FaceApiLib.php');

/**
 * Class FaceApiTest
 */
class FaceApiTest extends CI_Controller {
	public function index()
	{
		try {
			$api_name = 'detect';
			$image_url = '';

			/* @var FaceApiLib $face_api */
			$face_api = new FaceApiLib();

			$result = $face_api->send_face_api($api_name, $image_url);
			var_dump($result);

			$api_name = 'tag';
			$image_url = '';
			$result = $face_api->send_vision_api($api_name, $image_url);
			var_dump($result);

		}
		catch (HttpException $ex)
		{
			echo $ex;
		}
	}
}
