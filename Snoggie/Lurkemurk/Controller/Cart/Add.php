<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class Add extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $cart;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
    		\Magento\Checkout\Model\Cart\CartInterface $cart
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->cart = $cart;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        /*
        $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
        $params = array();      
        $params['options[469]'] = 459;
        $params['qty'] = 1;
        $params['product'] = 25
        $this->cart->addProduct($product, $params);
        $this->cart->save();
        */
        $cart = [];
        $data = array(
            'products'=>$cart
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
