<?php

/******************************************************************************/
/******************************************************************************/

class Autoride_ThemeVideo
{
	/**************************************************************************/
	
	public $videoSource;
	
	/**************************************************************************/

	function __construct() 
	{ 
		$this->videoSource=array
		(
			'vimeo'=>array(__('Vimeo','autoride')),
			'youtube'=>array(__('YouTube','autoride'))
		);
	}

	/**************************************************************************/
	
	function getVideoSource()
	{
		return($this->videoSource);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/