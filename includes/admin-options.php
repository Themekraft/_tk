<?php

class _tk_SettingPage
{
    const DEFAULT_DATA_GROUP = '_tk_option_data_group';
    const DEFAULT_DATA_ITEM = '_tk_options';

    public $id;
    public $title;
    public $menu_title;
    public $capability;
    public $content_callback;

    public $sections = array();

    public function render()
    {
        add_action( 'admin_menu', array( $this, 'add_setting_page' ) );
        add_action( 'admin_init', array( $this, 'setting_page_init' ) );
    }

    public function add_setting_page()
    {
        // This page will be under "Settings"
        add_options_page(
            $this->title, 
            $this->menu_title, 
            $this->capability, 
            $this->id, 
            array( $this, 'create_settings_page' )
        );
    }

    public function create_settings_page()
    {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php $this->title; ?></h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields(self::DEFAULT_DATA_GROUP);   
                do_settings_sections($this->id);
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    public function setting_page_init()
    {
        register_setting(
            self::DEFAULT_DATA_GROUP, // Option group
            self::DEFAULT_DATA_ITEM, // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        foreach ($this->sections as $section) {
            add_settings_section(
                $section->id, // ID
                $section->title, // Title
                array($section, 'create_content' ), // Callback
                $this->id // Page
            );

            foreach ($section->settings as $setting) {
                add_settings_field(
                    $setting->id, // ID
                    $setting->title, // Title 
                    array($setting, 'create_content' ), // Callback
                    $this->id, // Page
                    $section->id, // Section,
                   $setting->args // Arguments     
                );
            }
        }
    }

    public function sanitize($input)
    {
        $result_input = array();

        foreach ($this->sections as $section) {
            foreach ($section->settings as $setting) {
                $result_input = $setting->sanitize($input, $result_input);
            }
        }

        return $result_input;
    }
}

class _tk_SettingSection
{
    public $id;
    public $title;
    public $content_callback;
    public $settings = array();

    public function create_content()
    {
        print 'Enter your settings below:';
    }
}

class _tk_SettingItem
{
    public $id;
    public $title;
    public $content_callback;
    public $sanitize_callback;
    public $args;

    const DEFAULT_DATA_ITEM = '_tk_options';

    public function create_content($args)
    {
        $this->options = get_option(self::DEFAULT_DATA_ITEM);

        printf(
            '<input type="text" id="%s" name="%s[%s]" value="%s" />', $this->id, self::DEFAULT_DATA_ITEM, $this->id,
            isset( $this->options[$this->id] ) ? esc_attr( $this->options[$this->id]) : ''
        );
    }

    public function sanitize($input, $result_input)
    {
        if( isset( $input[$this->id] ) )
                $result_input[$this->id] = sanitize_text_field($input[$this->id] );

        return $result_input;
    }
}

class _tk_Settings
{
    const DEFAULT_PAGE_TITLE = '_tk Settings';
    const DEFAULT_PAGE_MENU_TITLE = '_tk Settings';
    const DEFAULT_PAGE_CAPABILITY = 'manage_options';
    const DEFAULT_PAGE_MENU_SLUG = '_tk-setting-admin';

    const DEFAULT_SECTION_ID = '_tk-default-section';
    const DEFAULT_SECTION_TITLE = 'General';

    const DEFAULT_DATA_ITEM = '_tk_options';


    private static $pages = array();

    public static function add_page($page_id, $page_title = NULL, $menu_title = NULL, $content_callback = NULL, $capability = NULL)
    {
        $page = new _tk_SettingPage();

        $page->id = $page_id;
        $page->title = isset($page_title) ? $page_title : $page_id;
        $page->menu_title = isset($menu_title) ? $menu_title : $page->title;
        $page->capability = isset($capability) ? $capability : self::DEFAULT_PAGE_CAPABILITY;
        $page->content_callback = $content_callback;

        self::$pages[$page_id] = $page;
    }

    public static function add_section($section_id, $section_title = NULL, $page_id = NULL, $content_callback = NULL)
    {
        $page = self::resolve_page($page_id);

        $section = new _tk_SettingSection();
        $section->id = $section_id;
        $section->title = isset($section_title) ? $section_title : $section_id;
        $section->content_callback = $content_callback;

        $page->sections[$section_id] = $section;
    }

    // $tk_Settings.add_section('section_id', 'My Section', 'page_id', $callback);

    public static function add_setting($id, $title = NULL, $section_id = NULL, $page_id = NULL, $sanitize_callback = NULL, $content_callback = NULL, $args = NULL)
    {
        $page = self::resolve_page($page_id);
        $section = self::resolve_section($page, $section_id);

        $setting = new _tk_SettingItem();
        $setting->id = $id;
        $setting->title = isset($title) ? $title : $id;
        $setting->sanitize_callback = $sanitize_callback;
        $setting->content_callback = $content_callback;
        $setting->args = $args;

        $section->settings[$id] = $setting;
    }

    public static function get_value($setting_id)
    {
        $options = get_option(self::DEFAULT_DATA_ITEM);

        if (!isset($options))
            return '';

        return $options[$setting_id];
    }

    public static function render()
    {
        foreach (self::$pages as $page) {
            $page->render();
        }
    }

    static function resolve_page($page_id)
    {
        if (!isset($page_id))
        {
            if ( empty(self::$pages) ) {
                $page_id = self::DEFAULT_PAGE_MENU_SLUG;
            } else {
                reset(self::$pages);
                $page_id = current(array_keys(self::$pages));
            }
        }

        if (isset(self::$pages[$page_id]))
            return self::$pages[$page_id];

        self::add_page(self::DEFAULT_PAGE_MENU_SLUG, self::DEFAULT_PAGE_TITLE, self::DEFAULT_PAGE_MENU_TITLE, self::DEFAULT_PAGE_CAPABILITY);

        return self::$pages[self::DEFAULT_PAGE_MENU_SLUG];
    }

    static function resolve_section($page, $section_id)
    {
        if (!isset($section_id))
        {
            if ( empty($page->sections) ) {
                $section_id = self::DEFAULT_SECTION_ID;
            } else {
                reset($page->sections);
                $section_id = current(array_keys($page->sections));
            }
        }

        if (isset($page->sections[$section_id]))
            return $page->sections[$section_id];

        self::add_section(self::DEFAULT_SECTION_ID, self::DEFAULT_SECTION_TITLE, $page->id);

        return $page->sections[$section_id];
    }
}