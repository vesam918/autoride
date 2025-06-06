<?php

/******************************************************************************/
/******************************************************************************/

class Autoride_ThemeButton
{
	/**************************************************************************/

	public $style;
	
	/**************************************************************************/
	
	function __construct()
	{		
		$this->style=array
		(
			'1'=>array(__('Style 1','autoride')),
			'2'=>array(__('Style 2','autoride')),
			'3'=>array(__('Style 3','autoride')),
			'4'=>array(__('Style 4','autoride'))
		);	
	}
	
	/**************************************************************************/
	
	function getStyle()
	{
		return($this->style);
	}
	
	/**************************************************************************/
	
	function isStyle($style)
	{
		return(array_key_exists($style,$this->style));
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/