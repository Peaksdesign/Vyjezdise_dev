<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

        $router[] = $adminRouter = new RouteList('Admin');
        $adminRouter[] = new Route('admin/<presenter>/<action>[/<id>]','Homepage:default');

        $router[] = $userRouter = new RouteList('User');
        $userRouter[] = new Route('areal/<id>', 'Destination:detail');
        $userRouter[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}
