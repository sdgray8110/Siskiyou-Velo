<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/php-activerecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/php-activerecord/config.inc.php';

class postMigration {
    public function __construct() {
        $this->posts = Posts::all();
        $this->newPosts = NewPost::all();
    }

    public static function add_author_id() {
        $posts = Posts::all();

        $authors = array(
            'Spencer Gray' => 155,
            ' Spencer Gray' => 155,
            'Mary Burgess & Mike Smith'=> 392,
            'Edgar & Karen Hee' => 60,
            'Gary Shaff' => 205,
            'Phil Gagnon' => 159,
            'Buck Eichler' => 1328
        );

        foreach ($posts as $post) {
            $author_id = $authors[$post->author];

            $post->author_id = $author_id;
            $post->save();
        }
    }

    public function migrate_posts() {
        $posts = $this->posts;
        $i = 1;

        foreach ($posts as $post) {
            $data = $this->set_post_data($post,$i);
            $relData = $this->set_relational_data($i);

            NewPost::create($data);
            TermRel::create($relData);

            $i ++;
        }
    }

    private function set_post_data($post,$i) {
        return array(
            'post_author' => $this->get_wp_user_id($post->author_id),
            'post_date' => $post->timestamp,
            'post_date_gmt' => $post->timestamp,
            'post_content' => $post->body,
            'post_title' => $post->header,
            'post_status' => 'publish',
            'comment_status' => 'open',
            'ping_status' => 'open',
            'post_name' => $this->gen_slug($post->header),
            'post_modified' => $post->timestamp,
            'post_modified_gmt' => $post->timestamp,
            'post_parent' => 0,
            'menu_order' => 0,
            'post_type' => 'post',
            'guid' => 'http://www.siskiyouvelo.org/wordpress/?p=' . $i
        );
    }

    private function set_relational_data($i) {
        return array(
            'object_id' => $i,
            'term_taxonomy_id' => 1,
            'term_order' => 0
        );
    }

    private function get_wp_user_id($legacy_id) {
        $args = array(
            'conditions' => array(
                'meta_key = ? AND meta_value = ?',
                'legacy_id',
                $legacy_id
            )
        );

        $meta = UserMeta::find($args);

        return $meta->user_id;
    }

    private function gen_slug($str){
        # special accents
        $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
        $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
    }

}