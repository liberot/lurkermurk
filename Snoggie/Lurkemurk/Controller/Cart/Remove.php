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
        if(null != $prod->getId()){
            $this->cart->removeItem($prod->getId());   
            $this->cart->save();
            $res = true;          
        }

        // result
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'res'=>$res ? 'true' : 'false'
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
