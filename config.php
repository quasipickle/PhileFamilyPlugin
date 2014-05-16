<?php

return array(
	# If TRUE, then directories that are siblings of pages will have their index file
	# included in the list of siblings.
	#
	# If FALSE, only actual pages will be included
	'sibling_dirs' => TRUE,

	# If 'desc', the 1st ancestor will be the parent, the 2nd the grandparent, etc
	# If 'asc' (or anything other than 'desc'), the 1st ancestor will be the homepage,
	# and the last ancestor will be the parent
	'ancestor_sort' => 'asc'
);