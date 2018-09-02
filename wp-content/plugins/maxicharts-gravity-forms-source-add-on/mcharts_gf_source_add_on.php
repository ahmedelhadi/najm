<?php
/*
 * Plugin Name: MaxiCharts Gravity Forms Source Add-on
 * Plugin URI: https://maxicharts.com/category/gravity-forms-add-on/
 * Description: Extend MaxiCharts : Add the possibility to graph Gravity Forms submitted datas
 * Version: 1.6.0
 * Author: MaxiCharts
 * Author URI: https://maxicharts.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mcharts_gf
 * Domain Path: /languages
 */
if (! defined('ABSPATH')) {
    exit();
}
// define ( 'PLUGIN_PATH', trailingslashit ( plugin_dir_path ( __FILE__ ) ) );
define('DEFAULT_MAX_ENTRIES', 200);

if (! class_exists('maxicharts_reports')) {
    // maxicharts_log("include maxicharts_reports");
    define('MAXICHARTS_PLUGIN_PATH', plugin_dir_path(__DIR__));
    // include_once(MAXICHARTS_PATH . 'gf-charts-reports/gf_charts_reports.php');
    $toInclude = MAXICHARTS_PLUGIN_PATH . '/maxicharts/mcharts_utils.php';
    if (file_exists($toInclude)) {
        include_once ($toInclude);
    }
    // include_once(MAXICHARTS_PLUGIN_PATH . '/mcharts_utils.php');
}
// include_once __DIR__ . "/mcharts_utils.php";

if (! class_exists('maxicharts_gravity_forms')) {

    class maxicharts_gravity_forms
    {

        // protected static $logger = null;
        function __construct()
        {
            if (! class_exists('MAXICHARTSAPI')) {
                $msg = __('Please install MaxiCharts before');
                return $msg;
            }
            
            self::getLogger()->debug("Adding Module : " . __CLASS__);
            
            if ($this->checkGravityForms()) {
                add_action('maxicharts_add_shortcodes', array(
                    $this,
                    'add_gravity_forms_shortcode'
                ));
                
                add_filter("maxicharts_get_data_from_source", array(
                    $this,
                    "get_data_from_gf"
                ), 10, 3);
                
                add_filter('mcharts_filter_defaults_parameters', array(
                    $this,
                    'add_default_params'
                ));
                add_filter('mcharts_return_without_graph', array(
                    $this,
                    'return_without_graph'
                ));
            } else {
                self::getLogger()->error("Missing plugin Gravity Forms");
            }
        }

        static function getLogger()
        {
            if (class_exists('MAXICHARTSAPI')) {
                return MAXICHARTSAPI::getLogger('GF');
            }
        }

        function return_without_graph($atts)
        {
            self::getLogger()->trace($atts);
            $type = str_replace(' ', '', $atts['type']);
            if ($type === 'array' || $type === 'total' || $type === 'list' || $type === 'sum' || $type === 'sum_entries' || $type === 'sum_report_fields') {
                return true;
            }
            return false;
        }

        function checkGravityForms()
        {
            $result = true;
            $gfClassHere = class_exists('GFCommon');
            // self::getLogger()->info ( "GF? ".$gfClassHere." -> ".$gfPluginHere);
            if (! function_exists('is_plugin_active')) {
                include_once (ABSPATH . 'wp-admin/includes/plugin.php');
            }
            $gfPluginHere = is_plugin_active('gravityforms/gravityforms.php');
            // self::getLogger()->info ( "GF? ".$gfClassHere." -> ".$gfPluginHere);
            
            if (! $gfClassHere && ! $gfPluginHere) {
                // check if gravity forms installed and active
                $msg = "Please install/activate gravityforms plugin";
                self::getLogger()->error($msg);
                $result = false;
            }
            
            return $result;
        }

        function add_gravity_forms_shortcode()
        {
            self::getLogger()->trace("Adding shortcode : gfchartsreports");
            add_shortcode('gfchartsreports', array(
                $this,
                'gf_charts_shortcode'
            ));
            
            self::getLogger()->trace("Adding shortcode : gfentryfieldvalue");
            add_shortcode('gfentryfieldvalue', array(
                $this,
                'gf_entry_field_value'
            ));
        }

        function gf_entry_field_value($atts)
        {
            self::getLogger()->trace("Executing shortcode : gfentryfieldvalue");
            if (! is_admin()) {
                $source = 'gf';
                $destination = 'text';
                return $this->displayFieldValue($source, $destination, $atts);
            }
        }

        function gf_charts_shortcode($atts)
        {
            self::getLogger()->trace("Executing shortcode gfchartsreports");
            
            if (! is_admin() || wp_doing_ajax()) {
                $source = 'gf';
                $destination = 'chartjs';
                
                self::getLogger()->trace("Executing shortcode gfchartsreports : " . $source . ' -> ' . $destination);
                return maxicharts_reports::chartReports($source, $destination, $atts);
            } else {
                
                self::getLogger()->trace("Admin page for shortcode gfchartsreports : " . $source . ' -> ' . $destination);
            }
        }

        function add_default_params($defaults)
        {
            return $defaults;
        }

        function displayFieldValue($source, $destination, $atts)
        {
            self::getLogger()->trace("gfentryfieldvalue DO Report from " . $source . " to " . $destination);
            $defaultsParameters = array(
                'lead_id' => '',
                'field_id' => '',
                'style' => '',
                'class' => ''
            );
            extract(shortcode_atts($defaultsParameters, $atts, 'gfchartsreports'));
            self::getLogger()->trace($atts);
            $lead_id = str_replace(' ', '', $lead_id);
            $field_id = str_replace(' ', '', $field_id);
            $style = str_replace(' ', '', $style);
            $classParam = str_replace(' ', '', $class);
            
            $entry = GFAPI::get_entry($lead_id);
            // self::getLogger()->trace ( $entry);
            $field_to_display = rgar($entry, $field_id);
            self::getLogger()->trace($field_to_display);
            if ($style) {
                $result = '<span style="' . $style . '">' . $field_to_display . '</span>';
            } else if ($classParam) {
                $result = '<div class="' . $classParam . '">' . $field_to_display . '</div>';
            } else {
                $result = $field_to_display;
            }
            self::getLogger()->trace($result);
            return $result;
        }

        function listValuesOfFieldInForm($gfEntries, $includes)
        {
            self::getLogger()->trace("GF Create list ");
            self::getLogger()->trace($gfEntries);
            $result = '<ul>';
            
            $answersToCatch = MAXICHARTSAPI::getArrayForFieldInForm($gfEntries, $includes);
            
            $result .= implode('</li><li>', $answersToCatch);
            $result .= '</ul>';
            return $result;
        }

        function buildReportFieldsForGF($form_id, $type, $includeArray, $excludeArray = null, $datasets_invert = null)
        {
            self::getLogger()->trace("GF data to dig " . $form_id);
            
            $form = GFAPI::get_form($form_id);
            
            // $graphType = $type;
            $allFields = $form['fields'];
            self::getLogger()->trace("form fields : " . count($allFields));
            foreach ($allFields as $formFieldId => $fieldData) {
                
                $fieldType = $fieldData['type'];
                $fieldId = $fieldData['id'];
                if (! empty($includeArray)) {
                    if (! in_array($fieldId, $includeArray)) {
                        continue;
                    }
                } else if (! empty($excludeArray)) {
                    if (in_array($fieldId, $excludeArray)) {
                        continue;
                    }
                }
                // MAXICHARTSAPI::getLogger()->debug($fieldData);
                self::getLogger()->debug($type . " ### Processing field " . $formFieldId . ' of type ' . $fieldType);
                // $skipField = false;
                // $fieldData = apply_filters('mcharts_filter_gf_field_before_type_process', $formFieldId, $fieldData);
                $unknownType = false;
                
                switch ($fieldType) {
                    case 'product':
                    case 'option':             
                    case 'text':
                    case 'hidden':
                    case 'textarea':
                    case 'name':
                    case 'checkbox':
                    case 'radio':
                    case 'survey':
                    case 'select':
                    case 'multiselect':                        
                    case 'number':
                    case 'slider':
                    case 'list':
                        $reportFields[$fieldId]['inputType'] = $fieldData['inputType'];
                        if ($fieldData['gsurveyLikertEnableMultipleRows'] == 1) {
                            self::getLogger()->debug("MULTI ROW SURVEY LIKERT");
                            //self::getLogger()->debug($fieldData);
                            $reportFields[$fieldId]['choices'] = $fieldData['choices'];
                            $reportFields[$fieldId]['inputs'] = $fieldData['inputs'];
                            $reportFields[$fieldId]['gsurveyLikertEnableMultipleRows'] = 1;
                            $reportFields[$fieldId]['multisets'] = 1;
                        } else if ($fieldType == 'list' && $fieldData['enableColumns']) {
                            $reportFields[$fieldId]['choices'] = $fieldData['choices'];
                            $reportFields[$fieldId]['inputs'] = $fieldData['inputs'];
                            // $reportFields [$fieldId] ['gsurveyLikertEnableMultipleRows'] = 1;
                            $reportFields[$fieldId]['multisets'] = 1;
                        } else {
                            $reportFields[$fieldId]['choices'] = $fieldData['choices'];
                            $reportFields[$fieldId]['multisets'] = $fieldData['multisets'];
                        }
                        
                        break;               
                    
                    default:
                        self::getLogger()->error("Unknown field type : " . $fieldType);
                        $unknownType = true;
                        break;
                }
                
                if ($unknownType) {
                    continue;
                }
                
                $reportFields[$fieldId]['datasets_invert'] = $datasets_invert;
                $reportFields[$fieldId]['label'] = $fieldData['label'];
                $reportFields[$fieldId]['graphType'] = $type;
                $reportFields[$fieldId]['type'] = $fieldType;
                
                self::getLogger()->debug("Creating report field  : " . $fieldData['label'] . ' of type ' . $fieldType . ' -> ' . $type . ' inverted:' . $datasets_invert);
            }
            
            return $reportFields;
        }

        
        function initialize_ordered_answers($fieldId,$countArray,$fieldData){
            foreach ($fieldData['choices'] as $choice) {
                $newPossibleValue = $choice['value'];
                // $allPossibleValues[] = $newPossibleValue;
                $countArray[$fieldId]['orderedAnswers'][$newPossibleValue] = 0;
                $countArray[$fieldId]['valuesAndLabels'][$choice['value']] = $choice['text'];
            }
            
            /*
             * if (!isset($countArray[$fieldId]['valuesAndLabels'][$valueForChoice])) {
                                    $countArray[$fieldId]['valuesAndLabels'][$valueForChoice] = $fieldInReport['text'];
                                }
             */
            
            self::getLogger()->trace("initialize_ordered_answers:");
            self::getLogger()->trace($countArray);
            return $countArray;
        }
        function get_unique_keys($array) {
            $newKeys = array();
            foreach ($array as $newItem) {
                $newKeys[] = $newItem['Year'] . $newItem['Quarter'] . $newItem['Type of Device'];
            }
            
            return $newKeys;
        }
        
        function aggregate_datas($previousDatas, $newDatas, $list_series_names = null, $list_series_values = null, $list_sum_on_value = null){
     
            foreach ($newDatas as $newItem) {
                $concat_key = $newItem['Year'] . $newItem['Quarter'] . $newItem['Type of Device'];
                // bool array_key_exists ( mixed $key , array $array )
                if (array_key_exists($concat_key, $previousDatas)){
                    //$msg = $concat_key." key exists, update total";
                    self::getLogger()->trace($msg);
                    if (!is_numeric($previousDatas[$concat_key]['Number'])){
                        self::getLogger()->error("NAN : ".$newItem['Number']);
                    }
                    $newToAdd = $newItem['Number'];
                    if (!is_numeric( $newToAdd)){
                        $newToAdd = str_replace(',','',$newToAdd);
                        if (!is_numeric($newToAdd)){
                        self::getLogger()->warn("NAN : ". $newItem['Number']);
                        $newToAdd = 0;
                        }
                    }
                    
                    $newValue = $previousDatas[$concat_key]['Number'] + $newToAdd;
                    $msg = $concat_key." key exists, update total : ".$previousDatas[$concat_key]['Number']." + ".$newToAdd. " = " .$newValue;
                    $previousDatas[$concat_key]['Number'] = $newValue;
                    
                } else {
                    $msg = "adding new key : ".$concat_key ." => ".implode(' | ',$newItem);
                    self::getLogger()->trace($msg);
                    $newToAdd = $newItem['Number'];
                    if (!is_numeric( $newToAdd)){
                        $newToAdd = str_replace(',','',$newToAdd);
                        if (!is_numeric($newToAdd)){
                            self::getLogger()->warn("NAN : ". $newItem['Number']);
                            $newItem['Number'] = 0;
                        }
                    }
                    $previousDatas[$concat_key] = $newItem;
                }
            }
            
            return $previousDatas;        
            
        }
        /*
        function getCheckboxForValue($fieldID, $value) {
            
            $fieldInReportArray = $reportFields[$fieldId]['choices'];//[$choiceKey];
            foreach ($fieldInReportArray as $key => $valuesArray){
                
                if ($valuesArray['value'] == $value){
                    $result = 
                }
            }
        }*/
        
        function countAnswers($reportFields, $entries)
        {
            $countArray = array();
            if (count($entries) == 0) {
                self::getLogger()->error('no entries');
                return $countArray;
            }
            self::getLogger()->info("counting answers for report fields");
            self::getLogger()->trace($reportFields);
            
            foreach ($reportFields as $fieldId => $fieldData) {
                if (empty($fieldId)) {
                    self::getLogger()->warn('empty field ' . $fieldId);
                    continue;
                }
                
                $fieldType = $fieldData['type'];
                self::getLogger()->trace("-> Get answers for field " .$fieldId. " | ".$fieldType);
                $multiRowsSurvey = isset($fieldData['gsurveyLikertEnableMultipleRows']) ? $fieldData['gsurveyLikertEnableMultipleRows'] == 1 : false;
                // $listCondition
                $multiRowsList = (isset($fieldData['type']) && $fieldData['type'] == 'list') ? $fieldData['enableColumns'] == 1 : false;
                
                $multiRows = ($multiRowsSurvey || $multiRowsList);
                $multiText = $multiRows ? 'multirows' : 'single row';
                self::getLogger()->info("--> Counting answers in entries for field " . $fieldType . ' (' . $multiText . ') : ' . $fieldId);
                
                self::getLogger()->trace($fieldData);
                // $allPossibleValues = array();
                $countArray[$fieldId] = array();
                if ($fieldType != 'list') {
                    $countArray = $this->initialize_ordered_answers($fieldId,$countArray,$fieldData);
                }
               
                
                self::getLogger()->trace($countArray[$fieldId]);
                //used in order not to process name fields twice (or more!)
                $processed_name_fields = array();
                
                foreach ($entries as $entry) {
                    self::getLogger()->trace("---> entry " . $entry['id']);
                    // self::getLogger()->trace ( $entry );
                    foreach ($entry as $key => $value) {
                        self::getLogger()->trace("process ".$key." => ".$value);
                        if (!isset($key) || !isset($value) /*|| strlen ( strval ( $value) ) == 0*/) {
                            self::getLogger()->trace($entry['id'] . " one is empty $key or $value");
                            continue;
                        } else {
                            self::getLogger()->trace("process ".$key." => ".$value);
                        }

                        
                        if ($fieldType == 'list') {
                            if (trim($key) == trim($fieldId)) {
                                self::getLogger()->debug("Working onlist field serialized data...");
                                $data = @unserialize($value);
                                if ($value === 'b:0;' || $data !== false) {
                                    self::getLogger()->debug("serialized");
                                    self::getLogger()->trace($data[0]);
                                    // FIXME make big total if several entries! list_sum_on_value                                   
                                    
                                    $newDatas = array_values($data);
                                     
                                    if (!empty($newDatas)){
                                        if (isset($countArray[$fieldId]['answers'])){
                                            $countArray[$fieldId]['answers'] = $this->aggregate_datas($countArray[$fieldId]['answers'],$newDatas,$list_sum_on_value);
                                        } else {
                                            $countArray[$fieldId]['answers'] = $this->aggregate_datas(array(),$newDatas,$list_sum_on_value);//$newDatas;
                                        }
                                    }
                                    //$countArray[$fieldId]['answers'] = array_values($data);
                                    
                                    // $countArray [$fieldId]['orderedAnswers'] [$value] += 1;
                                    // self::getLogger()->debug ($data);
                                } else {
                                    self::getLogger()->debug("not serialized data");
                                    
                                    $countArray[$fieldId]['answers'][] = $value;
                                    //$countArray[$fieldId]['orderedAnswers'][$value] += 1;
                                }
                            }
                        } else if ($fieldType == 'multiselect' || $fieldType == 'name' || $fieldType == 'checkbox' || ($fieldType == 'option' && $fieldData['inputType'] == 'checkbox' )) {
                            self::getLogger()->trace($entry['id']." ----> Field " . $fieldType . " $key => $value");
                            
                            if (!isset($countArray[$fieldId]['valuesAndLabels'])){
                                $countArray[$fieldId]['valuesAndLabels'] = array();
                            }
                            /*
                             * ----> Field multiselect 1 => Deuxième choix
                             * ----> Field multiselect 2 => ["Deuxi\u00e8me choix","Troisi\u00e8me choix"]
                             */
                            $keyExploded = explode('.', $key);
                            if (isset($keyExploded[0]) && isset($keyExploded[1]) && $keyExploded[0] == $fieldId) {
                                self::getLogger()->trace("------> Field matches current " . $fieldId . " $key => $value");
                                if (empty($value)) {
                                    self::getLogger()->trace("Checkbox not selected... skips");
                                    continue;
                                } else if ($fieldType == 'option'){
                                    $splittedOption = explode('|',$value);
                                    $valueForChoice = $splittedOption[0];
                                } else if ($fieldType == 'name'){
                                    //$splittedOption = explode('|',$value);
                                    $nameFieldKey = $entry['id'] . '_'.$fieldId;
                                    if (in_array($nameFieldKey ,$processed_name_fields)){
                                        self::getLogger()->trace("NAME field already processed");
                                        continue;
                                    }
                                    $firstn = $fieldId .'.3';
                                    $lastn = $fieldId.'.6';
                                    self::getLogger()->trace("NAME field : $firstn $lastn");
                                    $valueForChoice = ucfirst(strtolower(rgar($entry,$firstn))) . ' '.strtoupper(rgar($entry,$lastn));//$splittedOption[0];
                                    $processed_name_fields[] = $nameFieldKey;
                                } else {
                                    $valueForChoice = $value;
                                }
                                
                               
                                self::getLogger()->trace("++++++ Found answer " . $valueForChoice);
                                $valueForChoice = wp_strip_all_tags($valueForChoice);
                                $countArray[$fieldId]['answers'][] = $valueForChoice;
                                if (isset($countArray[$fieldId]['orderedAnswers'][$valueForChoice])) {
                                    $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                } else {
                                    $countArray[$fieldId]['orderedAnswers'][$valueForChoice] = 1;
                                }
                        
                                $currentTotal = $countArray[$fieldId]['orderedAnswers'][$valueForChoice];
                                self::getLogger()->trace("==== ".$valueForChoice." Total : ".$currentTotal);
                           
                            } else if ($fieldType == 'multiselect' && !empty($key) && trim($key) == trim($fieldId)){
                                /*$splittedOption = explode('|',$value);
                                $valueForChoice = $splittedOption[0];
                                */
                                $field_id    = $fieldId;
                                $form = GFAPI::get_form($entry['form_id']);
                                $field       = GFFormsModel::get_field( $form, $field_id );
                                $field_value = is_object( $field ) ? $field->get_value_export( $entry ) : '';
                                self::getLogger()->debug("==== MULTISELECT values ");
                                self::getLogger()->debug($field_value);
                                
                                $arrayValueForChoice = wp_strip_all_tags($field_value);
                                
                                $multiselectAnswersArray = explode(',',$arrayValueForChoice);
                                self::getLogger()->trace($multiselectAnswersArray);
                                
                                foreach ($multiselectAnswersArray as $newAnswer) {
                                    $valueForChoice = trim($newAnswer);
                                    self::getLogger()->trace("Adding new answer : ".$newAnswer);
                                    $countArray[$fieldId]['answers'][] = $valueForChoice;
                                    if (isset($countArray[$fieldId]['orderedAnswers'][$valueForChoice])) {
                                        $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                    } else {
                                        $countArray[$fieldId]['orderedAnswers'][$valueForChoice] = 1;
                                    }
                                }
                                
                                
                                
                            }
                        } else {
                            self::getLogger()->trace("----> Field " . $fieldType . " $key => $value");
                            if ($fieldType == 'option') {
                                self::getLogger()->debug("Option with inputType : ".$fieldData['inputType']);
                            } /*else if ($fieldType == 'name'){
                                self::getLogger()->debug("----> Field " . $fieldType . " $key => $value");
                            }*/
                            self::getLogger()->trace(trim($key)." ==? ".trim($fieldId) );
                            $multiRowsCondition = ($multiRows && strpos(trim($key), trim($fieldId . '.')) === 0);
                            if (trim($key) == trim($fieldId) || $multiRowsCondition) {
                                self::getLogger()->trace("Field id ".$key . " in entry " .$entry['id']." MATCHES report field id " . $fieldId. " : "." this is an answer to count");
                                if ($fieldType == 'option') {
                                    self::getLogger()->debug("ADD Option with inputType : ".$fieldData['inputType']);
                                   /* if ($fieldData['inputType'] == 'checkbox'){
                                        //$splittedOption = explode('|',$value);
                                        $newValue = $value;
                                    } else {*/
                                        $splittedOption = explode('|',$value);
                                        $newValue = $splittedOption[0];
                                    //}
                                    
                                } else if ($fieldType == 'survey') {
                                    self::getLogger()->debug("### SURVEY FIELD ".$fieldData['inputType']." ###");
                                    if ($multiRows) {
                                        // need to get label instead of value!
                                        self::getLogger()->debug("### MULTI ROWS SURVEY FIELD ###");
                                        self::getLogger()->debug("new answer " . $value . " found in entry " . $entry['id'] . " field id " . $key);
                                        $newValue = $value;
                                    } else {
                                        self::getLogger()->debug("### SINGLE ROW SURVEY FIELD ###");
                                        self::getLogger()->debug("new answer " . $value . " found in entry " . $entry['id'] . " field id " . $key);
                                        self::getLogger()->debug($fieldData);
                                        if ($fieldData['inputType'] == 'rank'){
                                         $orderedAnswers = explode(',',$value);                                         
                                         if (is_array($orderedAnswers)){
                                             $orderScore = range(count($orderedAnswers),1);
                                             self::getLogger()->trace($orderScore);
                                             //  array_combine ( array $keys , array $values )
                                             $scoresToSet = array_combine($orderedAnswers,$orderScore);
                                             foreach ($scoresToSet as $answer => $score){
                                                 $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $answer);
                                                 self::getLogger()->trace("After filter value : '" . $score."' to add.");
                                                 $countArray[$fieldId]['answers'][] = $filteredNewValue;
                                                 if (isset($countArray[$fieldId]['orderedAnswers'][$filteredNewValue])) {
                                                     $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += $score;
                                                 } else {
                                                     $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] = $score;
                                                 }
                                             }
                                         }
                                        } else {
                                        $newValue = $value;
                                        }
                                        // FIXME : rank type ?
                                        // FIXME : rating type ?
                                        
                                        /*
                                        // need to get label instead of value!
                                        self::getLogger()->debug($fieldData);
                                        if (! is_array($fieldData)) {
                                            self::getLogger()->warn("not an array : " . $fieldData);
                                            continue;
                                        }
                                        
                                        foreach ($fieldData as $k => $v) {
                                            if (! is_array($v)) {
                                                self::getLogger()->warn("not an array : " . $v);
                                                continue;
                                            }
                                            foreach ($v as $keyIdx => $originalChoice) {
                                                MAXICHARTSAPI::getLogger()->debug ( $originalChoice );
                                                if (trim($originalChoice['value']) == trim($value)) {
                                                    $newValue = trim($originalChoice['text']);
                                                    $newValue = wp_strip_all_tags($newValue);
                                                }
                                            }
                                        }
                                        */
                                    }
                                } else {
                                    $newValue = $value;
                                }
                                
                                self::getLogger()->trace("New value : '" . $newValue."' to add.");
                                $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $newValue);
                                self::getLogger()->trace("After filter value : '" . $newValue."' to add.");
                                $countArray[$fieldId]['answers'][] = $filteredNewValue;
                                //$countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += 1;
                                if (isset($countArray[$fieldId]['orderedAnswers'][$filteredNewValue])) {
                                    $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += 1;
                                } else {
                                    $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] = 1;
                                }
                                self::getLogger()->trace($filteredNewValue ." total is now : '" . $countArray[$fieldId]['orderedAnswers'][$filteredNewValue]."'");
                            }
                        }
                    }
                }
            }
            self::getLogger()->trace($countArray);
            return $countArray;
        }

        function getGFEntries($form_id, $maxentries, $custom_search_criteria, $atts)
        {
            $form = GFAPI::get_form($form_id);
            $allEntriesNb = GFAPI::count_entries($form_id);
            self::getLogger()->debug("All entries (also deleted!) : " . $allEntriesNb);
            $search_criteria = array();
            $jsonDecoded = false;
            if (!empty($custom_search_criteria)){
                $jsonDecoded = json_decode($custom_search_criteria, true);
                if (false !== $jsonDecoded){
                    $search_criteria = apply_filters('mcharts_modify_custom_search_criteria', $jsonDecoded, $atts);
                } else if (!empty($custom_search_criteria)) {
                    self::getLogger()->error("Cannot JSON decode custom criteria, although non empty");
                    self::getLogger()->error($custom_search_criteria);
                }
            } else {
                $search_criteria['status'] = 'active';
            }         
            self::getLogger()->debug("Given Search crit : ");
            self::getLogger()->debug($custom_search_criteria);            
            self::getLogger()->debug("Converted to Final Search crit : ");
      
            self::getLogger()->debug($search_criteria);
            
            $sorting = null;
            $paging = array(
                'offset' => 0,
                'page_size' => $maxentries
            );
            self::getLogger()->trace(var_export($paging, true));
            self::getLogger()->debug($form_id . ' - ' . $search_criteria . ' - ' . $sorting . ' - ' . $paging);
            // $search_criteria = null;
            $entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, $paging);
            // $entries = GFAPI::get_entries ( $form_id );
            $nbOfEntries = count($entries);
            
            self::getLogger()->debug("Create complete report for form " . $form_id);
            if ($nbOfEntries) {
                self::getLogger()->debug("entries : " . $nbOfEntries);
            } else {
                self::getLogger()->warn("entries : " . $nbOfEntries);
            }
            
            return apply_filters('mcharts_filter_gf_entries', $entries, $atts);
        }

        function computeScores($countArray, $reportFields, $args)
        {
            if (empty($countArray)) {
                $msg = "Empty count array!";
                self::getLogger()->error($msg);
                return $msg;
            } else {
                self::getLogger()->info("At least one item in countarray");
            }
            self::getLogger()->debug($countArray);
            
            foreach ($countArray as $fieldId => $fieldValues) {
                if (! isset($fieldValues['answers'])) {
                    self::getLogger()->warn("No answers for field " . $fieldId);
                    self::getLogger()->warn($fieldValues);
                    $reportFields[$fieldId]['no_answers'] = 1;
                    continue;
                }
                $answers = $fieldValues['answers'];
                $orderedAnswers = $fieldValues['orderedAnswers'];
                if (isset($orderedAnswers) && ! empty($orderedAnswers)) {
                    $reportFields[$fieldId]['scores'] = $orderedAnswers;
                } else {
                    self::getLogger()->debug("--> Computing score for field " . $fieldId);
                    // MAXICHARTSAPI::getLogger()->debug ($answers);
                    if (boolval($args['no_score_computation'])) {
                        // just take answers
                        $reportFields[$fieldId]['scores'] = array_values($answers);
                    } else if (boolval($args['case_insensitive']) == true) {
                        $reportFields[$fieldId]['scores'] = array_count_values(array_map('strtolower', $answers));
                    } else {
                        $reportFields[$fieldId]['scores'] = array_count_values($answers);
                    }
                    self::getLogger()->info("Scores size " . count($reportFields[$fieldId]['scores']));
                    
                    self::getLogger()->debug("Scores computed: ");
                    self::getLogger()->debug($reportFields[$fieldId]['scores']);
                    self::getLogger()->debug("Scores computed from ordered answers: ");
                    self::getLogger()->debug($orderedAnswers);
                }
                
                $reportFields[$fieldId]['valuesAndLabels'] = array_merge(array(),$fieldValues['valuesAndLabels']);
            }
            
            
            
            self::getLogger()->info("Scores computed: ");
            self::getLogger()->debug($reportFields);
            
            return $reportFields; // apply_filters('mcharts_modify_report_after_scores_count',$reportFields);
        }

        function countDataFor($source, $entries, $reportFields, $args)
        {
            $nb_of_entries = count($entries);
            self::getLogger()->info('Building ' . count($reportFields) . " fields upon " . $nb_of_entries . " entries");
            if ($nb_of_entries == 0) {
                self::getLogger()->error('no entries');
                return $reportFields;
            } else {
                self::getLogger()->info('countDataFor for ' . $nb_of_entries . ' entries');
            }
            
            $countArray = $this->countAnswers($reportFields, $entries);
            self::getLogger()->debug(count($countArray) . ' graph should be displayed');
            // self::getLogger()->debug($countArray);
            
            $reportFields = $this->computeScores($countArray, $reportFields, $args);
            /*
            $chartTitle = __("Complete report of form ");
            
            self::getLogger()->debug($chartTitle);
            $reportFields['title'] = $chartTitle;*/
            
            $reportFields = apply_filters('mcharts_gf_filter_fields_after_count', $reportFields, $args);
            
            $toDisplay = count($reportFields) - 1;
            self::getLogger()->debug($toDisplay . ' graph should be displayed');
            self::getLogger()->trace($reportFields);
            $reportFields = $this->buildDatasetsAndLabelsFromScores($reportFields, $args);
            
            return $reportFields;
        }

       
        function getSurveyScores($scores)
        {
            $result = array();
            foreach ($scores as $scoreKey => $scoreValue) {
                $xyValues = explode(':', $scoreKey);
                $datasetName = $xyValues[0];
                $datasetVal = $xyValues[1];
                $result[$datasetName][$datasetVal] = $scoreValue;
            }
            
            return $result;
        }

        function getSurveyChoices($choices)
        {
            $result = array();
            foreach ($choices as $choiceKey => $choiceValue) {
                
                $result[$choiceValue['value']] = $choiceValue['text'];
            }
            
            return $result;
        }

        function buildDatasetsAndLabelsFromScores($reportFields, $args)
        {
            self::getLogger()->debug('buildDatasetsAndLabelsFromScores');
            foreach ($reportFields as $id => $values) {
                $scores = isset($values['scores']) ? $values['scores'] : '';
                if (empty($scores)) {
                    continue;
                }
                /*
                 * self::getLogger()->debug ( "scores:" );
                 * self::getLogger()->debug ( $scores );
                 */
                $multiRows = isset($values['gsurveyLikertEnableMultipleRows']) ? $values['gsurveyLikertEnableMultipleRows'] == 1 : false;
                $forceMultisets = $values['multisets'] == 1 ? true : false;
                if ($multiRows) {
                    self::getLogger()->debug("SURVEY MULTIROWS");
                    $arraySurveyScores = $this->getSurveyScores($scores);
                    $arraySurveyChoices = $this->getSurveyChoices($values['choices']);
                    
                    foreach ($values['inputs'] as $inputIdx => $inputData) {
                        $questionId = trim($inputData['name']);
                        $datasetNameLabel = trim($inputData['label']);
                        
                        // $scoreValues = $arraySurveyScores[$questionId];
                        foreach ($arraySurveyChoices as $choiceId => $choicesText) {
                            $datasetValLabel = $choicesText;
                            // foreach ($scoreValues as $answerId => $dataValue) {
                            // $datasetValLabel = $arraySurveyChoices[$answerId];
                            $dataValue = $arraySurveyScores[$questionId][$choiceId];
                            if ($values['datasets_invert']) {
                                $reportFields[$id]['datasets'][$datasetValLabel]['data'][$datasetNameLabel] = $dataValue;
                                $reportFields[$id]['labels'][] = $datasetNameLabel;
                            } else {
                                $reportFields[$id]['datasets'][$datasetNameLabel]['data'][$datasetValLabel] = $dataValue;
                                $reportFields[$id]['labels'][] = $datasetValLabel;
                            }
                        }
                        
                   
                    }
                
                    $reportFields[$id]['labels'] = apply_filters('mcharts_modify_multirows_labels', $reportFields[$id]['labels']);
                    self::getLogger()->debug($reportFields[$id]['labels']);
                    self::getLogger()->debug($reportFields[$id]['datasets']);
                } else if ($values['type'] == 'list') {
                    self::getLogger()->debug("LIST");
                    // FIXME add all possible datasets even if no score (in order to keep colors ordered)
                    
                    // $reportFields[$id]['datasets'][$datasetNameLabel]
                    $data_conversion = $args['data_conversion'];
                    MAXICHARTSAPI::getLogger()->debug("### data_conversion ".$data_conversion);                   
                    MAXICHARTSAPI::getLogger()->debug($data_conversion);
                    $decoded_json_data = json_decode($data_conversion, true);
                    $transformationData = $decoded_json_data['transformation'];
                    MAXICHARTSAPI::getLogger()->debug($transformationData);
                    $typesToChange = array_keys($transformationData);
                    foreach ($typesToChange as $datasetName) {
                        $reportFields[$id]['datasets'][$datasetName] = array();
                        
                    }
                    foreach ($scores as $scoreKey => $scoreValue) {
                        
                        $datasetNameLabel = $scoreValue[$args['list_series_names']];
                        $valLabelArray = explode('+', $args['list_series_values']);
                        
                        $mappedValLabelArray = array();
                        foreach ($valLabelArray as $labelPart) {
                            $mappedValLabelArray[] = $scoreValue[$labelPart];
                        }
                        $datasetValLabel = implode(' ', $mappedValLabelArray);
                        $scoreDataValue = $scoreValue[$args['list_labels_names']];
                        
                        $scoreDataValue = str_replace(',', '', $scoreDataValue);
                        $roundPrecision = 0;
                        $scoreDataValue = round(floatval($scoreDataValue), $roundPrecision);
                        // round ( float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP ]] )
                        
                        $reportFields[$id]['datasets'][$datasetNameLabel]['data'][$datasetValLabel] = $scoreDataValue;
                        $reportFields[$id]['labels'][] = $datasetValLabel;
                    }
                    
                    $allLabels = $reportFields[$id]['labels'];
                    $allUniqueLabels = array_unique($allLabels);
                    sort($allUniqueLabels);
                    $reportFields[$id]['labels'] = $allUniqueLabels;
                    
                    // add missing keys and sort in order to graph correctly
                    foreach ($reportFields[$id]['datasets'] as $dataSetName => $dataSetData) {
                        $dataSetLabels = array_keys($dataSetData['data']);
                        $labelDiff = array_diff($allUniqueLabels, $dataSetLabels);
                        $newArrayPart = array_fill_keys($labelDiff, 0);
                        $newDatasetData = array_merge($reportFields[$id]['datasets'][$dataSetName]['data'], $newArrayPart);
                        ksort($newDatasetData);
                        $reportFields[$id]['datasets'][$dataSetName]['data'] = $newDatasetData;
                    }
                } else {
                    self::getLogger()->debug("STANDARD FIELD TYPE");
                    $percents = isset($values['percents']) ? $values['percents'] : '';
                    
                    if (!empty($reportFields[$id]['valuesAndLabels'])){
                        self::getLogger()->debug("get labels from valuesAndlabels");
                        self::getLogger()->trace($reportFields[$id]['valuesAndLabels']);
                        $reportFields[$id]['labels'] = array_values($reportFields[$id]['valuesAndLabels']);
                    } else {
                        $reportFields[$id]['labels'] = array_keys($scores);
                    }
                    
                    
                    $reportFields[$id]['data'] = array_values($scores);
                    $reportFields[$id]['labels'] = apply_filters('mcharts_modify_singlerow_labels', $reportFields[$id]['labels']);
                }
              
            }
            
            self::getLogger()->debug($reportFields);
            return $reportFields;
        }

        function createReportFieldForList($reportFields)
        {
  
        }

        function sumAllScoresValues($report_fields, $includeArray, $list_sum_keys)
        {
            self::getLogger()->info("SUM REPORT FIELDS #### " . count($report_fields) . " entries of field(s) ");
            $keys_to_sum = explode(',', $list_sum_keys);
            
            foreach ($report_fields as $idx => $datas) {
                self::getLogger()->debug("$idx => $datas");
                if (! is_numeric($idx)) {
                    continue;
                }
                
                if (! empty($includeArray)) {
                    if (! in_array($idx, $includeArray)) {
                        continue;
                    }
                }
                
                self::getLogger()->debug("$idx => $datas");
                // $keys_to_sum = explode(',',$list_sum_keys);
                // self::getLogger ()->debug ( $datas );
                self::getLogger()->debug("score elements to add " . count($datas['scores']));
                foreach ($datas['scores'] as $idx => $data) {
                    foreach ($data as $list_key => $list_value) {
                        self::getLogger()->trace($data);
                        $key_found = array_search($list_key, $keys_to_sum);
                        if ($list_sum_keys == 'all' || $key_found !== false) {
                            if (is_numeric($list_value)) {
                                $sumArray[] = intval($list_value);
                            }
                        }
                    }
                }
            }
            
            self::getLogger()->trace("### Size of sum array " . count($sumArray));
            $errMsg = __('Empty sum array');
            $errLogMsg = $errMsg . ' : ' . count($entries) . " entries of field(s) " . implode('/', $includeArray);
            if (empty($sumArray)) {
                self::getLogger()->warn($errLogMsg);
                return $errMsg;
            } else {
                self::getLogger()->debug($sumArray);
                $result = array_sum($sumArray);
                self::getLogger()->debug("successfully computed sum : " . $result);
                return $result;
            }
        }

        function countEntriesBy($entries, $includeArray, $datasets_invert, $type)
        {
            $reportFields = array();
            // $userVal = reset($includeArray);
            $idx = 0;
            foreach ($includeArray as $userVal) {
                $reportFields[$idx] = array();
                foreach ($entries as $entry) {
                    $valueOfUserField = rgar($entry, $userVal);
                    
                    if ((! empty($valueOfUserField))) {
                        
                        if ($userVal === "created_by") {
                            $author_obj = get_user_by('id', $valueOfUserField);
                            $valToInsert = $author_obj->display_name;
                        } else {
                            $valToInsert = $valueOfUserField;
                        }
                        
                        $reportFields[$idx]['answers'][] = $valToInsert;
                        $reportFields[$idx]['datasets_invert'] = $datasets_invert;
                        $reportFields[$idx]['label'] = $userVal;
                        $reportFields[$idx]['graphType'] = $type;
                    }
                }
                $idx ++;
            }
            return $reportFields;
        }

        function sumAllValues($entries, $includeArray, $list_sum_keys)
        {
            self::getLogger()->info("SUM #### " . count($entries) . " entries of field(s) " . implode('/', $includeArray));
            
            foreach ($includeArray as $field_id_to_count) {
                foreach ($entries as $entry) {
                    if (is_numeric($field_id_to_count)) {
                        $valToSum = rgar($entry, strval($field_id_to_count));
                        if (is_numeric($valToSum)) {
                            $sumArray[] = intval($valToSum);
                        } else {
                            self::getLogger()->debug("Not an int value for field " . $valToSum);
                            $unserializeData = @unserialize($valToSum);
                            if ($valToSum === 'b:0;' || $unserializeData !== false) {
                                self::getLogger()->debug("Serialized value decoded ");
                                self::getLogger()->trace($list_sum_keys);
                                
                                $keys_to_sum = explode(',', $list_sum_keys);
                                self::getLogger()->trace($keys_to_sum);
                                
                                // $itemTotal = 0;
                                foreach ($unserializeData as $idx => $datas) {
                                    foreach ($datas as $list_key => $list_value) {
                                        $key_found = array_search($list_key, $keys_to_sum);
                                        if ($list_sum_keys == 'all' || $key_found !== false) {
                                            if (is_numeric($list_value)) {
                                                $sumArray[] = intval($list_value);
                                            }
                                        }
                                    }
                                }
                            } else {
                                self::getLogger()->warn("Not an int nor a serialized value for field " . $valToSum);
                            }
                        }
                    } else {
                        self::getLogger()->warn("Not an numeric field number " . $field_id_to_count);
                    }
                }
            }
            self::getLogger()->debug("Size of sum array " . count($sumArray));
            $errMsg = __('Cannot compute sum');
            $errLogMsg = $errMsg . ' : ' . count($entries) . " entries of field(s) " . implode('/', $includeArray);
            if (empty($sumArray)) {
                self::getLogger()->warn($errLogMsg);
                return $errMsg;
            } else {
                $result = array_sum($sumArray);
                self::getLogger()->debug("successfully computed sum : " . $result);
                return $result;
            }
        }
        
        function getRadarDatasets($gf_form_id, $entries, $datasets_field, $includeArray){
        	self::getLogger()->debug("---> Create RADAR with " .$datasets_field);
        
        	$form = GFAPI::get_form($gf_form_id);
        	
        	$reportFields[0] = array('datasets' => array(), 'labels' => array());
        	$reportFields[0]['graphType'] = 'radar';
        	$reportFields[0]['multisets'] = 1;
        	$includedLabels = array();
        	self::getLogger()->debug($reportFields);
        	foreach ($entries as $entry){
        		self::getLogger()->debug("---> entry " . $entry['id']);
        		
        		foreach ($entry as $key => $value) {
        			self::getLogger()->debug($entry['id'].") process ".$key." => ".$value);
        			if ($key == $datasets_field){
        				self::getLogger()->debug("Radar new dataset ".$key." => ".$value);
        				//$reportFields[0]['datasets'] = array();
        				$data = array();
        				//$labelsForDataset = array();
        				self::getLogger()->debug("Process all fields as radar axis : ".implode($includeArray));
        				foreach ($includeArray as $includeFieldId) {
        					
        					$includedValue = rgar($entry,$includeFieldId);
        					if (empty($includedValue)){
        					    self::getLogger()->error("No value in entry for field ".$includeFieldId);
        					}
        					//self::getLogger()->debug($form['fields'][$includeFieldId]);
        					$data[] = $includedValue;
        					// $form['fields'][0]->label;
        					
        					$fieldOfForm = GFAPI::get_field( $form, $includeFieldId );
        					$newLabel = $fieldOfForm->label;
        					//$newLabel = $form['fields'][$includeFieldId]->label;
        					if (empty($newLabel)){
        					    self::getLogger()->error("No value in entry for label ".$newLabel);
        					}
        					
        					self::getLogger()->debug("radar ".$key.' / '.$newLabel." add new data ".$includeFieldId." = ".$includedValue);
        					if (!in_array($newLabel,$includedLabels)){
        					    $includedLabels[] = $newLabel;
        					    self::getLogger()->debug("+++ New label added : ".$newLabel);
        					}
        					
        					
        					//$newDataset['labels'][] = $datasetValLabel;
        				}
        				
        				$newDataset = array('data' => $data,'label' => $key);
        				//$newDataset['labels'] = $includedLabels;
        				$reportFields[0]['datasets'][$value] = $newDataset;
        				
        			}
        			
        		}
        	}
        	
        	$reportFields[0]['labels'] = $includedLabels;
        	
        	self::getLogger()->debug($reportFields);
        	return $reportFields;
        	
        }

        function get_data_from_gf($reportFields, $source, $atts)
        {
            self::getLogger()->info("Process source " . $source);
            // $reportFields = array ();
            if ($source == 'gf') {
                
                $defaultsParameters = array(
                    'type' => 'pie',
                    'mode' => '',
                    'url' => '',
                    'position' => '',
                    'float' => false,
                    'center' => false,
                    'title' => 'chart',
                    'canvaswidth' => '625',
                    'canvasheight' => '625',
                    'width' => '48%',
                    'height' => 'auto',
                    'margin' => '5px',
                    'relativewidth' => '1',
                    'align' => '',
                    'class' => '',
                    'labels' => '',
                    'data' => '30,50,100',
                    'data_conversion' => '',
                    'datasets_invert' => '',
                    'datasets' => '',
                    'gf_form_ids' => '',
                    'multi_include' => '',
                	'multisets' => '',
                	'datasets_field' => '',
                    'gf_form_id' => '1',
                    'maxentries' => strval(DEFAULT_MAX_ENTRIES),
                    'gf_criteria' => '',
                    'include' => '',
                    'exclude' => '',
                    'colors' => '',
                    'color_set' => '',
                    'color_rand' => false,
                    'chart_js_options' => '',
                    'tooltip_style' => 'BOTH',
                    'custom_search_criteria' => '',
                    'fillopacity' => '0.7',
                    'pointstrokecolor' => '#FFFFFF',
                    'animation' => 'true',
                    'xaxislabel' => '',
                    'yaxislabel' => '',
                    'scalefontsize' => '12',
                    'scalefontcolor' => '#666',
                    'scaleoverride' => 'false',
                    'scalesteps' => 'null',
                    'scalestepwidth' => 'null',
                    'scalestartvalue' => 'null',
                    'case_insensitive' => false,
                    'no_score_computation' => false,
                    'list_series_names' => '',
                    'list_series_values' => '',
                    'list_labels_names' => '',
                    'list_sum_keys' => 'all',
                    'data_only' => '',
                    'xcol' => '0',
                    'ycol' => '1',
                    'compute' => '',
                    'header_start' => '0',
                    'case_insensitive' => false,
                    'header_size' => '1',
                    // new CSV
                    'columns' => '',
                    'rows' => '',
                    'delimiter' => '',
                    'information_source' => '',
                    'no_entries_custom_message' => ''
                );
                extract(shortcode_atts($defaultsParameters, $atts));
                
                $type = str_replace(' ', '', $type);
                $mode = str_replace(" ", '', $mode);
                $url = str_replace(' ', '', $url);
                $title = str_replace(' ', '', $title);
                $data = explode(',', str_replace(' ', '', $data));
                $data_conversion = str_replace(' ', '', $data_conversion);
                $datasets_invert = str_replace(' ', '', $datasets_invert);
                // $gv_approve_status = explode ( ";", str_replace ( ' ', '', $gv_approve_status) );
                $datasets = explode("next", str_replace(' ', '', $datasets));
                $gf_form_ids = explode(',', str_replace(' ', '', $gf_form_ids));
                $multi_include = explode(',', str_replace(' ', '', $multi_include));
                $gf_form_id = str_replace(' ', '', $gf_form_id);
                if (empty($gf_form_id) || $gf_form_id < 0) {
                    $gf_form_id = 1;
                }
                $colors = str_replace(' ', '', $colors);
                $color_set = str_replace(' ', '', $color_set);
                $color_rand = str_replace(' ', '', $color_rand);
                $position = str_replace(' ', '', $position);
                $float = str_replace(' ', '', $float);
                $center = str_replace(' ', '', $center);
                // $information_source = $information_source;
                $case_insensitive = boolval(str_replace(' ', '', $case_insensitive));
                $no_score_computation = boolval(str_replace(' ', '', $no_score_computation));
                
                $list_series_names = $list_series_names;
                $list_series_values = $list_series_values;
                $list_labels_names = $list_labels_names;
                
                $include = str_replace(' ', '', $include);
                $exclude = str_replace(' ', '', $exclude);
                $tooltip_style = str_replace(' ', '', $tooltip_style);
                $xcol = str_replace(' ', '', $xcol);
                $columns = maxicharts_reports::get_all_ranges($columns);
                $rows = maxicharts_reports::get_all_ranges($rows);
                $delimiter = str_replace(' ', '', $delimiter);
                self::getLogger()->debug($columns);
                self::getLogger()->debug($rows);
                $compute = str_replace(' ', '', $compute);
                $maxentries = str_replace(' ', '', $maxentries);
                if (empty($maxentries)) {
                    $maxentries = DEFAULT_MAX_ENTRIES;
                }
                $header_start = str_replace(' ', '', $header_start);
                $header_size = str_replace(' ', '', $header_size);
                
                if ((! empty($include))) {
                    $includeArray = explode(",", $include);
                }
                if (! empty($exclude)) {
                    $excludeArray = explode(",", $exclude);
                }
                
                self::getLogger()->info("Get DATAS from GF source " . $source);
                if (! empty($gf_form_ids) && count($gf_form_ids) > 1 && ! empty($multi_include) && count($multi_include) > 1) {
                    // process multi-form sources
                    self::getLogger()->info("#### MULTI sources process");
                    
                    $multiCombined = array_combine($gf_form_ids, $multi_include);
                    self::getLogger()->info($multiCombined);
                    $countArray = array();
                    foreach ($multiCombined as $gf_id => $field_id) {
                        self::getLogger()->info("#### MULTI " . $gf_id . ' -> ' . $field_id);
                        $entries = $this->getGFEntries($gf_id, $maxentries, $custom_search_criteria, $atts);
                        $currentReportFields = $this->buildReportFieldsForGF($gf_id, $type, array(
                            $field_id
                        ), null, $datasets_invert);
                        
                        self::getLogger()->info("#### MULTI Counting " . $gf_id . ' -> ' . $field_id);
                        
                        $currentCount = $this->countAnswers($currentReportFields, $entries);
                        $reportFieldsArray[] = $currentReportFields;
                        
                        $answers = reset($currentCount)['answers'];
                        
                        $mergedAnswers = array_merge($mergedAnswers, $answers);
                        $countArray[] = $currentCount;
                    }
                    
                    self::getLogger()->debug("#### MULTI DATA RETRIEVED " . count($reportFieldsArray) . ' graph should be merged');
                    self::getLogger()->debug($countArray);
                    self::getLogger()->debug($reportFieldsArray);
                    $reportFields = reset($reportFieldsArray);
                    self::getLogger()->debug($reportFields);
                    
                    self::getLogger()->debug(array_search("answers", $countArray));
                    
                    $reportFields = $this->computeScores($countArray, $reportFields);
                    
                    self::getLogger()->info($rpa);
                } else if (! empty($gf_form_id) && $gf_form_id > 0) {
                    self::getLogger()->info("#### SINGLE source process");
                    $entries = $this->getGFEntries($gf_form_id, $maxentries, $custom_search_criteria, $atts);
                    $nbOfFechedEntries = count($entries);
                    if ($nbOfFechedEntries > 0) {
                        self::getLogger()->info($nbOfFechedEntries . " entries fetched");
                        if ($type === 'total') {
                            return $nbOfFechedEntries;
                        } else if ($mode === 'count') {
                            $reportFields = $this->countEntriesBy($entries, $includeArray, $datasets_invert, $type);
                        } else if ($type === 'sum' || $type === 'sum_entries') {
                            
                            // $totalCount = 0;
                            $sumArray = $this->sumAllValues($entries, $includeArray, $list_sum_keys);
                            return $sumArray;
                        } else if ($type === 'array') {
                            self::getLogger()->info("#### array type");
                            $result = MAXICHARTSAPI::getArrayForFieldInForm($entries, $includeArray);
                            self::getLogger()->info("#### array type result");
                            self::getLogger()->info($result);
                            return $result;
                        } else if ($type === 'list') {
                            return $this->listValuesOfFieldInForm($entries, $includeArray);
                        } else if ($type === 'radar' && !empty($datasets_field)) {
                        	self::getLogger()->info('Radar with '.$datasets_field);               		
                        			
                        	return $this->getRadarDatasets($gf_form_id, $entries, $datasets_field, $includeArray);                   		
                        	
                        } else {
                        
                            // if standard graph type
                            $reportFields = $this->buildReportFieldsForGF($gf_form_id, $type, isset($includeArray) ? $includeArray : null, isset($excludeArray) ? $excludeArray : null, $datasets_invert);
                            self::getLogger()->debug(count($reportFields) . ' graph(s) should be displayed');
                        }
                        
                        if (empty($reportFields)) {
                            $msg = "No data available for fields";
                            self::getLogger()->warn($msg);
                            return $msg;
                        } /* if ($type == 'radar'  && isset($datasets_field)) {
                        	$reportFields = apply_filters('mcharts_gf_filter_fields_after_count', $reportFields, $args);
                        	$reportFields = $this->buildDatasetsAndLabelsFromScores($reportFields, $args);
                        } else*/ if ($mode === 'count') {
                            /*
                             * computeScores
                             * build
                             */
                            $countArray = array_merge(array(), $reportFields);
                            
                            self::getLogger()->debug($reportFields);
                            self::getLogger()->debug($countArray);
                            
                            $reportFields = $this->computeScores($countArray, $reportFields, $args);
                            $reportFields = apply_filters('mcharts_gf_filter_fields_after_count', $reportFields, $args);
                            $reportFields = $this->buildDatasetsAndLabelsFromScores($reportFields, $args);
                        } else {
                            self::getLogger()->debug('### Entries values computation on report fields:', count($reportFields));
                            
                            $reportFields = $this->countDataFor($source, $entries, $reportFields, $atts);
                            
                            if ($type === 'sum_report_fields') {
                                self::getLogger()->debug($reportFields);
                                $sumArray = $this->sumAllScoresValues($reportFields, $includeArray, $list_sum_keys);
                                return $sumArray;
                            }
                        }
                    } else {
                        
                        $form_object = GFAPI::get_form($gf_form_id);
                        $formTile = '';
                        if (! is_wp_error($form_object)) {
                            $formTitle = $form_object['title'];
                            $formId = $form_object['id'];
                        }
                        
                        if (empty($no_entries_custom_message)) {
                            $displayed_msg = "No answer to form " . '<em> ' . $formTitle . ' </em> (' . $formId . ') ' . "yet";
                        } else {
                            $displayed_msg = $no_entries_custom_message;
                        }
                        
                        self::getLogger()->warn($displayed_msg);
                        self::getLogger()->warn("check filter:");
                        self::getLogger()->warn($custom_search_criteria);
                        return $displayed_msg;
                    }
                }
            }
            
            self::getLogger()->info(__CLASS__ . ' returns ' . count($reportFields) . ' report fields');
            
            return $reportFields;
        }
    }
}

new maxicharts_gravity_forms();