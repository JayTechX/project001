<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
	}

	public function index(){redirect(base_url());}


	// Fetch all states
	function fetch_states(){
		if( $this->input->is_ajax_request() ){
			$states = $this->user->get_states();
			header('Content-type: text/json');
			header('Content-type: application/json');
			echo json_encode($states);
			exit;
		}else{
			redirect( base_url() );
		}
	}


	// function to get all areas base on
	// the user selected state
	function fetch_areas(){
		if ($this->input->is_ajax_request()) {
			$sid = $this->input->get('sid');
			$areas = $this->user->get_area($sid);
			header('Content-type: text/json');
			header('Content-type: application/json');
			echo json_encode($areas);
			exit;
		}else{
			redirect(base_url());
		}
	}


	// Function to make an auto complete search
	function search_complete(){
		if( $this->input->is_ajax_request() && $this->input->post()) {
			$search = cleanit( $this->input->post('search') );
			$results = $this->product->search_query( $search );
			// var_dump( $results );
			$output = array();
			foreach( $results as $result ){
				$res['image_path'] = base_url('data/products/' . $result->id .'/' . $result->image_name);
				$res['product_name'] = $result->product_name;
				$res['url'] = urlify( $result->product_name, $result->id );
				$price = ( !empty( $result->discount_price) ) ? $result->discount_price .'<strike> ' .$result->sale_price. '</strike>' :  $result->sale_price;
				$res['price'] = $price;
				array_push( $output, $res );
			}
			$json = json_encode($output );
			header('Content-type: text/json');
			header('Content-type: application/json');
			echo json_encode($json);
			exit;
		}else{
			redirect( base_url() );
		}
	}

}