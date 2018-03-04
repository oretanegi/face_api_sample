<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)
require_once ('HTTP/Request2.php');

/**
 * Class FaceApiLib
 */
class FaceApiLib
{
    /**
     * FaceApiLib constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $api_end_point_type
     * @return array
     */
    protected function get_config($api_end_point_type)
    {
        $face_api_config['face'] = [
            'end_point' => 'https://westcentralus.api.cognitive.microsoft.com/face/v1.0/',
            'Ocp-Apim-Subscription-Key' => ''
        ];
        $face_api_config['vision'] = [
            'end_point' => 'https://westcentralus.api.cognitive.microsoft.com/vision/v1.0/',
            'Ocp-Apim-Subscription-Key' => ''
        ];

        $config = [];
        if ($api_end_point_type === 'face') {
            $config = $face_api_config['face'];
        } elseif ($api_end_point_type === 'vision') {
            $config = $face_api_config['vision'];
        }
        return $config;
    }

    /**
     * @param $api_name
     * @param $image_url
     * @return array
     */
    public function send_face_api($api_name, $image_url)
    {
        $config = $this->get_config('face');
        $config['api_url'] = $config['end_point'].$api_name;
        $config['image_url'] = $image_url;
        $result = $this->send($config);
        return $result;
    }

    /**
     * @param $api_name
     * @param $image_url
     * @return string
     */
    public function send_vision_api($api_name, $image_url)
    {
        $config = $this->get_config('vision');
        $config['api_url'] = $config['end_point'].$api_name;
        $config['image_url'] = $image_url;
        $result = $this->send($config);
        return $result;
    }

    /**
     * @param $params
     * @return string
     */
    protected function send($params)
    {
        try {
            $request = new Http_Request2($params['api_url']);
            $url = $request->getUrl();

            $headers = array(
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $params['Ocp-Apim-Subscription-Key'],
            );

            $request->setHeader($headers);

            $parameters = array(
                // Request parameters
                'returnFaceId' => 'true',
                'returnFaceLandmarks' => 'false',
                'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,emotion,hair,makeup,occlusion,accessories,blur,exposure,noise'
            );

            $url->setQueryVariables($parameters);
            $request->setMethod(HTTP_Request2::METHOD_POST);
            $url = sprintf('{"url": "%s"}', $params['image_url']);
            $request->setBody($url);

            $response = $request->send();
            $body = $response->getBody();
            return $body;
        } catch (Exception $e) {
            return $e;
        }
    }
}
