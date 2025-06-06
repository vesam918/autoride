<?php

/******************************************************************************/
/******************************************************************************/

class Autoride_ThemeBorder
{
	/**************************************************************************/

	public $style;
	
	/**************************************************************************/
	
	function __construct()
	{
		$this->style=array
		(
			'none'=>__('None','autoride'),
			'hidden'=>__('Hidden','autoride'),
			'dotted'=>__('Dotted','autoride'),
			'dashed'=>__('Dashed','autoride'),
			'solid'=>__('Solid','autoride'),
			'double'=>__('Double','autoride'),
			'groove'=>__('Groove','autoride'),
			'ridge'=>__('Ridge','autoride'),
			'inset'=>__('Inset','autoride'),
			'outset'=>__('Outset','autoride'),
			'inherit'=>__('Inherit','autoride')
		);
	}
	
	/**************************************************************************/
	
	function getStyle()
	{
		return($this->style);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/