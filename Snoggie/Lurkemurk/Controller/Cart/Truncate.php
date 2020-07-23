<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class Truncate extends \Magento\Framework\App\Action\Action 
{

    protected $jsonFactory;
    protected $cartUtil;
    protected $cart; 
    protected $request;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    	    \Magento\Checkout\Helper\Cart $cartUtil,
            \Magento\Framework\App\RequestInterface $request
        )
    {
       
        $this->jsonFactory = $jsonFactory;
        $this->cartUtil = $cartUtil;
        $this->cart = $this->cartUtil->getCart();
        $this->request = $request;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        $this->cart->truncate();
        $this->cart->save();
        
        // result
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'res'=>'true'
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
