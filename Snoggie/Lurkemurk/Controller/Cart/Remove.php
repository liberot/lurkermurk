<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class Remove extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $cartUtil;
    protected $cart; 
    protected $request;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    	    \Magento\Catalog\Model\ProductFactory $prodFactory,
            \Magento\Checkout\Helper\Cart $cartUtil,
            \Magento\Framework\App\RequestInterface $request
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->cartUtil = $cartUtil;
        $this->cart = $this->cartUtil->getCart();
        $this->request = $request;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        // reads post fields
        $pid = $this->request->getParam('pid');
        $pid = intval($pid);
        
        // fetches the product
        $prodFactory = $this->prodFactory->create();
        
        // adds the product to the cart
        $res = false;
        $prod = $prodFactory->load($pid);
        if(null != $prod){
            $this->cart->removeItem($prod->getId());   
            $this->cart->save();             
        }

        // result
        $data = array(
            'res'=>'???',
            'cmd'=>$this->request->getActionName()
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
