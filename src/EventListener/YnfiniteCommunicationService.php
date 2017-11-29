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

	public function debugIt($data, $parentData, $leadType, $appId = "") {
		$result = $this->sendLead($data, $parentData, $leadType, $appId);
		return array("result" => $result, "data" => $data, "parentData" => $parentData, "type" => $leadType, "sessioninfo" => curl_getinfo($this->curlSession));
	}

	public function sendLead($data, $parentData = null, $leadTypeId, $appId) {
		$requestData = array( "content" => $data, "content_type_id" => $leadTypeId);
		if($parentData) {
			$requestData['parent'] = array( "content" => $parentData);
		}
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content/p/", "PUT", $requestData);
		
		return array("result" => $result, "info" => curl_getinfo($this->curlSession));
	}

	public function fetchContentTypesLeads() {
		$result = $this->doCurl("http://staging-server.ynfinite.de/v1/content_type/p/request");
		$result = json_decode($result);
		
		$options = array();

		foreach($result as $contentType) {
			$options[$contentType->_id] = $contentType->name;
		}
		return $options;
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

		$fields = $result->formElements;
		if($result->parent->_id) {
			$parentFields = array();
			foreach($result->parent->formElements as $field) {
				$field->config->field_name = "__parent__".$field->config->field_name;
				$parentFields[] = $field;
			}
			$fields = array_merge($fields, $parentFields);
		}

		return $fields;
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