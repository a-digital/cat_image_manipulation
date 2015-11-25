<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Cat Image Manipulation Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Matt Shearing
 * @link		http://www.adigital.co.uk
 */

$plugin_info = array(
	'pi_name'		=> 'Cat Image Manipulation',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Matt Shearing',
	'pi_author_url'	=> 'http://www.adigital.co.uk',
	'pi_description'=> 'allows manipulations on category images',
	'pi_usage'		=>  cat_image_manipulation::usage()
);

class Cat_image_manipulation {
	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		//get our parameters
		$cat_id = ee()->TMPL->fetch_param('cat_id');
		$manipulation = ee()->TMPL->fetch_param('manipulation');
		
		//query the database using our category id
		$result = ee()->db->select('cat_image')
						->from('exp_categories')
						->where(array('cat_id' => $cat_id))
						->get();
		
		//return our category image and file directory as a string
		foreach ($result->result() as $key => $row) {
			$cat_image = $row->cat_image;
		}
		
		//find the closing brace on our full string
		$img_end = strpos($cat_image, "}");
		//return only the filename after the {filedir_x}
		$image_name = substr($cat_image, $img_end+1);
		
		//find the first underscore on our full string which will be {filedir_
		$start = strpos($cat_image, "_");
		//remove {filedir_ from string
		$file_dir = substr($cat_image, $start+1);
		//find closing brace for the filedir
		$dir_end = strpos($file_dir, "}");
		//remove anything after closing brace to leave us with the directory id regardless of number of digits
		$file_dir = substr($file_dir, 0, $dir_end);
		
		//query the database using our directory id
		$result = ee()->db->select('url')
						->from('exp_upload_prefs')
						->where(array('id' => $file_dir))
						->get();
		
		//return our full directory url path
		foreach ($result->result() as $key => $row) {
			$path = $row->url;
		}
		
		//explode the directory url path
		$path = explode("/", $path);
		//reverse the array to get to the last segment regardless of length
		$path = array_reverse($path);
		//add our image name and manipulation folder to the last segment of the url path
		$path[0] = "_".$manipulation."/".$image_name;
		//reverse the array again to its original order
		$path = array_reverse($path);
		//implode the array back into a string
		$path = implode("/", $path);
		
		//return our directory path, manipulation, and file as a full path to the template
		$this->return_data = $path;
	}//end __construct()
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

Core Purpose:

To be able to use image manipulations on category images available from the native directory



Tag Usage:

{exp:cat_image_manipulation cat_id="{category_id}" manipulation="wholesale"}



Variables:

cat_id
manipulation



Full Example:

{exp:channel:categories channel="wholesale_products" category_group="1" style="linear"}
<h4>{category_name}</h4>
<img src="{exp:cat_image_manipulation cat_id="{category_id}" manipulation="wholesale"}" alt="{category_name}" />
<p>{wholesale_desc}</p>
{/exp:channel:categories}

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.cat_image_manipulation.php */
/* Location: /add-ons/system/cat_image_manipulation/pi.cat_image_manipulation.php */