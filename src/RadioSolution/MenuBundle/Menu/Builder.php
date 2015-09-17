<?php
namespace  RadioSolution\MenuBundle\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;


class Builder extends Controller
{
    private $baseUrl = '';

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $this->baseUrl = $this->container->get('router')->getContext()->getBaseUrl();

    	$options['idmenu'] = 1;
    	$em = $this->getDoctrine()->getManager();

    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);
        //$menu->setattributes(array(
        //    'currentClass' => 'header-active',
        //    'firstClass' => false,
        //    'lastClass' => false,
        //));
        $menu->setChildrenAttribute('class', 'header-items');

    	$items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu = :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
        	->setParameters(array(
         		'id_menu' => $options['idmenu'],
    		))
            ->getResult()
        ;

    	foreach ($items as $key => $values) {
    	    $url = (preg_match('@^(http://|[/#])@', $values->getUrl()) ? '' : '/') . $values->getUrl();

    		$item = $menu->addChild($values->getName(), array('uri' => $this->baseUrl . $url));
            $item->setLinkAttribute('class', 'header-link');

            if (in_array($url, array('/', '/accueil'))) {
                 $item->setLinkAttribute('class', $item->getLinkAttribute('class') . ' header-link-home');
                 $item->setLabel('');
            }
            if (strpos($this->container->get('request')->getRequestUri(), $url) !== false) {
                $item->setLinkAttribute('class', $item->getLinkAttribute('class') . ' header-active');
    		}

    		$this->addChild($em, $values, $item, $options['idmenu'], 'header-link');

            $item->setChildrenAttribute('class', 'header-menu-level2');
    	}



    	return $menu;
    }

    protected function addChild($em, $entityItem, $item, $idmenu, $class = ''){
    	$items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent= :id_parent ORDER BY i.order_item ASC' )
        	->setParameters(array(
    			'id_menu' => $idmenu,
    			'id_parent' => $entityItem->getId(),
        	))
            ->getResult()
        ;

        if (!empty($items)) {
            $item->setLinkAttribute('class',  $item->getLinkAttribute('class') . ' header-link-has-children');
        }

    	foreach ($items as $key => $values) {
            $url = (preg_match('@^(http://|[/#])@', $values->getUrl()) ? '' : '/') . $values->getUrl();
    		$subitem = $item->addChild($values->getName(), array('uri' => $this->baseUrl . $url));
            if (!empty($class)) $subitem->setLinkAttribute('class', $class);

            if (strpos($this->container->get('request')->getRequestUri(), $url) !== false) {
                $subitem->setLinkAttribute('class', $subitem->getLinkAttribute('class') . ' header-active');
                $item->setLinkAttribute('class', $item->getLinkAttribute('class') . ' header-active');
            }

            // no recursivity
    		//$this->addChild($em, $values, $subitem, $idmenu);
    	}
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        $this->baseUrl = $this->container->get('router')->getContext()->getBaseUrl();

        $options['idmenu'] = 4;
    	$em = $this->getDoctrine()->getManager();

    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);

    	$items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
        	->setParameters(array(
         		'id_menu'=> $options['idmenu'],
    		))
            ->getResult()
        ;

    	foreach ($items as $key=>$values){
    		$url = (preg_match('@^(http://|[/#])@', $values->getUrl()) ? '' : '/') . $values->getUrl();
    		$item = $menu->addChild($values->getName(), array('uri' => $this->baseUrl . $url));
            $item->setLinkAttribute('class', 'footer-link');
    		//$this->addChild($em, $values, $item, $options['idmenu'], 'footer-link');
    	}
    	return $menu;
    }

}
