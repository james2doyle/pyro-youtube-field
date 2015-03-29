<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Youtube Video Field Type
 *
 * @package		Addons\Field Types
 * @author		James Doyle (james2doyle)
 * @license		MIT License
 * @link		http://github.com/james2doyle/pyro-youtube-field
 */
class Field_youtube_video
{
	public $field_type_slug    = 'youtube_video';
	public $db_col_type        = 'text';
	public $version            = '1.0.0';
	public $author             = array('name'=>'James Doyle', 'url'=>'http://github.com/james2doyle/pyro-youtube-field');

	// --------------------------------------------------------------------------

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data, $entry_id, $field)
	{
		$values = json_decode($data['value']);
		if (empty($values)) {
			$values = new stdClass();
			$values->url = "";
		}
		$instruction = lang('streams:youtube_video.select_instructions');
		$input = form_input($data['form_slug'], $values->url, "style=\"min-width:340px\"");
		return sprintf("<div id=\"%s_youtube_video\" class=\"youtube_video\"><p>%s</p>%s</div>", $data['form_slug'], $instruction, $input);
	}

	private function _youtube_title($id) {
		// $id = 'YOUTUBE_ID';
		// returns a single line of XML that contains the video title. Not a giant request.
		$videoTitle = @file_get_contents("https://gdata.youtube.com/feeds/api/videos/{$id}?v=2&fields=title");
		// despite @ suppress, it will be false if it fails
		if ($videoTitle) {
			// look for that title tag and get the insides
			preg_match("/<title>(.+?)<\/title>/is", $videoTitle, $titleOfVideo);
			return $titleOfVideo[1];
		} else {
			return false;
		}
		// usage:
		// $item = youtube_title('zgNJnBKMRNw');
	}

	private function _validate_link($url) {
		// http://stackoverflow.com/questions/13476060/validating-youtube-url-using-regex
		$rx = '~
		^(?:https?://)?             # Optional protocol
		(?:www\.)?                  # Optional subdomain
		(?:youtube\.com|youtu\.be)  # Mandatory domain name
		/watch\?v=([^&]+)           # URI with video id as capture group 1
		~x';
		// if matching succeeded, $matches[1] would contain the video ID
		$has_match = preg_match($rx, $url, $matches);
		return (empty($matches[1])) ? false : $matches[1];
	}

	public function pre_save($input)
	{
		$id = $this->_validate_link($input);
		$results = array(
			'id' => $this->_validate_link($input),
			'title' => $this->_youtube_title($id),
			'url' => $input
			);
		return json_encode($results);
	}

	public function pre_output_plugin($value, $data)
	{
		return json_decode($value);
	}

	public function event($field)
	{
		// $this->CI->type->add_js('youtube_video', 'youtube_video.js');
		// $this->CI->type->add_css('youtube_video', 'youtube_video.css');
	}
}
