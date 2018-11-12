<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('text');
	}


	public function index()
	{
		// $this->output->cache(60);
		$uri = $this->uri->segment(2);
		$index = substr($uri, strrpos($uri, '-') + 1);
		// sanitize
		if (!is_numeric(cleanit($index))) redirect(base_url());
		$page_data['product'] = $this->product->get_product($index);
		$page_data['var'] = $this->product->get_variation($index);
		$page_data['variations'] = $this->product->get_variations($index);
		$page_data['gallery'] = $this->product->get_gallery($index);
		$page_data['favourited'] = $this->product->is_favourited(base64_decode($this->session->userdata('logged_id')), $index);
		$page_data['likes'] = $this->product->get_also_likes($index);
		$page_data['title'] = preg_replace("/[^A-Za-z0-9]/", " ", $uri);
		$page_data['keywords'] = $page_data['title'] . ' , ' . $page_data['product']->rootcategory . ', ' . $page_data['product']->subcategory . ', ' . $page_data['product']->category . ' ,' . $page_data['product']->brand_name;
		$page_data['description'] = $this->product->get_category_detail($page_data['product']->rootcategory, 'root_category')->description;
		$page_data['profile'] = $this->user->get_profile(base64_decode($this->session->userdata('logged_id')));
		// $this->add_count($index);
        $page_data['page'] = 'product';
		$page_data['rating_counts'] = $this->product->get_rating_counts( $index );
//		var_dump($page_data['rating_counts']); exit;
        $page_data['featured_image'] = $this->product->get_featured_image( $index );
		$this->load->view('landing/product', $page_data);
	}


	// List Product Page
	public function catalog(){
		$str = $this->uri->segment(2);
		$str = preg_replace("/[^A-Za-z0-9-]/", "", cleanit($str));
		$page_data['searched'] = $str = preg_replace("/[^A-Za-z0-9]/", " ", cleanit($str)); // Convert the - to space
		if ($str == '') redirect(base_url());
		$page_data['title'] = ucwords($str);
		$features = $this->product->get_features($str);
		$output_array = array();
		foreach ($features as $feature => $values) {
			foreach ($values as $key => $value) {
				$variables = json_decode($value);
				foreach ($variables as $new_key => $new_value) {
					if (is_array($new_value)) {
						$new_value = array_map("unserialize", array_unique(array_map("serialize", $new_value)));
						foreach ($new_value as $inkey => $invalue) $output_array[$new_key][] = $invalue;
						$output_array[$new_key] = array_unique($output_array[$new_key], SORT_REGULAR);
					} else {
						$output_array[$new_key][] = $new_value;
						$output_array[$new_key] = array_unique($output_array[$new_key], SORT_REGULAR);
					}
				}
			}
		}
		// pagination
		$page = isset($_GET['page']) ? xss_clean($_GET['page']) : 0;
		if ($page > 1) $page -= 1;

		$array = array('str' => $str, 'is_limit' => false);
		$x = (array)$this->product->get_products($array, $this->input->get());
		$count = (count($x));

		$this->load->library('pagination');
		$this->config->load('pagination');
		$config = $this->config->item('pagination');
		$config['base_url'] = current_url();
		$config['total_rows'] = $count;
		$config['per_page'] = 32;
		$config["num_links"] = 5;
		$this->pagination->initialize($config);
		$page_data['features'] = $output_array;
		$array['limit'] = $config['per_page'];
		$array['offset'] = $page;
		$array['is_limit'] = true;
		$page_data['pagination'] = $this->pagination->create_links();
		$page_data['products'] = $this->product->get_products($array, $this->input->get());
		$page_data['brands'] = $this->product->get_brands($str);
		$page_data['colours'] = $this->product->get_colours($str);
		$page_data['sub_categories'] = $this->product->get_sub_categories($str);
		$page_data['profile'] = $this->user->get_profile(base64_decode($this->session->userdata('logged_id')));
		$page_data['description'] = $this->product->category_description($str);
        $page_data['page'] = 'category';
		$this->load->library('user_agent');
		if( !$this->agent->is_mobile()){
			$this->load->view('landing/category', $page_data);
		}else{
			$this->load->view('landing/mobile-category', $page_data);
		}
	}


	public function cart(){
		if ($this->input->post()) {
			// update
			$data = $this->input->post();
			if ($this->cart->update($data)) {
				$this->session->set_flashdata('success_msg', 'Your cart has been successfully updated.');
				redirect('cart');
			} else {
				$this->session->set_flashdata('error_msg', 'There was an error updating the cart');
				redirect('cart');
			}
		} else {
			$page_data['profile'] = $this->user->get_profile($this->session->userdata('logged_id'));
			$page_data['title'] = 'My cart';
			$page_data['page'] = 'cart';
			$this->load->view('landing/cart', $page_data);
		}
	}


	// remove cart
	public function remove_cart() {
		$this->cart->remove($this->uri->segment(3));
		redirect('cart');
	}

	/**
	 * @param $id - product id
	 * @return null
	 */
	function add_count($id){
		if (!empty($id)) {
			$this->load->helper('cookie');
			$check_visitor = $this->input->cookie($id, FALSE);
			// get the visitor Ip address
			$ip = $this->input->ip_address();
			if ($check_visitor == false) {
				$cookie = array(
					"name" => $id,
					"value" => $ip,
					"secure" => false
				);
				$this->input->set_cookie($cookie);
				$this->product->set_field('products', 'views', 'views+1', array('id' => $id));
			}
		}
	}

	/**
	 * @param $product_id - product id
	 * @param $user_id - user id
	 * @param $count - rating count
	 * @return null
	 */
	function add_rating()
	{
		if ($this->input->post()) {
			$status['status'] = 'error';
			$data = array(
				'product_id' => $this->input->post('product_id'),
				'user_id' => $this->input->post('user_id'),
				'rating_score' => $this->input->post('count')
			);
			// check if the user bought the product
			if ($this->product->has_bought($data['product_id'], $data['user_id'])) {
				$status['message'] = 'You need to be a verified buyer before rating.';
				echo json_encode($status);
				exit;
			}
			$id = $this->product->num_rows_count('product_rating', array('product_id' => $data['product_id'], 'user_id' => $data['user_id']));
			if ($id) {
				$this->product->product_update_data($id, array('rating_score' => $this->input->post('count')), 'product_rating');
				$status['status'] = 'success';
				echo json_encode($status);
				exit;
			}

			if (is_int($this->product->insert_data('product_rating', $data))) {
				$status['status'] = 'success';
				echo json_encode($status);
				exit;
			}
		}
		exit;
	}

	/**
	 * @param $product_id - product id
	 * @param $user_id - user id
	 * @param $title - title
	 * @param $display_name - display_name
	 * @param $content - content
	 * @return null
	 */
	function add_review(){
		if ($this->input->post()) {
			$status['status'] = 'error';
			$data = array(
				'product_id' => $this->input->post('product_id'),
				'user_id' => $this->input->post('user_id'),
				'title' => cleanit($this->input->post('title')),
				'display_name' => cleanit($this->input->post('name')),
				'content' => cleanit($this->input->post('detail')),
				'published_date' => get_now()
			);

			if (is_int($this->product->insert_data('product_review', $data))) {
				$status['status'] = 'success';
				$status['title'] = ucwords($data['title']);
				$status['detail'] = ucwords($data['content']);
				$status['name'] = ucwords($data['display_name']);
				echo json_encode($status);
				exit;
			} else {
				echo json_encode($status);
			}
		}
		exit;
	}



    // Search
    public function search(){
//        http://localhost/project001/search?category=Phones+%26+Tablets&q=sam
        if( !$this->input->get('q', true) ) redirect(base_url());

        $category = preg_replace("/[^A-Za-z0-9- ]/", "", cleanit($this->input->get('category', true)));
        $product_name = $page_data['searched'] = cleanit($this->input->get('q', true));

        $page_data['title'] = ucwords($category .' '. $product_name);
        $features = $this->product->get_features($category, $product_name);

        $feature_array = array();
        foreach ($features as $feature => $values) {
            foreach ($values as $key => $value) {
                $variables = json_decode($value);
                foreach ($variables as $new_key => $new_value) {
                    if (is_array($new_value)) {
                        $new_value = array_map("unserialize", array_unique(array_map("serialize", $new_value)));
                        foreach ($new_value as $inkey => $invalue) $feature_array[$new_key][] = $invalue;
                        $feature_array[$new_key] = array_unique($feature_array[$new_key], SORT_REGULAR);
                    } else {
                        $feature_array[$new_key][] = $new_value;
                        $feature_array[$new_key] = array_unique($feature_array[$new_key], SORT_REGULAR);
                    }
                }
            }
        }
        // pagination
        $page = isset($_GET['page']) ? xss_clean($_GET['page']) : 0;
        if ($page > 1) $page -= 1;

        $array = array('category' => $category, 'product_name' => $product_name,  'is_limit' => false);
        $x = (array)$this->product->get_search_products($array, $this->input->get());
        $count = (count($x));

        $this->load->library('pagination');
        $this->config->load('pagination');
        $config = $this->config->item('pagination');
        $config['base_url'] = current_url();
        $config['total_rows'] = $count;
        $config['per_page'] = 32;
        $config["num_links"] = 5;
        $page_data['features'] = $feature_array;
        $array['limit'] = $config['per_page'];
        $array['offset'] = $page;
        $array['is_limit'] = true;
        $page_data['pagination'] = $this->pagination->create_links();
        $page_data['products'] = $this->product->get_search_products($array, $this->input->get());
        $page_data['brands'] = $this->product->get_brands($category, $product_name);
        $page_data['colours'] = $this->product->get_colours($category, $product_name);
        $page_data['sub_categories'] = $this->product->get_sub_categories($category);
        $page_data['profile'] = $this->user->get_profile(base64_decode($this->session->userdata('logged_id')));
        $page_data['description'] = $this->product->category_description($category);
        $page_data['page'] = 'seacch';
        $this->pagination->initialize($config);
        $this->load->library('user_agent');
        if( !$this->agent->is_mobile()){
            $this->load->view('landing/search', $page_data);
        }else{
            $this->load->view('landing/mobile-search', $page_data);
        }
    }



}
