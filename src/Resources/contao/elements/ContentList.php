<?php
namespace Ynfinite;

class ContentList extends \ContentElement {

	protected $strTemplate = 'ce_ynfinite_content_list';

    private $operationMap = array(
        "b" => "==",
        "kg" => "<=",
        "gg" => ">=",
        "z" => "<==>",
        "u" => "!=="
    );

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
		    $retVal = "Ynfinit Content List";
		    return $retVal;
        }
        return parent::generate();
    }

    protected function compile() {

        global $objYnfiniteContent;
        global $objPage;

        if (TL_MODE == 'BE') {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new \BackendTemplate($this->strTemplate);
        }

        if($this->ynfinite_template){
            $this->Template = new \FrontendTemplate($this->ynfinite_template);
        }

        $page = 1;        

        if(\Input::get("page")) {
            $page = \Input::get("page");
        }

        $container = \Contao\System::getContainer();

        $loadDataService = $container->get("ynfinite.contao-com.listener.communication");
        $formGeneratorService = $container->get("ynfinite.contao-com.formgenerator");

        $filterId = $this->ynfinite_filter_id;
        $filterFormId = $this->ynfinite_filter_form_id;
        $showForm = $this->ynfinite_show_form;
        $formId = $this->ynfinite_form_id;
        
        $contentType = null;
        $userFilter = array();

        if($filterFormId) {
            $filterForm = \Ynfinite\YnfiniteFilterFormModel::findById($filterFormId);
            $filterAlias = $filterForm->alias;

            if(\Input::get("filter")) {
                $userFilter = \Input::get("filter")[$filterAlias];
            }
        }

        if($filterId) {
            $filter = \Ynfinite\YnfiniteFilterModel::findById($filterId);
            $filterFields = \Ynfinite\YnfiniteFilterFieldsModel::findByPid($filter->id);

            $aliasField = $filter->alias;
            $contentType = $filterForm->contentType;

            if($filter->sortFields) {
                $sort = array("fields" => unserialize($filter->sortFields), "direction" => $filter->sortDirection);    
            }
            else {
                $sort = null;
            }
            
            $skip = ($page-1) * $this->ynfinite_perPage;

            $filterFieldData = array();


            $arrFilter = null;
            if(!$userFilter) {
                $arrFilter = $this->buildFilterArrayFromFilterFields($filterFields);   
            }
            else {
                $arrFilter = $this->buildFilterArrayFromUserFilterAndFilterForm($userFilter, $filterForm);
            }

            $filterData = $loadDataService->buildFilterData($arrFilter, $sort, $skip, $this->ynfinite_perPage);
        }

        // If the base item is not set, we try to load it with the main content type from the page
        if(!$objYnfiniteContent) {
            $contentId = \Input::get('items');
            if(!$contentId) {
                $basename = pathinfo($_SERVER['REQUEST_URI'])['basename'];
                $contentId = basename($basename, ".html");
            }
            if($contentId) {
                $content = $loadDataService->getContent($contentId, $objPage->ynfinite_contentType);
                if($content) {
                    $objYnfiniteContent = $content;
                }
            }
        }


        if($filter->useTags && $objYnfiniteContent) {
            $tags = array();
            foreach($objYnfiniteContent->content->interessengruppen as $tag) {
                $tags[] = $tag->slug;
            }
            $content = $loadDataService->getContentListWithTags($filter->contentType, $filterData, $tags, "");
        }
        else {
            $content = $loadDataService->getContentList($filter->contentType, $filterData, "");
        }

        $this->Template->debug = $content;

        if($this->ynfinite_jumpTo) {
            $objJumpToPage = \Contao\PageModel::findWithDetails($this->ynfinite_jumpTo);
            if($objJumpToPage) {
                $finalContent = array();
                if(count($content->hits) > 0) {
                    foreach($content->hits as $singleContent) {
                        if($aliasField) {
                            $alias = $singleContent->content->$aliasField;
                            
                        }
                        else {
                            $alias = $singleContent->content->alias;
                        }
                        $singleContent->jumpTo = $objJumpToPage->getFrontendUrl("/".$alias);
                        $finalContent[] = $singleContent;
                    }
                    $content->hits = $finalContent;
                }
            }
        }

        // Redirect directly to the found item when there is just one.
        if(count($content->hits) == 1 && $content->hits[0]->jumpTo) {
            //var_dump($_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/".$content->hits[0]->jumpTo);
            header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER["HTTP_HOST"]."/".$content->hits[0]->jumpTo);
            exit;
        }

        if(count($content->hits) === 0 && $showForm) {

        }
        
        $this->Template->data = $content;

        $pagination = array("page" => $page, "total" => $content->total, "maxPages" => (int)($content->total / $this->ynfinite_perPage));
        $this->Template->pagination = $pagination;
        
        $urls = $this->generateUrls($pagination);
        $this->Template->selfUrl = $urls['self'];
        $this->Template->prevUrl = $urls['prev'];
        $this->Template->nextUrl = $urls['next'];
    }

    private function generateUrls($pagination) {
        $returnUrls = array();

        $urlInfo = explode("?", $_SERVER['REQUEST_URI']);

        $urlData = array();
        $concat = "?";

        if(count($urlInfo) > 1) {
            // At this point we are not sure if the page parameter is the last or not so we check for &page= and then page=
            $pagePos = strpos($urlInfo[1], "&page=");
            if($pagePos === false) $pagePos = strpos($urlInfo[1], "page=");

            if($pagePos !== false) {
                $urlData = array( "url" => $urlInfo[0], "params" => substr($urlInfo[1], 0, $pagePos) );
            }
            else {
                $urlData = array( "url" => $urlInfo[0], "params" => $urlInfo[1]);   
            }
        }
        else {
            $urlData = array( "url" => $_SERVER['REQUEST_URI'], "params" => "" );
        }

        
        if ($urlData['params']) $concat = "&";

        $url = $urlData['url'];
        if($urlData['params']){
            $url .= "?".$urlData['params'].$concat;
            $returnUrls['self'] = $url;
            if($pagination['page'] > 1 ) {
                $returnUrls['prev'] = $url."page=".($pagination['page']-1);
            }
            if($pagination['page'] < $pagination['maxPages']) {
                $returnUrls['next'] = $url."page=".($pagination['page']+1);
            }
            
        } 
        else {
            $url .= $concat;
            $returnUrls['self'] = $url;
            if($pagination['page'] > 1 ) {
                $returnUrls['prev'] = $url."page=".($pagination['page']-1);
            }
            if($pagination['page'] < $pagination['maxPages']) {
                $returnUrls['next'] = $url."page=".($pagination['page']+1);
            }
        }

        return $returnUrls;
    }

    private function buildFilterArrayFromFilterFields($filterFields) {
        global $objYnfiniteContent;
        
        $arrFilter = array();

        foreach($filterFields as $filterField) {
            $value = $filterField->value;
            
            $container = \Contao\System::getContainer();
            $comService = $container->get("ynfinite.contao-com.listener.communication");

            $value = $comService->parseText($value, $objYnfiniteContent);

            $arrFilter["content.".$filterField->type_field] = array(
                "operation" => $this->operationMap[$filterField->operation],
                "value" => $value
            );

            if($filterField->operation == "z") {
                $arrFilter["content.".$filterField->type_field]["value2"] = $filterField->value2;
            }
        }
        return $arrFilter;
    }

    private function buildFilterArrayFromUserFilterAndFilterForm($userFilter, $filterForm) {
        $arrFilter = array();

        $filterFields = \Ynfinite\YnfiniteFilterFormFieldsModel::findByPid($filterForm->id);
        
        foreach($filterFields as $filterField) {
            $value = "";
            if($filterField->operation == "z") {
                $value = $userFilter[$filterField->contentTypeField][0];
            }
            else {
                $value = $userFilter[$filterField->contentTypeField];
            }
            
            if($value !== "") {            
                $arrFilter["content.".$filterField->contentTypeField] = array(
                    "operation" => $this->operationMap[$filterField->operation],
                    "value" => $value
                );

                if($filterField->operation == "z") {
                    $arrFilter["content.".$filterField->contentTypeField]["value2"] = $userFilter[$filterField->contentTypeField][1];
                }
            }
        }
        return $arrFilter;
    }
}