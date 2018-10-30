<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('text');
    }

	public function index(){
        $this->session->set_userdata('referred_from', current_url());
	    $uri = $this->uri->segment(1);
        $index = substr($uri, strrpos($uri, '-') + 1);
        // sanitize
        if( !is_numeric( cleanit($index) ) ) redirect(base_url());
        $page_data['product'] = $this->product->get_product( $index );
        $page_data['variation'] = $this->product->get_variation($index);
        $page_data['variations'] = $this->product->get_variations($index);
        $page_data['gallery'] = $this->product->get_gallery($index);
        $page_data['favourited'] = $this->product->is_favourited(base64_decode($this->session->userdata('logged_id')), $index);
        $page_data['likes'] = $this->product->get_also_likes( $index );
        $page_data['title'] = preg_replace("/[^A-Za-z0-9]/"," ", $uri );
        $this->load->view('landing/product', $page_data);
	}

    public function fav(){
        if( !$this->session->userdata('logged_in')) redirect(base_url());        
        $this->load->model('user_model');
        if($this->user_model->favourite( base64_decode($this->session->userdata('logged_id')), base64_decode($this->input->post('pid')),
            $this->input->post('action') )){
            echo true;
            exit;
        }else{
            echo false;
            exit;
        }                   
    }

    
    // List Product Page
    public function catalog(){        
        $str = $this->uri->segment(2); 
        $str = preg_replace("/[^A-Za-z0-9-]/","",cleanit($str) );
        $str = preg_replace("/[^A-Za-z0-9]/"," ",cleanit($str) ); // Convert the - to space 
        if( $str == '' ) redirect(base_url());      
        $page_data['title'] = $str;
        $features = $this->product->get_features($str)->result_array();

        $result = array();
        foreach( $features as $key => $values ){
            $json_decoded = json_decode($values['feature_value']);
            foreach ($json_decoded as $jsons) {
                $jsons = (array) $jsons;
                $obj['feature_name'] = $jsons['feature_name'];

                $obj['feature_name'][] = $jsons['feature_value'];
                

                // var_dump( $jsons );
                array_push( $result, $obj);              
                
                // if( !in_array( $jsons['feature_name'], $result )){
                //     $result['feature_name'] = $jsons['feature_name'];
                //     echo $jsons['feature_name'] .' <br />';
                // }
            }
        }
        $result = array_unique($result, SORT_REGULAR);
        var_dump( $result  );
        exit;
        $output_array = array();
        foreach($features as $feature => $values ){            
            foreach ($values as $key => $value) {
                $variables = json_decode( $value );
                foreach ($variables as $new_key => $new_value) {
                    $new_value = (array)$new_value;

                    if( is_array($new_value) ){
                        foreach ($new_value as $inkey => $invalue) {
                            $output_array[$new_key][] = $invalue;
                        }
                        $output_array[$new_key] = array_unique($output_array[$new_key], SORT_REGULAR);
                        $new_value = array_map("unserialize", array_unique(array_map("serialize", $new_value)));
                        // var_dump( $output_array);
                    }else{
                        $output_array[$new_key][] = $new_value;
                        $output_array[$new_key] = array_unique($output_array[$new_key], SORT_REGULAR);
                    }
                }
            }
        }
        // pagination
        $page = isset($_GET['page']) ? xss_clean($_GET['page']) : 0;
        if( $page > 1 ) $page -= 1;

        $array = array('str' => $str, 'is_limit' => false);
        $x = (array) $this->product->get_products( $array, $this->input->get());
        $count = (count($x));

        $this->load->library('pagination');
        $this->config->load('pagination');
        $config = $this->config->item('pagination');
        $config['base_url'] = current_url() ;
        $config['total_rows'] = $count; 
        $config['per_page'] = 50; 
        $config["num_links"] = 5;
        $this->pagination->initialize($config);
        
        // var_dump( $output_array );
        // exit;
        $page_data['features'] = $output_array;

        $array['limit'] = $config['per_page']; $array['offset'] = $page; $array['is_limit'] = true;
        $page_data['pagination'] = $this->pagination->create_links();        
        $page_data['products'] = $this->product->get_products( $array, $this->input->get() );
        $page_data['brands'] = $this->product->get_brands($str);
        $page_data['colours'] = $this->product->get_colours($str);
        $page_data['sub_categories'] = $this->product->get_sub_categories($str);

        $this->load->view('landing/category', $page_data);
    }


    public function cart(){
        $page_data['title'] = 'My cart';
        if( $this->input->post() ){
            // update
            $data = $this->input->post();
            if( $this->cart->update($data) ){
                $this->session->set_flashdata('success_msg','Your cart has been successfully updated.');
                redirect('cart');
            }else{
                $this->session->set_flashdata('error_msg','There was an error updating the cart');
                redirect('cart');
            }
        }else{
            $this->load->view('landing/cart', $page_data);
        }
    }


    public function remove_cart(){
        $this->cart->remove($this->uri->segment(3));
        redirect('cart');
    }


    public function add_to_cart(){
        $this->load->library('cart');
        $name = preg_replace("/[^A-Za-z0-9- ]/","",cleanit($this->input->post('product_name')) );
        $data = array(
            'id' => base64_decode($this->input->post('product_id')),
            'qty' => $this->input->post('quantity'),
            'price' => $this->input->post('product_price'),
            'name' => $name,
            'options' => 
                array('size' => $this->input->post('variation'), 
                    'colour' => $this->input->post('colour'),
                    'seller' => base64_decode($this->input->post('seller'))
                )                
        );

        $this->cart->insert( $data );
        echo true;        
        exit;
    }


}