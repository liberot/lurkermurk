<?php
namespace Snoggie\Lurkemurk\Controller\Customer;




class Register extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
    protected $customerFactory;
    protected $request; 
    protected $jsonFactory;
    protected $validatorFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Framework\Validator\Factory $validatorFactory,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->request = $request; 
        $this->jsonFactory = $jsonFactory;
        $this->validatorFactory = $validatorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $clnt = $this->request->getParam('clnt');
        $pass = $this->request->getParam('pass');
        $forename = $this->request->getParam('forename');
        $surename = $this->request->getParam('surename');

        //... ?? 

        $json = $this->jsonFactory->create();
        
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
        $customer = $this->customerFactory->create();
        
        $customer->setWebsiteId($websiteId);
        $customer->setEmail($clnt); 
        $customer->setFirstname($forename);
        $customer->setLastname($surename);
        $customer->setPassword($pass);

        $validator = $this->validatorFactory->createValidator('customer', 'save');
        if(false == $validator->isValid($customer)) {
            $data = array(
                'cmd'=>$this->request->getActionName(),
                'message'=>'customer object is not valid'
            );
            $json->setData($data);
            return $json;    
        }

        $customer->save();
        $customer->sendNewAccountEmail();

        $data = array(
            'cmd'=>$this->request->getActionName(),
            'message'=>'this i dunno some error might get raised somewhere sometime since there might be a customer already registered and such but there is no such procedures mentioned in app/code/Magento/Customer/Model/Customer.php'
        );
        
        $json->setData($data);
        return $json;    
    }
}