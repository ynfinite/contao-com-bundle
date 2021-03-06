<?php
namespace Ynfinite;

class ContentForm extends \ContentElement {

	protected $strTemplate = 'ce_ynfinite_form';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
		    $retVal = "Ynfinit Form";
		    return $retVal;
        }
        return parent::generate();
    }

    protected function compile() {

        if (TL_MODE == 'BE') {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new \BackendTemplate($this->strTemplate);
        }

        $container = \Contao\System::getContainer();

        // Get Services
		$loadDataService = $container->get("ynfinite.contao-com.listener.communication");
        $formGeneratorService = $container->get("ynfinite.contao-com.formgenerator");

        $formId = $this->ynfinite_form_id;
        $form = \Ynfinite\YnfiniteFormModel::findById($formId);

        $formElements = $loadDataService->getContentTypeFieldsList($form->leadType);
        $requestToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();
        
        $formData = $formGeneratorService->generateForm($form, $formElements);

        $this->Template->cssData = $this->cssID;
        $this->Template->requestToken = $requestToken;
        $this->Template->formId = $formId;
        $this->Template->leadType = $form->leadType;
        $this->Template->uid = uniqid();

        //$this->Template->fields = htmlspecialchars(json_encode($outputFields, JSON_FORCE_OBJECT), ENT_QUOTES, 'UTF-8');
        $this->Template->fields = $formData['outputFields'];
        $this->Template->validate = $formData['validate'];
        $this->Template->messages = $formData['messages'];

        $this->Template->groupStarter = $formData['groupStarter'];
        $this->Template->groupEnder = $formData['groupEnder'];

        $this->Template->submitLabel = $form->submitLabel;
    }


    function renderTextField($field, $starter, $ender) {
        $label = $field->label;
        if(!$label) $label = ucfirst($field->config->name);
        if($field->config->mandatory === true) {
            $label .="<span class'required'>*</span>";
        }
        $return = "";
        if($starter) {
            $return .= "<div class='field-group'>";
        }

        if(!$field->config->hidden) {
            $return .= '<div class="widget text" data-fieldname="'.$field->config->name.'">
                <label for="'.$field->config->field_name.'">'.$label.'</label>';
            $return .= '<input type="hidden" name="realFieldNames['.str_replace("__parent__", "", $field->config->field_name).']" value="'.$field->config->name.'" />';

            if($field->config->multiline) {
                $return .= '<textarea name="data['.$field->config->field_name.']"></textarea>';
            }
            else {
                $return .= '<input type="text" name="data['.$field->config->field_name.']" />';
            }
            
            $return .= '</div>';
        }
        else {
            $return .= '<input type="hidden" name="realFieldNames['.str_replace("__parent__", "", $field->config->field_name).']" value="'.$field->config->name.'" />';
            $return .= '<input type="hidden" name="data['.$field->config->field_name.']" />';
        }


        if($ender) {
            $return .= "</div>";
        }
        return $return;
    }

    function renderSelectField($field, $filterData, $formUuid) {
        $markup = "";

        // Build label
        $label = $field->label;
        if(!$label) $label = ucfirst($field->config->name);
        if($field->config->mandatory === true) {
            $label .="<span class'required'>*</span>";
        }

        // Check if its a group starter
        $return = "";
        if($starter) {
            $return .= "<div class='field-group'>";
        }


        // Build field markup
        $items = $field->config->items;
        switch($field->config->selectType) {
            case "checkbox":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>';
                foreach($items as $item) {
                    $markup .= '<div class="widget-inner-container">
                        <div class="widget-option-container">
                            <input type="checkbox" name="data['.$field->config->field_name.']" value="'.$item->name.'" />
                        </div>
                        <label>'.$item->name.'</label>
                    </div>';
                }
            break;
            case "radio":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>';
                foreach($items as $item) {
                    $markup .= '<div class="widget-inner-container">
                        <div class="widget-option-container">
                            <input type="radio" name="data['.$field->config->field_name.']" value="'.$item->name.'" />
                        </div>
                        <label>'.$item->name.'</label>
                    </div>';
                }
            break;
            case "select":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>
                    <select name="data['.$field->config->field_name.']">
                    <option value="" selected="selected">-</option>';
                
                foreach($items as $item) {
                    $markup .= "<option value='".$item->name."'>".$item->name."</option>";    
                }

                $markup .= "</select>";
            break;
        }

        $return .= '<div class="widget '.$field->config->selectType.'" data-fieldname="'.$field->config->name.'">
            <input type="hidden" name="realFieldNames['.str_replace("__parent__", "", $field->config->field_name).']" value="'.$field->config->name.'" />
            '.$markup.'
        </div>';

        // Check if its end of group
        if($ender) {
            $return .= "</div>";
        }
        
        return $return;
    }
    
    function renderCheckboxField($field, $filterData, $formUuid) {
        $markup = "";

        // Build label
        $label = $field->label;
        if(!$label) $label = ucfirst($field->config->name);
        if($field->config->mandatory === true) {
            $label .="<span class'required'>*</span>";
        }

        // Check if its a group starter
        $return = "";
        if($starter) {
            $return .= "<div class='field-group'>";
        }

        // Build field markup
        $markup .= '<div class="widget-inner-container">
            <div class="widget-option-container">
                <input type="checkbox" name="data['.$field->config->field_name.']" value="'.$field->config->name.'" />
            </div>
            <label>'.$label.'</label>
        </div>';


        $return .= '<div class="widget checkbox" data-fieldname="'.$field->config->name.'">
            <input type="hidden" name="realFieldNames['.str_replace("__parent__", "", $field->config->field_name).']" value="'.$field->config->name.'" />
            '.$markup.'
        </div>';

        // Check if its end of group
        if($ender) {
            $return .= "</div>";
        }
        
        return $return;
    }

    function renderHiddenField($field, $filterData, $formUuid) {        
        return '<input type="hidden" value="'.$field->value.'" name="data['.$field->config->field_name.']" />';
    }


    function getValidationData($field) {
        $validationData = array();

        $validationString = "{";
        $messageString = "{";
        
        $validation = array();
        $messages = array();

        if($field->config->mandatory === true) {
            $validation[] = "required: true";
            $messages[] = "required: 'Bitte füllen Sie dieses Feld aus.'";
        }
        $validationString .= implode(",", $validation)."}";
        $messageString .= implode(",", $messages)."}";

        $validationData['validation'] = $validationString;
        $validationData['messages'] = $messageString;

        return $validationData;
    }
}