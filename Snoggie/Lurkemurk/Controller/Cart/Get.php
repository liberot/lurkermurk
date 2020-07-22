<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class Get extends \Magento\Framework\App\Action\Action 
{

    protected $jsonFactory;
    protected $prodFactory;
    protected $cartUtil;
    protected $cart; 
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    	    \Magento\Catalog\Model\ProductFactory $prodFactory,
            \Magento\Checkout\Helper\Cart $cartUtil
        )
    {
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->cartUtil = $cartUtil;
        $this->cart = $this->cartUtil->getCart();
        parent::__construct($context);
    }
 
    public function execute()
    {
        $prodFactory = $this->prodFactory->create();
        $ctems = [];
        $items = $this->cart->getItems();
        foreach($items as $item){
            $ctems[]= array(
                'id'=>$item->getProductId(),
                'qty'=>$item->getQty(),
                'name'=>$item->getProduct()->getName()
            );
        }
        $data = array(
            'items'=>$ctems,
            'cmd'=>$this->request->getActionName()
        );
        $json = $this->jsonFactory->create();
        $json->setData($data);
        return $json;
    }
}
