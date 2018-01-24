<?php

namespace Ynfinite;

class ContentSingle extends \ContentElement {

	protected $strTemplate = 'ce_ynfinite_content_single';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
		    $retVal = "Ynfinit Content";
		    return $retVal;
        }

        if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
        {
            \Input::setGet('items', \Input::get('auto_item'));
        }

        return parent::generate();
    }

    protected function compile() {
        global $objPage;

        if (TL_MODE == 'BE') {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new \BackendTemplate($this->strTemplate);
        }

        if($this->ynfinite_template){
            $this->Template = new \FrontendTemplate($this->ynfinite_template);
        }

        $container = \Contao\System::getContainer();

        $contentId = null;
        $content = null;

        $loadDataService = $container->get("ynfinite.contao-com.listener.communication");

        if(!$this->ynfinite_content_id) {
            $contentId = \Input::get('items');
            if(!$contentId) {
                $basename = pathinfo($_SERVER['REQUEST_URI'])['basename'];
                $contentId = basename($basename, ".html");
            }

            if($contentId) {
                $content = $loadDataService->getContent($contentId, $this->ynfinite_contentType);    

                if($content) {
                    $this->Template->data = $content;
                }
            }                            
        }
        else {
            $contentId = $this->ynfinite_content_id;
            $content = $loadDataService->getContentById($contentId, $this->ynfinite_contentType);

            if($content) {
                $this->Template->data = $content;
            }

        }

        if($content) {
            
            if($this->ynfinite_set_page_title) {
                $titelField = $this->ynfinite_title_field;
                $objPage->pageTitle = $content->content->$titelField;
            }
        }
        else {
            $this->Template->data = array();
        }
    }
}