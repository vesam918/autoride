<?php
namespace AutoRide\Theme\Core;

class Post {
    public function getPost() {
        global $post;
        
        if (!isset($post)) {
            return false;
        }

        // Handle different post types
        switch (get_post_type($post)) {
            case 'page':
                return $this->getPageData($post);
            case 'post':
                return $this->getPostData($post);
            default:
                return $this->getDefaultData($post);
        }
    }

    private function getPageData($post): object {
        $data = new \stdClass();
        $data->post = $post;
        $data->meta = get_post_meta($post->ID);
        $data->thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
        $data->template = get_page_template_slug($post->ID);
        return $data;
    }

    private function getPostData($post): object {
        $data = new \stdClass();
        $data->post = $post;
        $data->meta = get_post_meta($post->ID);
        $data->thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
        $data->categories = get_the_category($post->ID);
        $data->tags = get_the_tags($post->ID);
        return $data;
    }

    private function getDefaultData($post): object {
        $data = new \stdClass();
        $data->post = $post;
        $data->meta = get_post_meta($post->ID);
        $data->thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
        return $data;
    }

    public static function getPostMeta($post_id, $key = '', $single = true) {
        if (empty($key)) {
            return get_post_meta($post_id);
        }
        return get_post_meta($post_id, $key, $single);
    }

    public static function updatePostMeta($post_id, $meta_key, $meta_value) {
        return update_post_meta($post_id, $meta_key, $meta_value);
    }

    public static function deletePostMeta($post_id, $meta_key) {
        return delete_post_meta($post_id, $meta_key);
    }
}
