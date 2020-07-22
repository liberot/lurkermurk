<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class Add extends \Magento\Framework\App\Action\Action 
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
        $prodFactory = $this->prodFactory->create();
        $reqp = $this->request->getParams();
       
        // fixdiss
        // fixdiss
        // fixdiss
        // fixdiss
        // todo: post this values....
        // use a post collection
        foreach ($reqp as $key=>$val) {
            switch($key){
            case 'ids':
                $val.= ',';
                $ids = explode(',', $val);
                foreach($ids as $id){
                    $prod = null;
                    $prod = $prodFactory->load($id);
                    if(null != $prod){
                        $prms = array(
                            'qty'=>1,
                            'product'=>$prod->getId()
                        );
                        $this->cart->addProduct($prod, $prms);   
                        $this->cart->save();             
                    }
                }
            }
        }
        // fixdiss
        
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
            'act'=>$this->request->getActionName()
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
