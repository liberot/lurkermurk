<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class ListItems extends \Magento\Framework\App\Action\Action 
{

    protected $jsonFactory;
    protected $prodFactory;
    protected $cartUtil;
    protected $cart; 
    protected $request; 
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    	    \Magento\Catalog\Model\ProductFactory $prodFactory,
            \Magento\Checkout\Helper\Cart $cartUtil,
            \Magento\Framework\App\RequestInterface $request
        )
    {
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->cartUtil = $cartUtil;
        $this->cart = $this->cartUtil->getCart();
        $this->request = $request; 
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        $prodFactory = $this->prodFactory->create();
        $ctems = [];
        $items = $this->cart->getItems();
        foreach($items as $item){
            $ctems[]= array(
                'pid'=>$item->getProductId(),
                'qty'=>$item->getQty()
            );
        }
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'items'=>$ctems
        );
        $json = $this->jsonFactory->create();
        $json->setData($data);
        return $json;
    }
}
