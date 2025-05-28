<?php 
		/* Template Name: Vehicle list */

		if(Autoride_ThemePlugin::isActive('CHBSPlugin'))
		{
			$Plugin=new CHBSPlugin();
			$Plugin->prepareLibrary();
			$Plugin->addLibrary('style',2,'enqueue');
			$Plugin->addLibrary('script',2,'enqueue');	
		}

		get_header(); 
		
		get_template_part('vehicle-list','content'); 

		get_footer(); 