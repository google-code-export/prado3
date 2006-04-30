<?php
/*
 * Created on 29/04/2006
 */

class TActiveControlAdapter extends TControlAdapter
{
	private static $_renderedPosts = false;
	
	/**
	 * Render the callback request post data loaders once only.
	 */
	public function render($writer)
	{
		if(!self::$_renderedPosts)
		{
			$options = TJavascript::encode($this->getPage()->getPostDataLoaders(),false);
			$script = "Prado.Callback.PostDataLoaders.concat({$options});";
			$this->getPage()->getClientScript()->registerEndScript(get_class($this), $script);
			self::$_renderedPosts = true;
		}
		parent::render($writer);
	}	
} 
?>
