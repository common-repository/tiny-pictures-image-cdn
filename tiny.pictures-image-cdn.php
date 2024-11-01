<?php
/**
 * @package tiny.pictures-image-cdn
 * @version 1.1.0
 */
/*
Plugin Name: tiny.pictures Image CDN
Plugin URI: http://wordpress.org/plugins/tiny-pictures-image-cdn/
Description: Scales and optimizes your images using the tiny.pictures image processing service in the cloud and delivers them through worldwide CDN nodes.
Author: Tiny Tools Microservices GmbH
Version: 1.1.0
Author URI: https://tinytools.io/
*/

namespace TinyPictures;

require_once 'TinyPictures.php';

class TinyPicturesWordpress extends TinyPictures {
    protected $packageName = 'tiny.pictures-image-cdn';
    protected $packageNameHuman = 'tiny.pictures Image CDN';
    protected $optionName = 'tinyPictures';
    protected $option = [];
    protected $homeUrl = '';

    public function __construct() {
        $this->option = get_option($this->optionName);
        $this->homeUrl = get_home_url() . '/';

        if ($this->option['user']) {
            parent::__construct([
                'user' => $this->option['user'],
                'namedSources' => isset($this->option['source']) && $this->option['source'] ? [$this->option['source'] => $this->homeUrl] : null
            ]);
            $this->registerFilters();
        }
        $this->registerAdmin();
    }

    protected function registerFilters() {
        add_filter(
            'wp_calculate_image_srcset',
            function($srcset, $size_array, $image_src, $image_meta, $attachment_id) {
                foreach ($srcset as &$src) {
                    if ($src['descriptor'] === 'w') {
                        $src['url'] = $this->url(
                            $this->homeUrl . 'wp-content/uploads/' . $image_meta['file'],
                            ['width' => $src['value']]
                        );
                    }
                }
                return $srcset;
            },
            PHP_INT_MAX,
            5
        );
    }

    protected function registerAdmin() {
        add_action('admin_menu', function() {
            add_options_page(
                'tiny.pictures settings',
                'tiny.pictures',
                'manage_options',
                $this->optionName,
                function() {
                    // check user capabilities
                     if (!current_user_can( 'manage_options' ) ) {
                        return;
                     }
                     include 'settings.php';
                }
            );
        });

        add_action('admin_init', function() {
            add_option($this->optionName, []);
            register_setting(
                $this->optionName,
                $this->optionName
            );
        
            add_settings_section(
                $this->optionName . '_user',
                'User',
                function($args) {
                    ?>
                    <?php
                },
                $this->optionName
            );
        
            add_settings_field(
                $this->optionName . '_user_user',
                'User name',
                function($args) {
                    echo '<input type="text" name="' . $this->optionName . '[user]" value="' . (isset($this->option['user']) ? esc_attr($this->option['user']) : '') . '" /><p class="description">If you don\'t have an account, you can register for free ' . ($this->option['user'] ? 'at <a href="https://tiny.pictures/#register">https://tiny.pictures/#register</a>' : 'in the form below') . '.<p>';
                },
                $this->optionName,
                $this->optionName . '_user',
                []
            );
            add_settings_field(
                $this->optionName . '_user_source',
                'Source',
                function($args) {
                    echo '<input type="text" name="' . $this->optionName . '[source]" value="' . (isset($this->option['source']) ? esc_attr($this->option['source']) : '') . '" /><p class="description">Name of the source that points to <a href="' . $this->homeUrl . '">' . $this->homeUrl . '</a>. See your <a href="https://tiny.pictures/dashboard/sources">dashboard</a> to create or edit.</p>';
                },
                $this->optionName,
                $this->optionName . '_user',
                []
            );
        });

        add_filter(
            'plugin_action_links',
            function ($actions, $file, $plugin_data, $context) {
                if ($file === $this->packageName . '/' . $this->packageName . '.php') {
                    $actions['settings'] = '<a href="options-general.php?page=tinyPictures">Settings</a>';
                    $actions['website'] = '<a href="https://tiny.pictures/?utm_source=wordpress-plugin&utm_medium=referral" target="_blank" rel="noopener">Visit website</a>';
                }
                return $actions;
            },
            10,
            4
        );

        if (!$this->option['user']) {
            add_action(
                'admin_notices',
                function() {
                    ?>
                    <div class="notice notice-warning is-dismissible">
                        <p>The <?php echo $this->packageNameHuman; ?> plugin is activated but not configured properly. Please update your <a href="options-general.php?page=tinyPictures">settings</a> to enable it.</p>
                    </div>
                    <?php
                }
            );
        }
    }
}

$tinyPicturesWordpress = new TinyPicturesWordpress();
