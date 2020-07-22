<?php
namespace Snoggie\Lurkemurk\Controller\Customer;



class Auth extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $request;
    protected $customer;
    protected $store;
    protected $storeManager; 

    public function __construct
    	(
    	    \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $pageFactory,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Customer\Model\Customer $customer,
            \Magento\Store\Model\Store $store,
            \Magento\Store\Model\StoreManagerInterface $storeManager
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->customer = $customer;
        $this->request = $request;
        $this->store = $store;
        $this->storeManager = $storeManager;

        parent::__construct($context);
    }

    public function execute()
    {
    
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
        $storeId = $this->storeManager->getStore()->getStoreId();
        $this->customer->setWebsiteId($websiteId);
        $this->customer->setStoreId($storeId);

        $json = $this->jsonFactory->create();
        $data = [];
        
        $clnt = $this->request->getParam('clnt');
        $pass = $this->request->getParam('pass');
        
        if('' == $clnt){
            $data[]= array(
                'auth'=>'false',
                'message'=>'no client'
            );
            $json->setData($data);
            return $json;
        }
        
        if('' == $pass){
            $data[]= array(
                'auth'=>'false',
                'message'=>'no pass'
            );
            $json->setData($data);
            return $json;
        }
        
        // app/code/Magento/Customer/Model/Customer.php
        // authenticate();
        $auth = false; 
        
        // fetches customer
        $this->customer = $this->customer->loadByEmail($clnt);
        if($this->customer->getConfirmation()){
            // .. confirmation issues
        }

        // auths customer
        if($auth = $this->customer->validatePassword($pass)){
            $this->customer->_eventManager->dispatch(
                'customer_customer_authenticated',
                ['model'=>$this->customer, 'password'=>$pass]
            );
        };
    
        if(false == $auth){
            $data[]= array(
                'auth'=>'false',
                'message'=>'no auth'
            );
            $json->setData($data);
            return $json;
        }
        
        $data[]= array(
            'auth'=>'true',
            'message'=>'authed all bright'
        );
        $json->setData($data);
        return $json;
    
    }
}


