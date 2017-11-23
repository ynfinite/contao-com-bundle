<?php

namespace Ynfinite\ContaoComBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Config;

class YnfiniteCommunicationService {
	private $curlSession;

	private $framework;

	public function __construct(ContaoFrameworkInterface $framework) {
		$this->framework = $framework;
		$this->framework->initialize();

		$config = $framework->getAdapter(Config::class);

		$this->curlSession = curl_init(); 
		curl_setopt($this->curlSession, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->curlSession, CURLOPT_HEADER, false); 
		curl_setopt($this->curlSession, CURLINFO_HEADER_OUT, true); 

		curl_setopt($this->curlSession, CURLOPT_HTTPHEADER, array(
			'ynfinite-api-key: '.$config->get('ynfinite_api_token'),
			'ynfinite-service-id: '.$config->get('ynfinite_cms_id'),
		));

	}

	public function debugIt($data, $leadType, $appId = "") {
		$result = $this->sendLead($data, $leadType, $appId);
		return $result;
		//return curl_getinfo($this->curlSession);
	}

	public function sendLead($data, $leadTypeId, $appId) {
		$requestData = array( "content" => $data, "content_type_id" => $leadTypeId);
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content/p/", "PUT", $requestData);
		$info = curl_getinfo($this->curlSession);
		switch($info['http_code']) {
			case 201:
				return array("data" => $result, "appId" => $appId, "status" => $info['http_code']);
			break;
		}
		return array("data" => $result, "appId" => $appId, "status" => $info['http_code']);
	}

	public function fetchContentTypesLeads() {
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content_type/p/lead");
		$result = json_decode($result);
		
		$options = array();

		foreach($result as $contentType) {
			$options[$contentType->_id] = $contentType->name;
		}
		return $options;
	}

	public function fetchContentTypesContent() {
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content_type/p/content");
		return array(
			"33333" => "Immobilien",
			"44444" => "Produkte"
		);
	}

	public function getContentTypeFieldOptions($contentTypeId) {
		$formElements = $this->getContentTypeFields($contentTypeId);
		
		$options = array();

		foreach($formElements as $field) {
			$options[$field->config->field_name] = $field->config->name;
		}
		
		return $options;
	}

	public function getContentTypeFields($contentTypeId) {
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content_type/p/".$contentTypeId);
		$result = json_decode($result);

		return $result->formElements;
	}

	private function doCurl($url, $httpMethod="GET", $data = null) {
		curl_setopt($this->curlSession, CURLOPT_URL, $url);
		curl_setopt($this->curlSession, CURLOPT_CUSTOMREQUEST, $httpMethod);
		
		if($data !== null) {
			curl_setopt($this->curlSession, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		
		$result=curl_exec($this->curlSession);
		return $result;
	}
}