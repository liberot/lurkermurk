<?php
namespace Snoggie\Lurkemurk\Controller\Customer;



class Auth extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $request;
    protected $customer;

    public function __construct
    	(
    	    \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $pageFactory,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Customer\Model\Customer $customer
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->customer = $customer;
        $this->request = $request;
        
        parent::__construct($context);
    }

    public function execute()
    {
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
        
        if(!$auth = $this->customer->authenticate($client, $pass)){
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


