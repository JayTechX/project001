<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Frontpage extends MY_Controller {
	public function __construct(){
        parent::__construct();
    }

	public function index(){
//        $this->output->enable_profiler(TRUE);
		$page_data['title'] = 'Online shopping | Buy Electronics, Phones, Fashions in Nigeria';
		$page_data['page'] = 'homepage';
		$page_data['sliders'] = $this->product->get_results('sliders', 'image,img_link', "( status = 'active')");
        $page_data['profile'] = $this->user->get_profile($this->session->userdata('logged_id'));
        if ($this->agent->is_mobile()) {
            $page_data['main_categories'] = $this->product->get_results('categories', 'id,name,slug,title', "(pid=0)");
            //  $page_data['category_listing'] = $this->product->get_results('homepage_setting', 'id', "(status = 'active')" );
            $page_data['category_listing'] = $this->product->run_sql("SELECT h.category_id, h.content, c.name, c.slug FROM homepage_setting h JOIN categories c ON (c.id = h.category_id)
            WHERE h.status = 'active' ORDER BY h.position");
            $this->load->view('landing/mobile-home', $page_data);
        } else {

            $page_data['top_sales'] = $this->product->get_top_sales();
            $this->load->view('landing/home', $page_data);
        }
	}
}
