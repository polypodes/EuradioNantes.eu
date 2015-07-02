<?php 
namespace  RadioSolution\MenuBundle\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;


class Builder extends Controller
{
    public function mainMenu(FactoryInterface $factory, array $options )
    {
    	//$domain = $this->get('request')->server->get('HTTP_HOST');
    	$options['idmenu']=1;
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);
    	
    	$items = $em->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
    	->setParameters(array(
     		'id_menu'=> $options['idmenu'],
		))->getResult();
    	
    	foreach ($items as $key=>$values){
    	    if(strpos($values->getUrl(),'http://')===false){$ext='/';}else{$ext='';}

    		$item=$menu->addChild($values->getName(), array('uri' => $ext.$values->getUrl()));
    		
    		
    		//echo preg_quote($ext.$values->getUrl(), '/').'<br clear="all" />';
    		
    		if(preg_match('/.*'.preg_quote(str_replace('.', '', $ext.$values->getUrl()), '/').'.*/i', $this->container->get('request')->getRequestUri())){
    		
    			$item->setCurrent(true);
    			
    		}

    		$this->addChild($em,$values,$item,$options['idmenu']);
    	}
    	return $menu;
    }
    
    protected function addChild($em, $entityItem, $item, $idmenu){
    	//$domain = $this->get('request')->server->get('HTTP_HOST');
    	$items = $em->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent= :id_parent ORDER BY i.order_item ASC' )
    	->setParameters(array(
    			'id_menu'=> $idmenu,
    			'id_parent' => $entityItem->getId(),
    	))->getResult();
    	
    	
    	
    	foreach ($items as $key=>$values){
    	
    	    if(strpos($values->getUrl(),'http://')===false){$ext='/';}else{$ext='';}
    		$subitem = $item->addChild($values->getName(), array('uri' => $ext.$values->getUrl()));
    		

    		//echo str_replace(array('\-', '\.'), array('-', ''), preg_quote($ext.$values->getUrl(), '/')).'<br clear="all" />';
    		
    		
    		//echo $ext.$values->getUrl().'|||';
    		
    		//if($ext.$values->getUrl() == './categorie/teams-europennes'){
    			//echo '<div style="position:absolute; background-color:white;">';
    			//echo $ext.$values->getUrl().'<br/>';
    			//echo $this->container->get('request')->getRequestUri().'<br/>';
    			//echo '/.*'.preg_quote(str_replace('.', '', $ext.$values->getUrl()), '/').'.*/i'.'<br/>';
    			//echo '</div>';
    		
    		//	}		
			
			
    		if(preg_match('/.*'.preg_quote(str_replace('.', '', $ext.$values->getUrl()), '/').'.*/i', $this->container->get('request')->getRequestUri())){
    		
    			$item->setCurrent(true);
    			$subitem->setCurrent(true);
    		
    		}


    		$this->addChild($em,$values,$subitem,$idmenu);
    	}
    }
    
    public function footerMenu(FactoryInterface $factory, array $options )
    {
    	//$domain = $this->get('request')->server->get('HTTP_HOST');
    	$options['idmenu']=4;
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);
    	
    	$items = $em->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
    	->setParameters(array(
     		'id_menu'=> $options['idmenu'],
		))->getResult();
    	
    	foreach ($items as $key=>$values){
    		if(strpos($values->getUrl(),'http://')===false){$ext='/';}else{$ext='';}
    		$item=$menu->addChild($values->getName(), array('uri' => $ext.$values->getUrl()));
    		$this->addChild($em,$values,$item,$options['idmenu']);
    	}
    	return $menu;
    }
    
    public function surfooterMenu(FactoryInterface $factory, array $options )
    {
    	//$domain = $this->get('request')->server->get('HTTP_HOST');
    	$options['idmenu']=6;
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);
    	
    	$items = $em->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
    	->setParameters(array(
     		'id_menu'=> $options['idmenu'],
		))->getResult();
    	
    	foreach ($items as $key=>$values){
    		if(strpos($values->getUrl(),'http://')===false){$ext='/';}else{$ext='';}
    		$item=$menu->addChild($values->getName(), array('uri' => $ext.$values->getUrl()));
    		$this->addChild($em,$values,$item,$options['idmenu']);
    	}
    	return $menu;
    }
     
    
    
    
    public function footerLinks(FactoryInterface $factory, array $options )
    {
    	$domain = $this->get('request')->server->get('HTTP_HOST');
    	$options['idmenu']=5;
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$menu = $em->getRepository('MenuBundle:Menu')->find($options['idmenu']);
    	$menu = $factory->createItem($menu);
    	
    	$items = $em->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.menu= :id_menu AND i.parent IS NULL ORDER BY i.order_item ASC')
    	->setParameters(array(
     		'id_menu'=> $options['idmenu'],
		))->getResult();
    	
    	foreach ($items as $key=>$values){
    		if(strpos($values,'http://')){$ext='';}else{$ext='/';}
    		$item=$menu->addChild($values->getName(), array('uri' => $ext.$values->getUrl()));
    		$this->addChild($em,$values,$item,$options['idmenu']);
    	}
    	return $menu;
    }
    
    
}