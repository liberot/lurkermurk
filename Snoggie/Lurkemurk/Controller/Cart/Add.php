<?php
namespace Snoggie\Lurkemurk\Controller\Cart;



class Add extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $cart;
    protected $request;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    	    \Magento\Catalog\Model\ProductFactory $prodFactory,
            // \Magento\Checkout\Model\Session $cart,
            \Magento\Checkout\Model\Cart $cart,
            \Magento\Framework\App\RequestInterface $request
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->cart = $cart;
        $this->request = $request;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        // ??
        // $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $this->cart = $objManager->create('\Magento\Checkout\Model\Cart');
        // ??

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
        

        $items = $this->cart->getQuote()->getAllVisibleItems();
        $ctems = [];
        foreach($items as $item){
            $ctems[]= $item->getId();
        }
        $data = array(
            'items'=>$ctems,
            'cart'=>'toBeEvalueated..:))',
            'reqp'=>$reqp
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
