<?php
// Create the plugin's settings page and menu item.
class HB_Create_Admin_Page
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_admin_menu']);
    }

    public function create_admin_menu()
    {
        $capability = 'manage_options';
        $slug = 'hootboard';

        // Sidebar menu entry.
        add_menu_page(
            // Menu item name and link.
            __('HootBoard', 'hootboard'),
            __('HootBoard', 'hootboard'),
            $capability,
            $slug,
            [$this, 'menu_page_template'],
            HB_URL . 'dist/images/menu-icon.png',
            6 // Position in the sidebar menu.
        );
    }

    public function menu_page_template()
    {
        $option = get_option('hb_configs');
        $config = $option ? $option : "null";

        // hbImagesFolderPath variable needs inside the react app to load the assets.
        // To avoid bundling the assents into the bundle.js file, we are using the plugin folder itself as the assets folder.
        echo '<div class="wrap">
            <script>
                const hbImagesFolderPath = "'.HB_URL.'dist/images/";
                const hbConfig = '.$config.';
            </script>
            <div id="hb-admin"></div>
        </div>';
    }
}

new HB_Create_Admin_Page();
