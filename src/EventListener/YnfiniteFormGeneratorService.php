<?php

namespace Ynfinite\ContaoComBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Config;

use Ynfinite\YnfiniteCacheModel;

use \DateTime;

class YnfiniteFormGeneratorService {

	private $framework;
	private $config;

	public function __construct(ContaoFrameworkInterface $framework) {
		$this->framework = $framework;
		$this->framework->initialize();

		$this->config = $framework->getAdapter(Config::class);
	}	

	public function generateForm($form, $formElements) {
        $container = \Contao\System::getContainer();


        $fields = unserialize($form->formFields);
        
        $groupStarter = array();
        if($form->groupStarter) $groupStarter = unserialize($form->groupStarter);
        
        $groupEnder = array();
        if($form->groupEnder) $groupEnder = unserialize($form->groupEnder);
		
        uksort($formElements, function($key1, $key2) use ($fields, $formElements) {
            $return = (array_search($formElements[$key1]->config->field_name, $fields) > array_search($formElements[$key2]->config->field_name, $fields) ? 1 : -1);
            return $return;
        });

		$realFieldNames = array();
        $outputFields = array();
        $validate = array();
        $messages = array();

        foreach($formElements as $element) {
        	
            $key = array_keys($fields, $element->config->field_name);
            $starter = array_keys($groupStarter, $element->config->field_name);
            $ender = array_keys($groupEnder, $element->config->field_name);


            if($key) {
                $markup = "";
                switch($element->type) {
                    case "checkbox":
                        $markup = $this->renderCheckboxField($element, $starter, $ender);
                        break;
                    case "select":
                        $markup = $this->renderSelectField($element, $starter, $ender);
                        break;

                    case "number":
                    case "text":
                        $markup = $this->renderTextField($element, $starter, $ender);
                        break;
                }
                $validationData = $this->getValidationData($element);

                $outputFields[$key[0]] = $markup;
                $validate["data[".$element->config->field_name."]"] = $validationData['validation'];
                $messages["data[".$element->config->field_name."]"] = $validationData['messages'];
        	}
        }

        return array("outputFields" => $outputFields, "validate" => $validate, "messages" => $messages, "groupStarter" => $groupStarter, "groupEnder" => $groupEnder);
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
        switch($field->config->selecttype) {
            case "checkbox":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>';
                foreach($items as $item) {
                    $markup .= '<div class="widget-inner-container">
                        <div class="widget-option-container">
                            <input type="checkbox" name="data['.$field->config->field_name.']" value="'.$item->value.'" />
                        </div>
                        <label>'.$item->label.'</label>
                    </div>';
                }
            break;
            case "radio":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>';
                foreach($items as $item) {
                    $markup .= '<div class="widget-inner-container">
                        <div class="widget-option-container">
                            <input type="radio" name="data['.$field->config->field_name.']" value="'.$item->value.'" />
                        </div>
                        <label>'.$item->label.'</label>
                    </div>';
                }
            break;
            case "select":
                $markup = '<label for="'.$field->config->field_name.'">'.$label.'</label>
                    <select name="data['.$field->config->field_name.']">
                    <option value="" selected="selected">-</option>';
                
                foreach($items as $item) {
                    $markup .= "<option value='".$item->value."'>".$item->label."</option>";    
                }

                $markup .= "</select>";
            break;
        }

        $return .= '<div class="widget '.$field->config->selecttype.'" data-fieldname="'.$field->config->name.'">
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