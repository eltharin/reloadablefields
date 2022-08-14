<?php

namespace Eltharin\ReloadableFieldBundle\Service;

use Twig\Markup;

class ButtonPrinter
{
	public static function showButton($val)
	{
		$val['params']['after'] = ['reloadBtn' => ['type' => 'markup', 'content' => new Markup('<span class="btn success reloader" data-target="' . $val['id'] . '" />reload</span>', 'UTF-8')]];

		return $val;
	}
}