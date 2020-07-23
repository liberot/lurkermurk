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
        
        // app/code/Magento/Customer/Model/Customer.php::authenticate();
        $auth = false; 
        
        // fetches customer
        $this->customer = $this->customer->loadByEmail($clnt);

        // confirmation issue 
        /*
        if($this->customer->getConfirmation() && 
            $this->customer->accountConfirmation->isConfirmationRequired(
                $this->customer->getWebsiteId(), 
                $this->customer->getId(), 
                $this->customer->getEmail()
            )
        ){
            $data = array(
                'cmd'=>$this->request->getActionName(),
                'auth'=>'false',
                'message'=>'not confirmed'
            );
            $json->setData($data);
            return $json;
        }
        */

        // no such customer
        // ..

        // auth trial of client customer
        if($auth = $this->customer->validatePassword($pass)){
            // this might redirect the browser
            // for session renewal issues 
            // beats me...
            /*
            $this->customer->_eventManager->dispatch(
                'customer_customer_authenticated',
                ['model'=>$this->customer, 'password'=>$pass]
            );
            */
        };
    
        // no auth for what reason 
        if(false == $auth){
            $data = array(
                'cmd'=>$this->request->getActionName(),
                'auth'=>'false',
                'message'=>'no auth'
            );
            $json->setData($data);
            return $json;
        }
        
        // auth 
        // mage2 might redirect for session fissmiss dunno 
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'auth'=>'true',
            'message'=>'authed all bright'
        );
        $json->setData($data);
        return $json;
    
    }
}


