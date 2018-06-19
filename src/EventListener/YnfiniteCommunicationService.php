<?php

namespace Ynfinite\ContaoComBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Config;

use Ynfinite\YnfiniteCacheModel;

use \DateTime;

class YnfiniteCommunicationService {
	private $curlSession;

	private $framework;
	private $config;

	private $serverUrl = "http://staging-server.ynfinite.de";

	public function __construct(ContaoFrameworkInterface $framework) {
		$this->framework = $framework;
		$this->framework->initialize();

		$this->config = $framework->getAdapter(Config::class);

		$this->curlSession = curl_init(); 
		curl_setopt($this->curlSession, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->curlSession, CURLOPT_HEADER, false); 
		curl_setopt($this->curlSession, CURLINFO_HEADER_OUT, true); 

		curl_setopt($this->curlSession, CURLOPT_HTTPHEADER, array(
			'ynfinite-api-key: '.$this->config->get('ynfinite_api_token'),
			'ynfinite-service-id: '.$this->config->get('ynfinite_cms_id'),
			'Cache-Control: no-cache'
		));

	}

	public function debugIt($data, $parentData, $leadType, $appId = "") {
		/*FETCH CONTENT TYPES TEST*/
		$result = $this->fetchContentTypesLeads();
		return array("result" => $result);

		/*SEND DATA TEST*/
		//$result = $this->sendLead($data, $parentData, $leadType, $appId);
		//return array("result" => $result, "data" => $data, "parentData" => $parentData, "type" => $leadType, "sessioninfo" => curl_getinfo($this->curlSession));
		
		
	}

	public function sendLead($data, $parentData = null, $leadTypeId, $appId) {
		$requestData = array( "content" => $data, "content_type_id" => $leadTypeId);
		if($parentData) {
			$requestData['parent'] = array( "content" => $parentData);
		}
		$result = $this->doCurl($this->serverUrl."/v1/content/p/", "PUT", $requestData);
		
		return array("result" => $result, "info" => curl_getinfo($this->curlSession));
	}

	public function fetchContentTypesLeads() {
		$result = $this->doCurl($this->serverUrl."/v1/content_type/p/request");
		$result = json_decode($result);
		
		if($result->error) {
			return $this->generateError();
		}

		return $this->buildTypeOptions($result);
	}

	public function fetchContentTypesContent() {
		$result = $this->doCurl($this->serverUrl."/v1/content_type/p/content");
		$result = json_decode($result);

		if($result->error) {
			return $this->generateError();
		}

		return $this->buildTypeOptions($result);
	}

	public function fetchTagGroups() {
		$result = $this->doCurl($this->serverUrl."/v1/tag_group/p/");
		$result = json_decode($result);

		if($result->error) {
			return $this->generateError();
		}
		
		$options = array();
		foreach($result->hits->hits as $tagGroup) {
			$options[$tagGroup->_id] = $tagGroup->name;
		}
		return $options;
	}

	public function fetchTags($tagGroupId) {
		$options = array();
		if($tagGroupId) {
			$result = $this->doCurl($this->serverUrl."/v1/tag_group/p/".$tagGroupId."/tags");
			$result = json_decode($result);
			if($result->error) {
				return $this->generateError();
			}

			foreach($result->hits->hits as $tag) {
				$options[$tag->slug] = $tag->name;
			}
		}
		return $options;
	}

	public function getContentType($contentTypeId, $distinctFields) {
		$options = array();
		
		$fieldData = $this->getContentTypeFields($contentTypeId, $distinctFields);

		return $fieldData;
	}

	public function getContentTypeFieldOptions($contentTypeId, $buildOneDimensionalList = false) {
		$fieldData = $this->getContentTypeFields($contentTypeId);
		$options = array();

		if($buildOneDimensionalList === true) {
			foreach($fieldData['pages'] as $page) {
				$pageOptions = array();
				foreach($page->panel as $fieldId) {
					$options[$fieldData['fields'][$fieldId]->config->field_name] = $page->name." - ".$fieldData['fields'][$fieldId]->config->name;
				}
			}

			if($fieldData['parent']->_id) {
				$parentData = $this->finishFieldArray($fieldData['parent']);
				
				foreach($parentData['pages'] as $page) {
					$pageOptions = array();
					foreach($page->panel as $fieldId) {
						$options[$parentData['fields'][$fieldId]->config->field_name] = "Zugeordnet: ".$page->name." - ".$parentData['fields'][$fieldId]->config->name;
					}
				}
				
			}
		}
		else {
			foreach($fieldData['pages'] as $page) {
				$pageOptions = array();
				foreach($page->panel as $fieldId) {
					$pageOptions[$fieldData['fields'][$fieldId]->config->field_name] = $fieldData['fields'][$fieldId]->config->name;
				}
				$options[$page->name] = $pageOptions;
			}

			if($fieldData['parent']->_id) {
				$parentData = $this->finishFieldArray($fieldData['parent']);
				
				foreach($parentData['pages'] as $page) {
					$pageOptions = array();
					foreach($page->panel as $fieldId) {
						$pageOptions[$parentData['fields'][$fieldId]->config->field_name] = $parentData['fields'][$fieldId]->config->name;
					}
					$options["Zugeordneter Inhaltstyp"][$page->name] = $pageOptions;
				}
				
			}
		}
	
		return $options;
	}


	public function getContentTypeFieldItems($contentTypeId, $fieldName, $distinctFields = array()) {
		$fieldData = $this->getContentTypeFields($contentTypeId, $distinctFields);

		$items = array();
		if(count($fieldData['fields']) > 0) {
			foreach($fieldData['fields'] as $field) {
				
				if($field->config->field_name == $fieldName) {
					if($field->config->items) {
						foreach($field->config->items as $item) {
							$items[$item->value] = $item->label;
						}
					}
				}
			}
		}

		return $items;
	}

	public function getContentTypeFieldsList($contentTypeId) {
		$fieldData = $this->getContentTypeFields($contentTypeId);		

		$options = array();

		foreach($fieldData['pages'] as $page) {
			$pageOptions = array();
			foreach($page->panel as $fieldId) {
				$options[$fieldData['fields'][$fieldId]->config->field_name] = $fieldData['fields'][$fieldId];
			}
		}

		if($fieldData['parent']->_id) {
			$parentData = $this->finishFieldArray($fieldData['parent']);
			
			foreach($parentData['pages'] as $page) {
				$pageOptions = array();
				foreach($page->panel as $fieldId) {
					$options["__parent__".$parentData['fields'][$fieldId]->config->field_name] = $parentData['fields'][$fieldId];
				}
			}			
		}

		return $options;
	}

	public function getContentTypeFields($contentTypeId, $distinctFields = array()) {
		$returnFields = array();
		$fieldsByIdArray = array();
		$params = array();

		if(count($distinctFields) > 0) {
			$params['distinct'] = $distinctFields;
		}

		$result = $this->doCurl($this->serverUrl."/v1/content_type/p/".$contentTypeId, "GET", $params);
		$result = json_decode($result);
		
		return $this->finishFieldArray($result);
	}

	public function finishFieldArray($contentType){
		$fields = $contentType->data;
		
		// Build fields by id array
		foreach($fields as $field) {
			$fieldsByIdArray[$field->_id] = $field;
		}

		return array("pages" => $contentType->pages, "fields" => $fieldsByIdArray, "parent" => $contentType->parent, "distinct" => $contentType->distinct);
	}

	public function getContentListWithTags($contentType, $filter, $tags, $debug) {
		$filter["slug"] = $tags;
		$result = $this->doCurl($this->serverUrl."/v1/tag/p/slug/content_type/".$contentType, "GET", $filter);
		$result = json_decode($result);
		return $result->hits;
	}

	public function getContentList($contentType, $filter, $debug) {
		$result = $this->doCurl($this->serverUrl."/v1/content_type/p/".$contentType."/content", "GET", $filter);
		$result = json_decode($result);
		return $result->hits;

		/*$debug = $this->serverUrl."/v1/content_type/p/".$contentType."/content";

		return array("result" => $result, "debug" => $debug, "filter" => $filter, "contentType" => $contentType, "sessioninfo" => curl_getinfo($this->curlSession));*/
	}

	public function getContent($id, $contentTypeId) {
		$result = $this->doCurl($this->serverUrl."/v1/content_type/p/".$contentTypeId."/content/".$id);
		$result = json_decode($result);
		return $result;
		//return array("called_url" => $this->serverUrl."/v1/content/p/".$id, "result" => $result, "sessioninfo" => curl_getinfo($this->curlSession));
	}

	public function getContentById($id, $contentTypeId) {
		$result = $this->doCurl($this->serverUrl."/v1/content/p/".$id);
		$result = json_decode($result);
		return $result;
		//return array("called_url" => $this->serverUrl."/v1/content/p/".$id, "result" => $result, "sessioninfo" => curl_getinfo($this->curlSession));
	}

	private function doCurl($url, $httpMethod="GET", $data = null) {

		$useCache = false;

		// Build url and data for call
		if($data !== null) {
			$queryData = http_build_query($data);
			
			if($httpMethod == "PUT" || $httpMethod == "POST") {
				curl_setopt($this->curlSession, CURLOPT_POSTFIELDS, $queryData);
			}
			else {
				$url .= "/?".$queryData;
			}
		}

		// Check if for this request is a cache entry in the database
		$objCache = YnfiniteCacheModel::findByUrl($url);

		if($objCache) {
			$now = new DateTime();
			$created = new DateTime();
			$created->setTimestamp($objCache->created);
			$timeDiff = $now->diff($created);
	

			if($objCache->url && $timeDiff->i < 2) { // and if it is still valide
				$useCache = true;	
			}
		}

		// If not do the request and generate a cache entry
		if(!$useCache) {
			if($objCache) {
				$objCache->delete();
			}

			curl_setopt($this->curlSession, CURLOPT_URL, $url);
			curl_setopt($this->curlSession, CURLOPT_CUSTOMREQUEST, $httpMethod);
			
			$result=curl_exec($this->curlSession);
			

			if($httpMethod == "GET") {
				// generate cache
				$cache = $this->cacheData($url, $httpMethod, $data, $result);
				$result = json_decode($cache->result);
			}
		}
		else {
			// Else return the cached value
			$result = json_decode($objCache->result);
		}

		return $result;
	}

	public function parseText($text, $formData = array()) {
		$matches = array();
		preg_match_all("/\{\{[^\}\}]*\}\}/", $text, $matches);
		
		foreach($matches as $match) {
			$match = $match[0];
			$clearedMatch = substr($match, 2, strlen($match)-4); // remove curly brackets
		
			$splitMatch = explode("::", $clearedMatch);
			
			switch($splitMatch[0]) {
				case "ynfinite_form_value":
					$text = str_replace($match, $formData[$splitMatch[1]], $text);
				break;
				case "ynfinite_from_content":
					$text = $formData->content->{$splitMatch[1]};
				break;
				case "ynfinite":
					switch($splitMatch[1]) {
						case "url_alias":
							$basename = pathinfo($_SERVER['REQUEST_URI'])['basename'];
		                	$text = basename($basename, ".html");
		                break;
					}
				break;
			}
		}
		return $text;
	}

	private function buildTypeOptions($result) {
		$options = array();
		foreach($result->hits->hits as $contentType) {
			$options[$contentType->_id] = $contentType->settings->name;
		}
		return $options;
	}

	public function buildFilterData($filterFields, $sort, $skip, $perPage) {
		// Check if values are set by request
		$setFromRequest = false;
		if(count($values) > 0) $setFromRequest = true;

		$filter = array(
            "pagination" => array("skip" => $skip, "limit" => $perPage)
        );
        
        if($sort) {
        	$filter["sort"] = $sort;
        }

        if(count($filterFields) > 0) {
        	$filter["filter"] = $filterFields;	
        }
        return $filter;
	}

	public function cacheData($url, $httpMethod, $data, $result){
		$cache = new YnfiniteCacheModel();
		$cache->url = $url;
		$cache->methode = $httpMethod;
		$cache->data = json_encode($data);
		$cache->result = json_encode($result);
		$cache->created = time();
		$cache->save();

		return $cache;
	}

	private function generateError() {
		return array(
			"Message" => "Keine Verbindung zu Ynfinite, bitte den API Key in Einstellungen checken", 
			"Error" => $result->error, 
			"Data" => array(
				"Key" => $this->config->get('ynfinite_api_token'), 
				"Id" => $this->config->get('ynfinite_cms_id')
			)
		);
	}
}