<?php

namespace Models\CF7;

use Models\UserModel;
use Models\CF7\FormModel;
class EntryMetaModel
{   
    private $post_type_entry ;
    
    private $post_meta_table;
    
    public function __construct()
    {
        $this->post_type_entry = "flamingo_inbound";
        
        $this->post_meta_table = "postmeta";
    }

    /**
	 * Get entry_meta by entry ID
     * 
     * @return array
	 */
    public function entryMetaByEntryID($entry_id)
    {    
        $post = new \Flamingo_Inbound_Message( $entry_id );
        
        if ( empty( $post->channel ) ) {
            return [];
        }

        $form = (new FormModel())->formByChannel($post->channel);

        $form_id = null;
        if (!empty($form)){
            $form_id = $form->ID;
        }

        $items = [];
        
        foreach ( (array) $post->fields as $key => $value ){
            $item = [];

            $item['id'] = null;
            $item['form_id'] = $form_id;
            $item['entry_id'] = $entry_id;
            $item['meta_key'] = null;
            $item['meta_value'] = $value;
            $item['type'] = "text";
            $item['label'] = $key;
            $items[] = $item;
        }

        return $items;
    }

    /**
	 * Get entry_meta by entry ID
     * 
     * @return array
	 */
    public function searchEntryMetaAnswer($answer)
    {   
        global $wpdb;

        $results = $wpdb->get_results("SELECT ID, post_name, meta_id, meta_key, meta_value, post_id  FROM ".$wpdb->prefix."postmeta AS wp_pm INNER JOIN ".$wpdb->prefix."posts AS wp_p ON  wp_pm.post_id = wp_p.ID WHERE wp_pm.meta_value LIKE '%$answer%' AND  wp_p.post_type='$this->post_type_entry' ");
        
        $items = []; 

        foreach($results as $key => $value){

            $post = new \Flamingo_Inbound_Message( $value->ID );
            
            $form_id = null;

            if ( !empty( $post->channel ) ) {
                
                $form = (new FormModel())->formByChannel($post->channel);

                
                if (!empty($form)){
                    $form_id = $form->ID;
                }
            }

            $item = [];

            $item['id'] = $value->meta_id;
            $item['form_id'] = $form_id;
            $item['entry_id'] = $value->ID;
            $item['meta_key'] = null;
            $item['meta_value'] = $value->meta_key;
            $item['type'] = "text";
            $item['label'] = $value->meta_value;

            $items[] = $item;
        }

        return $items;
    }
    
    
}
