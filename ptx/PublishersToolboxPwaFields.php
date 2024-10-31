<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * The fields builder plugin class.
     *
     * This class is used to build common fields quickly and easily.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaFields {
        
        /**
         * The ID of this plugin.
         *
         * @access private
         * @var string $pluginName The ID of this plugin.
         *
         * @since 2.0.0
         */
        public $pluginName;
        
        /**
         * The version of this plugin.
         *
         * @access private
         * @var string $pluginVersion The current version of this plugin.
         *
         * @since 2.7.3
         */
        private $pluginVersion;
        
        /**
         * The options array.
         *
         * @access public
         * @var string $optionsReturn Returns the options data to use in field.
         *
         * @since 2.0.0
         */
        public $optionsReturn;
        
        /**
         * The field message fro premium version.
         *
         * @access public
         * @var string $optionsReturn Returns the message.
         *
         * @since 2.0.4
         */
        public $message;
        
        /**
         * Initialize the options to build the fields.
         *
         * @param $pluginName
         * @param $pluginVersion
         *
         * @since 2.0.0
         */
        public function __construct($pluginName, $pluginVersion) {
            $this->pluginName    = $pluginName;
            $this->pluginVersion = $pluginVersion;
            
            $pluginGlobal        = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $this->optionsReturn = $pluginGlobal->getPluginOptions();
            
            $this->message = 'This is a Premium feature.';
        }
        
        /**
         * Standard text field, use this as basis for other fields.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function textField(
            $title, $name, $group = '', $default = '', $options = [
            'description' => '',
            'placeholder' => '',
            'validate'    => false,
            'sub'         => '',
            'type'        => 'text',
            'status'      => false,
        ]
        ) {
            $dataValue     = $this->getOptionsReturn($name, $group, $options);
            $fieldName     = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck  = isset($options['status']) && $options['status'];
            $validateCheck = isset($options['validate']) && $options['validate'];
            
            /**
             * Field label.
             */
            if (isset($options['type']) && $options['type'] === 'hidden') {
                $htmlBuild = '';
            } else {
                $htmlBuild = '<label
                title="' . $title . '"
                for="' . $name . '"
                ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : '') . '>
                ' . $title . '
                ' . ($validateCheck ? '<span class="is-req">*</span>' : '') . ' </label>';
            }
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<input
            type="' . (isset($options['type']) ? $options['type'] : 'text') . '"
            id="' . $name . '"
            name="' . $fieldName . '"
            value="' . (!empty($dataValue) ? $dataValue : $default) . '"
            ' . (isset($options['placeholder']) ? 'placeholder="' . $options['placeholder'] . '" ' : '') . ($premiumCheck ? ' readonly' : '') . '>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            
            $htmlBuild .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Textarea field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function textBoxField(
            $title, $name, $group = '', $default = '', $options = [
            'description' => '',
            'placeholder' => '',
            'validate'    => false,
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue     = $this->getOptionsReturn($name, $group, $options);
            $fieldName     = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck  = isset($options['status']) && $options['status'];
            $validateCheck = isset($options['validate']) && $options['validate'];
            
            /**
             * Field label.
             */
            $htmlBuild = '<label
            title="' . $title . '"
            for="' . $name . '"
            ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : '') . '>
            ' . $title . '
            ' . ($validateCheck ? '<span class="is-req">*</span>' : '') . '</label>';
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<textarea
            id="' . $name . '"
            name="' . $fieldName . '"
            ' . ($premiumCheck ? ' readonly' : '') . '>' . (!empty($dataValue) ? $dataValue : $default) . '</textarea>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * WYSIWYG field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function wysiwygField(
            $title, $name, $group = '', $default = '', $options = [
            'description' => '',
            'placeholder' => '',
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue    = $this->getOptionsReturn($name, $group, $options);
            $fieldName    = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck = isset($options['status']) && $options['status'];
            
            
            /**
             * Build field html.
             */
            $htmlBuild = wp_editor((!empty($dataValue) ? $dataValue : $default), $name, [
                'theme_advanced_buttons1' => 'bold, italic',
                'media_buttons'           => false,
                'textarea_rows'           => 8,
                'tabindex'                => 4,
                'textarea_name'           => $fieldName,
            ]);
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= $title ? '<div class="is-desc">' . $title . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>';
            
            return $htmlBuild;
        }
        
        /**
         * Select field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param array $values string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function selectField(
            $title, $name, $group = '', $values = ['select'], $options = [
            'description' => '',
            'placeholder' => '',
            'validate'    => false,
            'default'     => '',
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue     = $this->getOptionsReturn($name, $group, $options);
            $fieldName     = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck  = isset($options['status']) && $options['status'];
            $validateCheck = isset($options['validate']) && $options['validate'];
            
            /**
             * Field label.
             */
            $htmlBuild = '<label
            title="' . $title . '"
            for="' . $name . '"
            ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : '') . '>
            ' . $title . '
            ' . ($validateCheck ? '<span class="is-req">*</span>' : '') . '</label>';
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<select id="' . $name . '" name="' . $fieldName . '" ' . ($premiumCheck ? ' class="is-disabled" readonly' : '') . '>';
            
            $htmlBuild .= '<option value="">' . (isset($options['placeholder']) ? $options['placeholder'] : 'Select') . '</option>';
            sort($values);
            foreach ($values as $value) {
                if (!empty($dataValue) && $dataValue === $value) {
                    $checkOption = 'selected';
                } elseif (empty($dataValue) && isset($options['default']) && $options['default'] === $value) {
                    $checkOption = 'selected';
                } else {
                    $checkOption = '';
                }
                $htmlBuild .= '<option value="' . $value . '" ' . $checkOption . '>' . ucwords($value) . '</option>';
            }
            $htmlBuild .= '</select>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Checkbox field.
         *
         * Option: eventClass - Used to attach jQuery operators to field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param int $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkboxField(
            $title, $name, $group = '', $default = 0, $options = [
            'description' => '',
            'eventClass'  => '',
            'checked'     => '',
            'sub'         => '',
            'status'      => false,
            'message'     => '',
        ]
        ) {
            $dataValue    = $this->getOptionsReturn($name, $group, $options);
            $fieldName    = $this->getOptionsReturn($name, $group, $options, 'field');
            $defaultCheck = isset($options['checked']) && !empty($dataValue) ? 'checked="checked"' : '';
            $premiumCheck = isset($options['status']) && $options['status'];
            $eventClass   = (isset($options['eventClass']) && $options['eventClass'] ? ' ' . $options['eventClass'] : '');
            
            /**
             * Field label.
             */
            $messageTip = isset($options['message']) && !empty($options['message']) ? $options['message'] : $this->message;
            $htmlBuild  = '<label title="' . $title . '" for="' . $name . '" ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $messageTip . '</b>"' : '') . '>';
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<input
            type="checkbox"
            name="' . $fieldName . '"
            value="' . $default . '"
            id="' . $fieldName . '"
            class="switch' . $eventClass . '"
                ' . (!empty($dataValue) ? checked($dataValue, $default, false) : $defaultCheck) . '
                ' . ($premiumCheck ? ' readonly' : '') . (!empty($messageTip && $premiumCheck) ? ' disabled' : '') . '>';
            
            $htmlBuild .= ' ' . $title . '</label>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck && $messageTip ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Radiobox Field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param int $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function radioBoxField(
            $title, $name, $group = '', $default = 0, $options = [
            'description' => '',
            'eventClass'  => '',
            'checked'     => '',
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue    = $this->getOptionsReturn($name, $group, $options);
            $fieldName    = $this->getOptionsReturn($name, $group, $options, 'field');
            $defaultCheck = isset($options['checked']) && !empty($dataValue) ? 'checked="checked"' : '';
            $premiumCheck = isset($options['status']) && $options['status'];
            
            /**
             * Build field html.
             */
            $htmlBuild = '<input
            type="radio"
            id="' . $name . '-' . $default . '"
            name="' . $fieldName . '"
            value="' . $default . '"
             ' . (!empty($dataValue) ? checked($dataValue, $default, false) : $defaultCheck) . '
             ' . ($premiumCheck ? ' readonly disabled class="is-disabled"' : '') . '>';
            
            /**
             * Field label.
             */
            $htmlBuild .= '<label
            for="' . $name . '-' . $default . '"
            title="' . $title . '"
            ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : '') . '>
            ' . $title . '</label>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Color Picker Field.
         *
         * @param $title string The input title and label.
         * @param $name string The input name.
         * @param $group string Group fields together.
         * @param $default string The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function colorPickerField(
            $title, $name, $group = '', $default = '', $options = [
            'description' => '',
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue    = $this->getOptionsReturn($name, $group, $options);
            $fieldName    = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck = isset($options['status']) && $options['status'];
            
            /**
             * Field label.
             */
            $htmlBuild = '<label
            title="' . $title . '"
            for="' . $name . '"
            ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : '') . '>
            ' . $title . '</label>';
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<input
            type="text"
            id="' . $name . '"
            name="' . $fieldName . '"
            value="' . (!empty($dataValue) ? $dataValue : $default) . '"
            class="minicolors' . ($premiumCheck ? ' is-disabled' : '') . '"
            data-defaultValue="' . $default . '"
            maxlength="11"
             ' . ($premiumCheck ? ' readonly' : '') . '>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Slider field.
         *
         * @param string $title The input title and label.
         * @param string $name The input name.
         * @param string $group Group fields together.
         * @param int $default The default value if any.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function sliderField(
            $title, $name, $group = '', $default = 90, $options = [
            'description' => '',
            'min'         => 0,
            'max'         => 10,
            'append'      => '',
            'sub'         => '',
            'status'      => false,
        ]
        ) {
            $dataValue    = $this->getOptionsReturn($name, $group, $options);
            $fieldName    = $this->getOptionsReturn($name, $group, $options, 'field');
            $premiumCheck = isset($options['status']) && $options['status'];
            
            /**
             * Field label.
             */
            $htmlBuild = '<label
            title="' . $title . '"
            for="' . $name . '"
            ' . ($premiumCheck ? 'class="tip is-disabled" data-tt="<b>' . $this->message . '</b>"' : 'class="sliderLabel"') . '>
            ' . $title . ':</label>';
            
            /**
             * Build field html.
             */
            $htmlBuild .= '<input
            type="text"
            name="' . $fieldName . '"
            value="' . (!empty($dataValue) ? $dataValue : $default) . '"
            class="sliderField' . ($premiumCheck ? ' is-disabled' : '') . '"
            id="' . $name . '"
            data-min="' . $options['min'] . '"
            data-max="' . $options['max'] . '"
            data-default="' . (!empty($dataValue) ? $dataValue : $default) . '"
            data-step="' . $options['step'] . '"
            ' . (isset($options['append']) ? 'data-append="' . $options['append'] . '"' : '') . '
            readonly="readonly"
             ' . ($premiumCheck ? ' readonly' : '') . '>' . ($options['append'] ?? '');
            
            $htmlBuild .= '<div class="slider-pt-' . $name . '-ui toolbox-slider"></div>';
            
            /**
             * Description.
             */
            $premiumDesc = ($premiumCheck ? ' (Premium Only)' : '');
            $htmlBuild   .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . ' ' . $premiumDesc . '</div>' : '<div class="is-desc">' . $premiumDesc . '</div>');
            
            return $htmlBuild;
        }
        
        /**
         * Media Select Field.
         *
         * Uses: wp_enqueue_media()
         *
         * @param $title string The label title.
         * @param $name string The field name.
         * @param $group string Group fields together.
         * @param array $options
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function imageUploadField(
            $title, $name, $group = '', $options = [
            'description' => '',
            'placeholder' => '',
            'validate'    => false,
            'sub'         => '',
            'default'     => 'admin/assets/img/logo.png',
        ]
        ) {
            $default   = plugin_dir_url(__DIR__) . $options['default'];
            $dataValue = $this->getOptionsReturn($name, $group, $options);
            $fieldName = $this->getOptionsReturn($name, $group, $options, 'field');
            
            if (!empty($dataValue) && is_numeric($dataValue)) {
                $src   = wp_get_attachment_url($dataValue);
                $value = $dataValue;
            } else if (!empty($dataValue)) {
                $src   = $dataValue;
                $value = $dataValue;
            } else {
                $src   = $default;
                $value = '';
            }
            
            $htmlBuild = '<label title="' . $title . '">' . $title . ' ' . (isset($options['validate']) && $options['validate'] ? '<span class="is-req">*</span></label>' : '') . '</label>';
            $htmlBuild .= (isset($options['description']) ? '<div class="is-desc">' . $options['description'] . '</div><br>' : '');
            $htmlBuild .= '<div class="pwa-selected-image upload_image_button"><img data-src="' . $default . '" src="' . $src . '" alt="' . $title . '"></div>';
            $htmlBuild .= '<div>';
            $htmlBuild .= '<input type="hidden" name="' . $fieldName . '" id="' . $name . '" value="' . $value . '" />';
            $htmlBuild .= '<button type="button" class="upload_image_button btn">Upload / Select</button>';
            $htmlBuild .= ' <button type="button" class="remove_image_button btn is-small is-icon is-destructive">';
            $htmlBuild .= '<span class="dashicons dashicons-post-trash"></span></button>';
            $htmlBuild .= '</div>';
            
            return $htmlBuild;
        }
        
        /**
         * Build field names and return data
         *
         * @param string $name Field Name
         * @param string $group Field Group
         * @param array $options Field Sub Group
         * @param string $type Default is data
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function getOptionsReturn($name = '', $group = '', $options = [], $type = 'data') {
            $dataValue = '';
            $fieldName = '';
            
            /**
             * Field naming setup.
             */
            if (empty($group) && isset($name)) {
                $dataValue = isset($this->optionsReturn[$name]) ? $this->optionsReturn[$name] : '';
                $fieldName = $name;
            } elseif (!empty($group) && empty($options['sub'])) {
                $dataValue = isset($this->optionsReturn[$group][$name]) ? $this->optionsReturn[$group][$name] : '';
                $fieldName = $group . '[' . $name . ']';
            } elseif (!empty($options['sub'])) {
                $dataValue = isset($this->optionsReturn[$group][$options['sub']][$name]) ? $this->optionsReturn[$group][$options['sub']][$name] : '';
                $fieldName = $group . '[' . $options['sub'] . ']' . '[' . $name . ']';
            }
            
            if ($type === 'data') {
                /**
                 * Data value return on Sidebar Meta fields.
                 */
                if (isset($options['meta'])) {
                    global $post;
                    $postId        = $post->ID;
                    $metaDataValue = get_post_meta($postId, $this->pluginName, true);
                    
                    return (isset($metaDataValue[$name]) ? $metaDataValue[$name] : '');
                }
                
                return $dataValue;
            }
            
            return $fieldName;
        }
    }
