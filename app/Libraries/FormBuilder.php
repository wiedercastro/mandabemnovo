<?php
namespace App\Libraries;

use App\Libraries\Validation;
use Illuminate\Http\Request;

class FormBuilder 
{
    private $config = array(/* Config array - can be overrided by passing in array in ini() */
        'default_input_type' => 'form_input',
        'default_input_container_class' => 'form-group',
        'bootstrap_required_input_class' => 'form-control',
        'default_dropdown_class' => 'valid',
        'default_control_label_class' => 'col-sm-2 control-label',
        'default_no_label_class' => 'col-sm-offset-2',
        'default_form_control_class' => 'col-sm-9',
        'default_form_class' => 'form-horizontal col-sm-12',
        'default_button_classes' => 'btn btn-primary',
        'default_date_post_addon' => '', // For instance '<span class="input-group-btn"><button class="btn default" type="button"><i class="glyphicon glyphicon-calendar"></i></button></span>'
        'default_date_format' => 'Y-m-d',
        'default_date_today_if_not_set' => FALSE,
        'default_datepicker_class' => '', // For instance 'date-picker'
        'empty_value_html' => '<div class="form-control" style="border:none;"></div>',
        'use_testing_value' => true
    );
    private $func; /* Global function holder - used in switches */
    private $data_source; /* Global holder for the source of the data */
    private $elm_options; /* Global options holder */
    private $elm_options_help;
    private $print_string = ''; /* An output buffer */
    private $error_validation = [];

    /**
     * @property array $input_addons
     * This is for adding input-groups and addons.
     * pre/post do not have to be inputed as arrays but will be turned into ones
     * so that we can handle multipal pre/post input addons.
     */
    private $input_addons = array(
        'exists' => false, /* does the specific input have an addon? */
        'pre' => array(), /* container for pre addons */
        'pre_html' => '',
        'post' => array(), /* container for post addons */
        'post_html' => ''
    );
    private $data_post = [];

    function __construct($config = array()) 
    {
        if (!empty($config)) {
            $this->init($config);
        } else {
            $this->func = $this->config['default_input_type'];
        }
    }

    function init($config = array()) 
    {
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->config[$k] = $v;
            }
            $this->func = $this->config['default_input_type'];
        }
    }

    function getConfig() 
    {
        return $this->config;
    }

    function openForm($options) 
    { 
        $action = '';
        if (isset($options['action'])) {
            $action = $options['action'];
            unset($options['action']);
        } else {
            $this->showError('No action set for form. Please include array(\'action\' => \'\') in the open_form(...) function call');
        }

        $class = $this->config['default_form_class'];
        if (isset($options['class'])) {
            $class = $options['class'];
        }
        $options['class'] = $class;
        $options['autocomplete'] = 'on';

        return $this->_buildFormOpen($action, $options);
    }

    public function showError($error) 
    {
        $html = '<div class="alert alert-danger">';
        foreach ($error as $e) {
            $html .= '<i class="fa fa-exclamation-triangle"></i> ' . $e . "<br>";
        }
        $html .= '</div>';
        return $html;
    }

    private function _buildFormOpen($action, $attributes = [])
    {
        $route = route($action);
        $attributes = array_merge(['method' => 'POST', 'enctype' => 'multipart/form-data'], $attributes);
        return '<form action="' . $route . '" ' . $this->_attributesToHtml($attributes) . '>';
    }

    private function _attributesToHtml($attributes)
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= $key . '="' . e($value) . '" ';
        }
        return trim($html);
    }
    public function getDataPost() 
    {
        return $this->data_post;
    }

    public function validadeData($fields, $post, $type_return = 'NORMAL') 
    {
        $data_post = array();
        $error = array();

        foreach ($fields as $code => $field) {

            $data_post[$code] = ( isset($post[$code]) && strlen(trim($post[$code])) ) ? trim($post[$code]) : null;
            if (isset($field['required']) && $field['required'] && !$data_post[$code]) {
                $error[] = "Informe o campo " . $field['label'] . "";
            }

            if (isset($field['maxlength']) && (int) $field['maxlength']) {
                if (mb_strlen($data_post[$code], 'UTF-8') > (int) $field['maxlength']) {
                    $error[] = "Texto para " . $field['label'] . " excede o numero de caracters permitidos. O número máximo permitido é de " . (int) $field['maxlength'] . " caracters.";
                }
            }
            if (isset($field['type']) && $field['type'] == 'email' && strlen(trim($data_post[$code]))) {
                if (!filter_var($data_post[$code], FILTER_VALIDATE_EMAIL)) {
                    $error[] = sprintf("O email \"%s\" informado é inválido, informe um email válido.", $data_post[$code]);
                }
            }
            $validation = new Validation();
            if ((strtolower($code) == 'cpf' || strtolower($code) == 'cnpj') && strlen($data_post[$code])) {
                if (!$validation->validDoc(strtolower($code), $data_post[$code])) {
                    if (strtolower($code) == 'cpf') {
                        $error[] = "O CPF informado é invalido.";
                    } else if (strtolower($code) == 'cnpj') {
                        $error[] = "O CNPJ informado é invalido.";
                    }
                }
            }

            if ((strtolower($code) == 'cpf' || strtolower($code) == 'cnpj' || strtolower($code) == 'telefone' || strtolower($code) == 'cep' ) && strlen($data_post[$code])) {
                $data_post[$code] = preg_replace('/[^0-9]/', '', $data_post[$code]);
            }
        }
        if ($error) {
            if ($type_return == 'NORMAL') {
                $this->error_validation = '<hr><div class="alert alert-danger">Erro:<br>';
                foreach ($error as $e) {
                    $this->error_validation .= '<i class="fa fa-exclamation-triangle"></i> ' . $e . "<br>";
                }
                $this->error_validation .= '</div>';
            }
            if ($type_return == 'ARRAY') {
                $this->error_validation = $error;
            }
            $this->data_post = $data_post;
            return false;
        }
        return $data_post;
    }

    public function getErrorValidation() 
    {
        return $this->error_validation;
    }

    public function mountFormV1($fields, $default_cols = array()) 
    {
        $form_html = array('all' => '');
        foreach ($fields as $code => $field) {

            $field['no_cols'] = true;

            if (isset($default_cols['force_cols']) && $default_cols['force_cols']) {
                $field['no_cols'] = false;
            }

            if (isset($field['type']) && $field['type'] == 'hidden') {
                $html_item = '     <input type="hidden" id="' . ( isset($field['element_id']) ? $field['element_id'] : $code ) . '" name="' . $code . '"  value="' . (isset($field['default_value']) ? $field['default_value'] : '' ) . '" />';
            } else {
                if ($default_cols && !isset($default_cols['force_cols'])) {
                    $col_1 = $default_cols[0];
                    $col_2 = $default_cols[1];
                } else {
                    $col_1 = isset($field['cols']) ? $field['cols'][0] : '4';
                    $col_2 = isset($field['cols']) ? $field['cols'][1] : '8';
                }
                if (!isset($field['type'])) {
                    $field['type'] = 'text';
                }

                if ($field['type'] == 'checkbox') {
 
                    $html_item = '<div class="form-check">';
                    $html_item .= '<input class="form-check-input" ' . (isset($field['checked']) && $field['checked'] ? ' checked="checked" ' : '' ) . ' type="checkbox" value="1x" id="' . $code . '" name="' . $code . '" />';
                    $html_item .= '<label class="form-check-label" for="' . $code . '">';
                    $html_item .= $field['label'];
                    $html_item .= '</label>';
                    $html_item .= '</div>';
                } elseif($field['type'] == 'autocomplete'){
                    $html_item = '<div class="form-group">';
                    $html_item.= '<label style="text-align: left;" class="control-label" for="date_from">';
                    $html_item.= $field['label'].'</label>';
                    $html_item.= '<input autocomplete="off" type="text" id="'.( isset($field['element_keyup_id']) ? $field['element_keyup_id'] : (isset($field['element_id']) ? $field['element_id'] : $code ) ).'_autoc_type" name="' . ( '_elem_autoc_'. time(). rand(0,99) ) . '" placeholder="'.(isset($field['placeholder']) ? $field['placeholder'] : $field['label'] ).'" value="'.$field['description'].'" class="form-control" />';
                    $html_item.= '<input type="hidden" value="'.(isset($field['default_value']) ? $field['default_value'] : '').'" name="'.(isset($field['element_name']) ? $field['element_name'] : $code ).'" id="'.(isset($field['element_id']) ? $field['element_id'] : $code ).'" />';
                    $html_item.= '</div>';
                } else {

                    $html_item = '';
                    
                    // Neste caso será apresentado apenas o ELEMENTO
                    if (!isset($field['skip_form_group']) || !$field['skip_form_group']) {
                        $html_item .= '<div class="form-group">';
                        $html_item .= '<label style="text-align: left;" class="';
                    }

                    if (!isset($field['no_cols']) || !$field['no_cols']) {
                        $html_item .= 'col-md-' . $col_1 . ' ';
                    }
                    if (!isset($field['skip_form_group']) || !$field['skip_form_group']) {
                        $html_item .= 'control-label" for="' . $code . '">' . ( isset($field['no_label']) && $field['no_label'] ? '' : isset($field['label']) ? $field['label'] : '' ) . ' ';
                    }
                    if (isset($field['help'])) {
                        $html_item .= '<i class="fa fa-info-circle" style="color: #008adb;" title="' . $field['help'] . '"></i>';
                    }
                    if (isset($field['help_click'])) {
                        $html_item .= '<a href="" data-path="' . $field['help_click']['path'] . '" class="btn_help_click"><i class="fa fa-question-circle" style="color: #008adb;" ></i></a>';
                    }

                    if (!isset($field['no_label']) || !$field['no_label']) {
                        $html_item .= ( isset($field['required']) && $field['required'] ? ' <sup class="sup_required">*</sup> ' : '' );
                    }

                    if (!isset($field['skip_form_group']) || !$field['skip_form_group']) {

                        $html_item .= '</label>';
                    }

                    if (!isset($field['no_cols']) || !$field['no_cols']) {
                        $html_item .= '  <div class="col-md-' . $col_2 . '" ' . ($field['type'] == 'checkbox' ? ' style="text-align: left" ' : '') . '>';
                    }
                    if ($field['type'] == 'text' || $field['type'] == 'password' || $field['type'] == 'email') {
                        $html_item .= '     <input ' . ( isset($field['autocomplete_off']) && $field['autocomplete_off'] ? ' autocomplete="199039909" ' : '' ) . ' ' . (isset($field['maxlength']) ? ' maxlength="' . $field['maxlength'] . '" ' : '') . '  ' . ( isset($field['class']) && $field['class'] == 'inp-date' ? ' autocomplete="off" ' : '' ) . '  type="' . $field['type'] . '" id="' . ( isset($field['element_id']) ? $field['element_id'] : $code ) . '" name="' . $code . '" placeholder="' . (isset($field['placeholder']) ? $field['placeholder'] : (isset($field['label']) ? $field['label'] : '') ) . '" value="' . (isset($field['default_value']) ? $field['default_value'] : '' ) . '" class="form-control ' . (isset($field['class']) ? $field['class'] : '') . '" ' . (isset($field['disabled']) && $field['disabled'] ? ' disabled="disabled" ' : '') . ' />';
                    } else if ($field['type'] == 'checkbox') {
                        $html_item .= '<input style="width: 20px; height: 20px;" type="checkbox" id="' . ( isset($field['element_id']) ? $field['element_id'] : $code ) . '" name="' . $code . '" value="1" class="checkbox" />';
                    } else if ($field['type'] == 'select') {
                        $html_item .= '     <select name="' . ( isset($field['element_name']) ? $field['element_name'] : $code ) . '" id="' . ( isset($field['element_id']) ? $field['element_id'] : $code ) . '" class="form-control ' . (isset($field['class']) ? $field['class'] : '') . '" ' . (isset($field['disabled']) && $field['disabled'] ? ' disabled="disabled" ' : '') . ' ';

                        if (isset($field['datajs'])) {
                            foreach ($field['datajs'] as $djs) {
                                $html_item .= " " . $djs[0] . '="' . $djs[1] . '" ';
                            }
                        }

                        $html_item .= ' >';

                        if (!isset($field['no_show_empty']) || !$field['no_show_empty']) {
                            $html_item .= '         <option value="">' . (isset($field['place_holder_default']) ? $field['place_holder_default'] : 'Selecione:') . '</option>';
                        }
                        foreach ($field['opts'] as $opt) :
                            $html_item .= '           <option ' . (isset($field['default_value']) && $field['default_value'] == $opt->id ? ' selected ' : '' ) . ' value="' . $opt->id . '">' . $opt->name . '</option>';
                        endforeach;
                        $html_item .= '     </select>';
                    } else if ($field['type'] == 'textarea') {
                        $html_item .= '     <textarea ' . (isset($field['rows']) ? ' rows="' . $field['rows'] . '" ' : '') . ' ' . (isset($field['colstextarea']) ? ' cols="' . $field['colstextarea'] . '" ' : '') . ' id="' . ( isset($field['element_id']) ? $field['element_id'] : $code ) . '" name="' . $code . '" placeholder="' . (isset($field['placeholder']) ? $field['placeholder'] : $field['label'] ) . '" class="form-control" >' . (isset($field['default_value']) ? $field['default_value'] : '') . '</textarea>';
                    }
                    if (!isset($field['no_cols']) || !$field['no_cols']) {
                        $html_item .= '  </div>';
                    }
                    if (!isset($field['skip_form_group']) || !$field['skip_form_group']) {
                        $html_item .= '</div>';
                    }
                }
            }
            $form_html[$code] = $html_item;
            $form_html['all'] .= $html_item;
        }
        return $form_html;
    }

    private function _recursiveBuildJson($ary, $offset = 0) 
    {
        $kv_str = '';
        foreach ($ary as $k => $v) {
            /* This offset class doesn't look that great :/ ' */
            $offset_class = '';
            if ($offset >= 1) {
                $offset_class = 'col-sm-offset-' . $offset;
            }

            if ((is_array($v) || is_object($v)) && !is_string($v)) {
                $new_offset = $offset + 1;
                $innter_str = $this->_recursiveBuildJson((array) $v, $new_offset);
                $kv_str .= '<div class="' . $offset_class . '"><strong>' . ucwords(strtolower(str_replace(array('_', '-'), ' ', $k))) . '</strong>' . $innter_str . '</div>';
            } else {
                $kv_str .= '<div class="' . $offset_class . '"><strong>' . ucwords(strtolower(str_replace(array('_', '-'), ' ', $k))) . '</strong>: ' . $v . '</div>';
            }
        }
        return $kv_str;
    }

    private function _resetBuilder() 
    {
        $this->print_string = '';
        $this->func = $this->config['default_input_type'];
    }

    private function _makeLabel($str) 
    {
        return ucwords(str_replace(array('_', '-', '[', ']'), array(' ', ' ', ' ', ' '), $str));
    }

    private function _label($link_to_input_id = true)
    {
        $label = '';
    
        if (isset($this->elm_options['label']) && $this->elm_options['label'] == 'none') {
            return ''; /* the keyword none */
        } elseif (isset($this->elm_options['label'])) {
            $label = $this->elm_options['label'];
        } elseif (isset($this->elm_options['id']) && $this->func != 'form_submit') {
            $label = $this->_makeLabel($this->elm_options['id']);
        }
    
        if ($this->func == 'form_submit') {
            $label = '';
        }
    
        return '<label for="' . ($link_to_input_id ? $this->elm_options['name'] : '') . '" class="' . $this->config['default_control_label_class'] . '">' . $label . '</label>';
    }
    
    private function _postInput() 
    {
        return '</div>';
    }

    private function _buildHelpBlock() 
    {
        if (!empty($this->elm_options_help)) {
            return '<span class="help-block">' . $this->elm_options_help . '</span>';
        }
        return '';
    }

    private function _buildInputAddonsPost() 
    {
        $ret_string = '';
        if ($this->input_addons['exists']) {
            if (!empty($this->input_addons['post_html'])) {
                $ret_string = $this->input_addons['post_html'];
            } else {
                foreach ($this->input_addons['post'] as $post_addon) {
                    $ret_string .= '<span class="input-group-addon">' . $post_addon . '</span>';
                }
            }
            $ret_string .= '</div>';
        }
        return $ret_string;
    }

    private function _createExtraString() 
    {
        $extra = '';
        foreach ($this->elm_options as $k => $v) {
            $extra .= " {$k}=\"{$v}\"";
        }
        return trim($extra);
    }

    private function _preElm() 
    {
        return '<div class="' . $this->config['default_input_container_class'] . '">';
    }

    private function _postElm() 
    {
        return '</div>';
    }

    private function _preInput() 
    {
        if (($this->func === 'form_date') && ($this->config['default_datepicker_class'] !== '')) {
            return '<div class="date ' . $this->config['default_datepicker_class'] . ' ' . $this->config['default_form_control_class'] . '" data-date="' . $this->elm_options['value'] . '" data-date-format="' . preg_replace(array('/Y/', '/m/', '/d/'), array('yyyy', 'mm', 'dd'), $this->config['default_date_format']) . '" data-date-viewmode="years">';
        }
        return '<div class="' . $this->config['default_form_control_class'] . '">';
    }

    function advSetValue($field = '', $default = '') 
    {
        $request = app(Request::class);

        if (!$request->hasValidation()) {
            if ($request->has($field)) {
                return e($request->input($field, $default));
            }

            return $default;
        }

        return e(old($field, $default));
    }

    private function _prepOptions() 
    {
        foreach ($this->elm_options as &$opt) {
            /* trying again to change everything to an array */
            if (is_object($opt)) {
                $opt = (array) $opt;
            }
        }
        $this->func = $this->config['default_input_type'];
        /* Pull the input type from the array */
        if (isset($this->elm_options['type']) && !empty($this->elm_options['type'])) {
            $this->func = 'form_' . $this->elm_options['type'];
            unset($this->elm_options['type']);
        } else {
            $this->func = $this->config['default_input_type'];
        }

        /* make sure to add 'form-control' to the class array */
        $class = $this->config['bootstrap_required_input_class'];
        if (isset($this->elm_options['class'])) {
            $class .= ' ' . trim(str_replace($this->config['bootstrap_required_input_class'], '', $this->elm_options['class']));
        }
        $this->elm_options['class'] = $class;

        /* make sure there is a name' attribute */
        if (!isset($this->elm_options['name'])) {
            /* put the id as the name by default - makes smaller 'config' arrays */
            if (isset($this->elm_options['id'])) {
                $this->elm_options['name'] = $this->elm_options['id'];
            } else {
                $this->elm_options['name'] = '';
            }
        }

        /* make sure there is a 'value' attribute
         * Also, make for fun defaulting by passing an object
         */
        $default_value = '';
        if (isset($this->elm_options['name']) && isset($this->data_source[$this->elm_options['name']]) && empty($this->elm_options['value'])) {
            $default_value = $this->data_source[$this->elm_options['name']];
        } elseif (isset($this->elm_options['value'])) {
            $default_value = $this->elm_options['value'];
        }

        if (isset($this->elm_options['testing_value']) && $this->config['use_testing_value']) {
            $default_value = $this->elm_options['testing_value'];
        }

        $this->elm_options['value'] = $this->advSetValue($this->elm_options['name'], $default_value);


        /* ====== Handle input_addons ======== */

        /* FIRST - clear the input_addons global array from any previous elemets */
        $this->input_addons = array(
            'exists' => false,
            'pre' => array(),
            'pre_html' => '',
            'post' => array(),
            'post_html' => ''
        );

        /* playing nice: handling the singular case */
        if (isset($this->elm_options['input_addon'])) {
            $this->elm_options['input_addons'] = $this->elm_options['input_addon'];
            unset($this->elm_options['input_addon']);
        }

        /* set the new input_addons array */
        if (isset($this->elm_options['input_addons']) && !empty($this->elm_options['input_addons'])) {
            /* there are input addons */
            $this->input_addons['exists'] = true;

            /* check for pre addons */
            if (isset($this->elm_options['input_addons']['pre']) && !empty($this->elm_options['input_addons']['pre'])) {
                $pre = $this->elm_options['input_addons']['pre'];
                if (!is_array($pre)) { /* to handle more than one, this needs to be an array - but should handle the easy case of one string */
                    $pre = array($pre);
                }
                $this->input_addons['pre'] = $pre;
            }

            /* then check for post addons */
            if (isset($this->elm_options['input_addons']['post']) && !empty($this->elm_options['input_addons']['post'])) {
                $post = $this->elm_options['input_addons']['post'];
                if (!is_array($post)) { /* to handle more than one, this needs to be an array - but should handle the easy case of one string */
                    $post = array($post);
                }
                $this->input_addons['post'] = $post;
            }

            /* accomidate hard coding of custom elements */
            if (isset($this->elm_options['input_addons']['pre_html']) && !empty($this->elm_options['input_addons']['pre_html'])) {
                $this->input_addons['pre_html'] = $this->elm_options['input_addons']['pre_html'];
            }
            if (isset($this->elm_options['input_addons']['post_html']) && !empty($this->elm_options['input_addons']['post_html'])) {
                $this->input_addons['post_html'] = $this->elm_options['input_addons']['post_html'];
            }


            /* unset it so that no funky stuff happens */
            unset($this->elm_options['input_addons']);
        }

        /* remove help element - don't need help properties to be in input elements */
        $this->elm_options_help = (isset($this->elm_options['help']) && !empty($this->elm_options['help'])) ? $this->elm_options['help'] : '';
        unset($this->elm_options['help']);
        return;
    }

    private function _buildInput($include_pre_post = true) 
    {
        $input_html_string = '';
        /* Combine elements have multiple input elements on the same line.
         * This block will call this function, '_buildInput' call recursivly.
         *
         * Example use: Credit Card EXP month/year
         */
        if ($this->func == 'form_combine') {
            if (!isset($this->elm_options['elements'])) {
                dump($this->elm_options);
                $this->showError('Tried to create `form_combine` with no elements. (id="' . $this->elm_options['name'] . '")');
            }

            $elm_options_backup = $this->elm_options; /* We need to make a copy for everything to work correctly */

            $counter = 0;
            foreach ($elm_options_backup['elements'] as $elm) {
                $this->elm_options = $elm; /* We override elm_options */
                $this->_prepOptions(); /* Run Prep on the new one */
                if ($counter > 0 && !empty($elm_options_backup['combine_divider'])) {
                    $input_html_string .= $elm_options_backup['combine_divider'];
                }
                $input_html_string .= $this->_buildInput(false);
                $counter++;
            }

            $this->elm_options = $elm_options_backup; /* We put our options back */
            $this->_prepOptions(); /* Run Prep to restore the state in which we begain */
        } else {
            /*
             * json
             * button (anchor, a)
             * label
             * date
             * email
             * tel
             * number
             * input
             * hidden
             * submit
             * dropdown (option)
             * html
             * textarea
             * file
             * checkbox
             * radio
             */
            switch ($this->func) {
                /*
                 * This should eventualy be expanded to be able to edit individual elements in the k=>v
                 * For now it will just display them.
                 */
                case 'form_json':
                    $input_html_string = $this->_recursiveBuildJson((array) json_decode($this->elm_options['value']));
                    break;
                case 'form_button':
                case 'form_anchor':
                case 'form_a':
                    $class = str_replace($this->config['default_button_classes'], '', $this->elm_options['class']);
                    $class = str_replace($this->config['bootstrap_required_input_class'], '', $class); /* remove the 'form-control' class */
                    /* add class="valid" to all dropdowns (makes them not full width - and works better with select2 plugin) */
                    if (strpos($class, $this->config['default_button_classes']) === FALSE) {
                        $class .= ' ' . $this->config['default_button_classes'];
                    }
                    $this->elm_options['class'] = trim($class);

                    $value = $this->elm_options['label'];
                    unset($this->elm_options['label']);

                    $input_html_string = '<a href="' . url('') . '" ' . $this->_attributesToHtml($this->elm_options) . '>' . $value . '</a>';
                    break;
                case 'form_label':
                    $input_html_string = '<label for="' . $this->elm_options['value'] . '" class="control-label text-left">' . 
                    $this->_makeLabel($this->elm_options['value']) . '</label>';

                    break;
                case 'form_date':
                    $this->elm_options['type'] = 'date'; // HTML5 compliant type for date
                    if ($this->config['default_date_post_addon'] != '') {
                        $this->input_addons['exists'] = TRUE;
                        $this->input_addons['post_html'] = $this->config['default_date_post_addon'];
                    }

                    try {
                        if (empty($this->elm_options['value'])) {
                            if ($this->config['default_date_today_if_not_set']) {
                                $dt = new \DateTime('today');
                                $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                            }
                        } else {
                            $dt = new \DateTime($this->elm_options['value']);
                            $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                        }
                    } catch (\Exception $e) {
                        if ($this->config['default_date_today_if_not_set']) {
                            $dt = new \DateTime('today');
                            $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                        }
                    }

                    $input_html_string = '<input ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_email':
                    $this->elm_options['type'] = 'email';
                    $input_html_string = '<input ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_tel':
                    $this->elm_options['type'] = 'tel';
                    $input_html_string = '<input ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_number':
                    $this->elm_options['type'] = 'number';
                    $input_html_string = '<input ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_input':
                    $input_html_string = '<input ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_hidden':
                    return '<input type="hidden" name="' . $this->elm_options['id'] . '" value="' . e($this->elm_options['value']) . '">';

                case 'form_submit':
                    $name = $this->elm_options['id'];
                    $label = $this->_makeLabel((isset($this->elm_options['label']) ? $this->elm_options['label'] : $this->elm_options['id']));

                    unset($this->elm_options['id']);
                    unset($this->elm_options['label']);

                    $class = str_replace($this->config['default_button_classes'], '', $this->elm_options['class']);
                    $class = str_replace($this->config['bootstrap_required_input_class'], '', $class); /* remove the 'form-control' class */
                    /* add class="valid" to all dropdowns (makes them not full width - and works better with select2 plugin) */
                    if (strpos($class, $this->config['default_button_classes']) === FALSE) {
                        $class .= ' ' . $this->config['default_button_classes'];
                    }
                    $this->elm_options['class'] = trim($class);

                    $input_html_string = '<button type="submit" name="' . $name . '" ' . $this->_attributesToHtml($this->elm_options) . '>' . $label . '</button>';

                    break;
                case 'form_option':
                case 'form_dropdown':
                    /* form_dropdown is different than an input */
                    if (isset($this->elm_options['options']) && !empty($this->elm_options['options'])) {
                        $name = $this->elm_options['name'];
                        $options = $this->elm_options['options'];
                        $value = $this->elm_options['value'];

                        unset($this->elm_options['name']);
                        unset($this->elm_options['value']);
                        unset($this->elm_options['options']);

                        if (!empty($this->config['default_dropdown_class'])) {
                            $class = str_replace($this->config['bootstrap_required_input_class'], '', $this->elm_options['class']);
                            /* add class="valid" to all dropdowns (makes them not full width - and works better with select2 plugin) */
                            if (strpos($class, $this->config['default_dropdown_class']) === FALSE) {
                                $class .= ' ' . $this->config['default_dropdown_class'];
                            }

                            if (strpos($class, $this->config['bootstrap_required_input_class']) === FALSE) {
                                $class .= ' ' . $this->config['bootstrap_required_input_class'];
                            }
                            $this->elm_options['class'] = trim($class);
                        }

                        $input_html_string = '<select name="' . $name . '" ' . $this->_attributesToHtml($this->elm_options) . '>';

                        foreach ($options as $key => $option) {
                            $selected = ($key == $value) ? 'selected' : '';
                            $input_html_string .= '<option value="' . e($key) . '" ' . $selected . '>' . e($option) . '</option>';
                        }

                        $input_html_string .= '</select>';

                    } else {
                        dump($this->elm_options);
                        $this->showError('Tried to create `form_dropdown` with no options. (id="' . $this->elm_options['name'] . '")');
                    }
                    break;
                case 'form_html':
                    if (!isset($this->elm_options['html'])) {
                        dump($this->elm_options);
                        $this->showError('Tried to create `form_html` with no html. (id="' . $this->elm_options['id'] . '")');
                    }
                    $input_html_string = $this->elm_options['html'];
                    break;
                case 'form_textarea':
                    $this->elm_options['value'] = html_entity_decode($this->elm_options['value']);
                    $input_html_string = '<textarea ' . $this->_attributesToHtml($this->elm_options) . '>' . e($this->elm_options['value']) . '</textarea>';
                    break;
                case 'form_file':
                    $input_html_string = '<input type="file" ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_checkbox':
                    $input_html_string = '<input type="checkbox" ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                case 'form_radio':
                    $input_html_string = '<input type="radio" ' . $this->_attributesToHtml($this->elm_options) . '>';
                    break;
                default:
                    if (function_exists($this->func)) {
                        $input_html_string = call_user_func($this->func, $this->elm_options);
                    } else {
                        $this->showError("Could not find function to build form element: '{$this->func}'");
                    }
                    break;
            }
        }
        $ret_string = '';
        $ret_string .= ($include_pre_post) ? $this->_preInput() : '';
        $ret_string .= $this->_buildInputAddonsPre();
        $ret_string .= (empty($input_html_string)) ? $this->config['empty_value_html'] : $input_html_string;
        $ret_string .= $this->_buildInputAddonsPost();
        $ret_string .= ($include_pre_post) ? $this->_buildHelpBlock() : '';
        $ret_string .= ($include_pre_post) ? $this->_postInput() : '';

        return $ret_string;
    }

    private function _buildInputAddonsPre() 
    {
        $ret_string = '';
        if ($this->input_addons['exists']) {
            if (!empty($this->input_addons['pre_html'])) {
                $ret_string = $this->input_addons['pre_html'];
            } else {
                $ret_string .= '<div class="input-group">';
                foreach ($this->input_addons['pre'] as $pre_addon) {
                    $ret_string .= '<span class="input-group-addon">' . $pre_addon . '</span>';
                }
            }
        }
        return $ret_string;
    }

    function squishHTML($html) 
    {
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
            [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
            [^<]*+        # Either zero or more non-"<" {normal*}
            (?:           # Begin {(special normal*)*} construct
                <           # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script)\b)
                [^<]*+      # more non-"<" {normal*}
            )*+           # Finish "unrolling-the-loop"
            (?:           # Begin alternation group.
                <           # Either a blacklist start tag.
                (?>textarea|pre|script)\b
            | \z          # or end of file.
            )             # End alternation group.
        ) # If we made it here, we are not in a blacklist tag.
        %Six';
        $text = preg_replace($re, " ", $html);
        if ($text === null) {
            return $html;
        }
        return $text;
    }

    function closeForm() 
    {
        $input_html_string = '</form>';
        return $input_html_string;
    }
    
    function buildDisplay($options, $data_source = array()) 
    {
        $this->_resetBuilder();
        $this->data_source = (array) $data_source;

        /* styling prefrence */
        $this->config['default_control_label_class'] .= ' bold';

        $this->print_string .= $this->_buildFormOpen('', array('class' => $this->config['default_form_class']));

        foreach ($options as $elm_options) {
            $this->elm_options = $elm_options;

            if (is_array($this->elm_options)) {
                $this->_prepOptions();
                if ($this->func != 'form_json') {
                    $this->func = 'form_label'; /* The only difference */
                }
                $this->print_string .= $this->_preElm();
                $this->print_string .= $this->_label();
                $this->print_string .= $this->_buildInput();
                $this->print_string .= $this->_postElm();
            }
        }
        $this->print_string .= $this->closeForm();
        return $this->squishHTML($this->print_string);
    }

    function buildFormHorizontal($options, $data_source = array()) 
    {
        $this->_resetBuilder();
        $this->data_source = (array) $data_source;

        foreach ($options as $elm_options) {
            $this->elm_options = $elm_options;

            if (is_array($this->elm_options)) {
                $this->_prepOptions();
                switch ($this->func) {
                    case 'form_hidden':
                        $this->print_string .= $this->_buildInput();
                        break;
                    case 'form_checkbox':
                    case 'form_radio':
                        // Link main label to input when there is only one option and that option has an empty label
                        $link_to_input = ((count($this->elm_options['options']) === 1) && array_key_exists('label', $this->elm_options['options'][0]) && ($this->elm_options['options'][0]['label'] === ''));

                        $default_form_control_class = $this->config['default_form_control_class'];
                        if (!array_key_exists('label', $this->elm_options) || ($this->elm_options['label'] === 'none')) {
                            $this->config['default_form_control_class'] .= ' ' . $this->config['default_no_label_class'];
                        }

                        $this->print_string .= $this->_preElm();
                        $this->print_string .= $this->_label($link_to_input);
                        $this->print_string .= $this->_preInput();

                        $this->config['default_form_control_class'] = $default_form_control_class;

                        $all_elm_options = $this->elm_options;

                        foreach ($all_elm_options['options'] as $elm_suboptions) {
                            $this->elm_options = $elm_suboptions;
                            $this->elm_options['name'] = $all_elm_options['name'];
                            $this->elm_options['id'] = $all_elm_options['id'];

                            // Set value as label if no label is set
                            array_key_exists('label', $this->elm_options) || $this->elm_options['label'] = $this->elm_options['value'];

                            $label_class = substr($this->func, 5) . '-inline';
                            array_key_exists('disabled', $this->elm_options) && $label_class .= ' disabled';

                            $this->print_string .= '<label class="' . $label_class . '">';
                            $this->print_string .= $this->_buildInput(FALSE);
                            $this->print_string .= ($this->elm_options['label'] === '') ? '&nbsp;' : $this->elm_options['label'] . '</label>'; // Place a nbps to keep the radio button / checkbox aligned with the main label
                        }

                        $this->print_string .= $this->_postInput();
                        $this->print_string .= $this->_postElm();

                        $this->elm_options = $all_elm_options;
                        break;
                    default:
                        $this->print_string .= $this->_preElm();
                        $this->print_string .= $this->_label();
                        $this->print_string .= $this->_buildInput();
                        $this->print_string .= $this->_postElm();
                        break;
                }
            }
        }
        return $this->squishHTML($this->print_string);
    }

    function changePreBuilt(&$pre_built, $id, $vals_ary) 
    {
        foreach ($pre_built as $k => $v) {
            if ($v['id'] == $id) {
                $pre_built[$k] = array_merge($pre_built[$k], $vals_ary);
                break;
            }
        }
        return;
    }

    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function autoDbToOptions($ary, $custom_options = array()) 
    {

        $options = array();

        foreach ($ary as $k => $v) {
            $elm_options = array(
                'id' => $k,
                'value' => $v
            );

            /*
             * TODO: this should be put in the options. It is suited specificaly for my database
             * configuration and my practices - which include having a 'id', 'modified', 'created', and 'active'
             * column in *ALL* databases - as well as some specific data.
             *
             * NOTE: This function will likely see a lot of change to make sure it is working and/or re-built
             * 'the right way'
             */
            if ($this->isJson($v)) {
                $elm_options['type'] = 'json';
            } else {
                /* the key contains 'date' - if it does lets assume that it should be a date */
                if (strpos(strtolower($k), 'date') !== FALSE) {
                    $k = 'date';
                }
                switch ($k) {
                    case 'id':
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'date':
                        $elm_options['type'] = 'date';
                        break;
                    case 'modified':
                    case 'created':
                        $elm_options['type'] = 'date';
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'active':
                        $elm_options['type'] = 'dropdown';
                        $elm_options['options'] = array(
                            '1' => 'Active',
                            '0' => 'De-Active'
                        );
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'log':
                        $elm_options['type'] = 'json';
                        break;
                }
            }

            /* We need to override what was 'auto' created - Note: should always be done last */
            if (isset($custom_options) && isset($custom_options[$k])) {
                if (is_array($custom_options[$k])) {
                    $elm_options = array_merge($elm_options, $custom_options[$k]);
                }
            }

            if (!(isset($custom_options) && isset($custom_options[$k]) && !is_array($custom_options[$k]) && $custom_options[$k] == 'unset')) {
                $options[] = $elm_options;
            }
        }

        return $options;
    }

     
}