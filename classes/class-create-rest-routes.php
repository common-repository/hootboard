<?php
// Creates plugin's action APIs endpoints.
class HB_Create_Rest_Routes
{
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'create_rest_routes' ] );
    }

    public function create_rest_routes() {
        register_rest_route( 'hootboard/v1', '/create', [
            'methods' => 'POST',
            'callback' => [ $this, 'create_page' ],
            'permission_callback' => [ $this, 'create_page_permission' ],
        ]);
        register_rest_route( 'hootboard/v1', '/clear', [
            'methods' => 'DELETE',
            'callback' => [ $this, 'clear' ],
            'permission_callback' => [ $this, 'clear_permission' ],
        ]);
    }

    public function create_page($request)
    {
        $config = json_decode($request->get_body());
        // Looking for page created with this shortcode.
        $page = get_posts([
            'name'      => 'hootboard',
            'post_type' => 'page'
        ]);

        update_option('hb_configs', json_encode($config));

        // If found, don't create a new page. Just return the page ID.
        if ($page) {
            $response = [
                'isSuccess' => true,
                'isNewPage' => false,
                'newPageURL' => get_permalink($page[0]->ID)
            ];
        } else {
            $newPageData = [
                'post_title' => 'HootBoard - ' . $config->boardName,
                'post_content' => "[hootboard]",
                'post_status' => 'publish',
                'post_name' => 'hootboard',
                'post_type' => 'page',
            ];

            // Without this sandwich function, the <iframe> tag will not be added to the page.
            // kses_remove_filters();
            $pageId = wp_insert_post($newPageData);
            // kses_init_filters();

            $response = [
                'isSuccess' => true,
                'isNewPage' => true,
                'newPageURL' => get_permalink($pageId)
            ];
        }

        return rest_ensure_response( $response );
    }

    public function create_page_permission()
    {
        return true;
    }

    public function clear($request)
    {
        delete_option('hb_configs');

        $response = [
            'isSuccess' => true,
        ];

        return rest_ensure_response( $response );
    }

    public function clear_permission()
    {
        return true;
    }
}

new HB_Create_Rest_Routes();
