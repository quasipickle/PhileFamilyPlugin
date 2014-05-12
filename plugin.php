<?php
class Family extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {

	private $curr_page_path;

	public function __construct() {
		 \Phile\Event::registerEvent('template_engine_registered', $this);
	}

	public function on($eventKey, $data = null) {
		if($eventKey == 'template_engine_registered'){
			$this->curr_path = $data['data']['current_page']->getFilePath();
			$this->curr_file = basename($this->curr_path);
			$this->curr_dir  = dirname($this->curr_path);

			$data['data'] = $this->buildFamily($data['data']);
			return $data;
		}
	}

	

	private function buildFamily($data){
		$ancestor_paths = $this->buildAncestorPaths($data);
		$siblings       = [];
		$ancestors      = [];

		foreach($data['pages'] as $Page){
			$Page->is_dir  = FALSE;
			$loop_path     = $Page->getFilePath();
			$loop_dir      = dirname($loop_path);
			$loop_granddir = dirname($loop_dir);

			// We've found a sibling page
			if($loop_dir == $this->curr_dir && ($loop_path != $this->curr_path)){
				$siblings[] = $Page;
			}

			// We've found a sibling directory, so add the index file  from that directory as a sibling
			if($this->settings['sibling_dirs'] && $this->curr_dir == $loop_granddir){
				$Page->is_dir = TRUE;
				$siblings[]   = $Page;
			}

			// We've found an ancestor file
			if(in_array($loop_path,$ancestor_paths)){
				$ancestors[$Page->getFilePath()] = $Page;
			}
		}

		ksort($ancestors);

		$data['siblings']  = $siblings;
		$data['ancestors'] = $ancestors;

		return $data;
	}

	#
	# This function builds an array of paths that the ancestors of the current page must have.  
	# Used by buildFamily to know when an ancestor page has been found when looping through all pages
	#
	# Returns an array of paths, sorted by first ancestor, to parent
	#
	private function buildAncestorPaths($data){
		$ancestor_paths     = [];
		$index_filename     = 'index'.CONTENT_EXT;
		$content_dir        = rtrim(CONTENT_DIR,'/');
		$content_dir_length = strlen($content_dir);

		// If we're viewing an index file...
		$active_dir         = ($this->curr_file == $index_filename) 
								// the first ancestor is in the parent directory
								? dirname($this->curr_dir) 
								// otherwise the first ancestor is the index file in the current directory
								: $this->curr_dir;

		
		while(strlen($active_dir) >= $content_dir_length){
			$ancestor_paths[] = $active_dir.'/'.$index_filename;
			$active_dir       = dirname($active_dir);
		}

		return $ancestor_paths;
	}
}
