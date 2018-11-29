<?php
if(!function_exists('salt')){
 	function salt($length) {
	     $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789;:?.>,<!@#$%^&*()-_=+|';
	     $randStringLen = $length;

	     $randString = "";
	     for ($i = 0; $i < $randStringLen; $i++) {
	         $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
	     }

	     return $randString;
	}
}

if(!function_exists('generate_token')){
 	function generate_token($length) {
	     $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	     $randStringLen = $length;

	     $randString = "";
	     for ($i = 0; $i < $randStringLen; $i++) {
	         $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
	     }

	     return $randString;
	}
}

if (!function_exists('shaPassword')) {
	function shaPassword($field = "", $salt = "")  {
		if($field) {
			return hash('sha256', $field.$salt);
		}
	}
}

if (!function_exists('plushrs')) {
	function plushrs($dt, $hrs){
		$pure = strtotime($dt);
		$plus = ($pure + 60*60*$hrs);
		$newPure = date('Y-m-d H:i:s', $plus);
		return $newPure;
	}
}

if (!function_exists('ngn')) {
	function ngn($amt = ''){
        if ($amt == '') $amt = '0';
           return '₦ '.number_format($amt);
	}
}

if (!function_exists('get_now')) {
	function get_now(){
		return gmdate("Y-m-d H:i:s", time());
	}
}


function get_percentage($total, $number){
 	if ( $total > 0 ) {
 		return round($number / ($total / 100),2);
  	} else {
    	return 0;
  	}
}

function get_discount( $sale_price, $discount_price){
	$percent = round((( $sale_price - $discount_price) * 100) / $sale_price);
	return '-'.$percent.'%';
}


if (!function_exists('neatDate')) {
    function neatDate($dt){
        $bdate = $dt;
        $bdate = str_replace('/', '-', $bdate);
        $nice_date = date('d M., Y', strtotime($bdate));
        return $nice_date;
    }
}

if (!function_exists('neatTime')) {
    function neatTime($dt){
        $bdate = $dt;
        $bdate = str_replace('/', '-', $bdate);
        $nice_date = date('g:i a', strtotime($bdate));
        return $nice_date;
    }
}

function ago($time){
	$time = strtotime($time);
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();

	   $difference     = $now - $time;
	   $tense         = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   $difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
	   $periods[$j].= "s";
	}

	return "$difference $periods[$j] ago ";
}


function cleanit($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
// Clean phone number
function phoneclean($num) {
    $num = preg_replace('/\D/', '', $num);
   	$len = strlen($num);
   	$accurate = $len - 10;
   	$realNUM = substr($num,$accurate);
   	return '0'.$realNUM;
}

function urlify($string, $id =''){
    $new_string = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    if( $id != '' ){
    	return 'product/'. $new_string .'-'.$id.'/';
    }else{
    	return $new_string;  
    }
}

if (!function_exists('productStatus')) {
    function productStatus($status){
        switch ($status) {
        	case 'pending':
			case 'shipped':
            case 'ordered':
        		return '<label class="label label-table label-warning">' . ucfirst( $status ). '</label>';
        		break;
        	case 'approved':
			case 'delivered':
        		return '<label class="label label-table label-success">' . ucfirst( $status ). '</label>';
        		break;  
        	case 'missing_images':
				return '<label class="label label-table label-info">' . ucfirst( $status ). '</label>';
				break;
			case 'returned':
			case 'failed_delivery':
			case 'canceled':
            	return '<label class="label label-table label-danger">' . ucfirst( $status ). '</label>';
            	break;
        	default:
        		return '<label class="label label-table label-danger">' . ucfirst( $status ). '</label>';
        		break;
        }
    }
}

// This functions round up the overall rating
if( !function_exists('product_overall_rating')){
	function product_overall_rating( $results ) : float{
		$rate5 = $rate4 = $rate3 = $rate2 = $rate1 = $total_outcome = 0;
		foreach ($results as $key ) {
			switch ($key['rating_score']) {
				case 5:
					$rate5 = $key['occurence'] * 5;
					break;
				case 4:
					$rate4 = $key['occurence'] * 4;
					break;
				case 3:
					$rate3 = $key['occurence'] * 3;
					break;
				case 2:
					$rate2 = $key['occurence'] * 2;
					break;
				case 1:
					$rate1 = $key['occurence'] * 1;
					break;
			}
			$total_outcome += $key['occurence'];
		}
		return round($rate5/$total_outcome + $rate4/$total_outcome + $rate3/$total_outcome + $rate2/$total_outcome + $rate1/$total_outcome, 1, PHP_ROUND_HALF_UP);
	}
}




if( !function_exists('product_percentage_rating')){
	function product_percentage_rating( $results ) : array{
		$rate5 = $rate4 = $rate3 = $rate2 = $rate1 = $total_outcome = 0;
		foreach ($results as $key ) { $total_outcome += $key['occurence']; }
		foreach( $results as $key ){
			
		}
		
	}
}

function current_full_url()
{
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

/*
 * Rating Star Generator : This works for all the ratings (5,4,3,2,1)
 * for also give the average score*/
if( !function_exists('rating_star_generator')){
	function rating_star_generator( $ratings ){
		$return = '';
		if( $ratings ) {
            $overall_rating = product_overall_rating( $ratings );
            $overall_rounded = round( $overall_rating );
            for( $i = 1; $i <= $overall_rounded; $i++){
                $return .= '<li class="rated"><i class="fa fa-star"></i></li>';
            }
            if( $overall_rounded < 5 ) {
                for ( $i = 0; $i < ( 5 - $overall_rounded); $i++){
                    $return .= '<li><i class="fa fa-star"></i></li>';
                }
            }
        }else{
			$x = 0;
            do {
            	$return .= '<li><i class="fa fa-star"></i></li>';
                $x++;
            } while ( $x <= 4);
		}
		return $return;
	}
}

/*
 * Calculate a single product rating and display the star
 * */
if( !function_exists('rating_single_generator')){
    function rating_single_generator( $rating_score ){
        $return = '';
        for ($i = 1; $i <= $rating_score; $i++) {
        	$return .= '<li class="rated"><i class="fa fa-star"></i></li>';
        }if ($rating_score < 5) {
            for ($i = 0; $i < (5 - $rating_score); $i++) {
				$return .= '<li><i class="fa fa-star"></i></li>';
            }
        }
        return $return;
    }
}

/*
 * Check the range of date for a discount price
 * */
if( !function_exists('date_in_range')){
    function date_in_range( $start_date, $end_date, $present_date){
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($present_date);
        return ( ($user_ts >= $start_ts) && ($user_ts <= $end_ts) );
    }
}
?>
