<?php
namespace Snoggie\Lurkemurk\Controller\Customer;




class Register extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
    protected $customerFactory;
    protected $request; 
    protected $jsonFactory;
    protected $validatorFactory;
    protected $customerGroups;
    protected $customer;
    
    public function __construct
        (
            \Magento\Framework\App\Action\Context $context,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Customer\Model\CustomerFactory $customerFactory,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Framework\Validator\Factory $validatorFactory,
            \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroups,
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Customer\Model\Customer $customer
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->request = $request; 
        $this->jsonFactory = $jsonFactory;
        $this->validatorFactory = $validatorFactory;
        $this->customerGroups = $customerGroups;
        $this->customer = $customer; 
        
        parent::__construct($context);
    }

    public function execute()
    {
        $json = $this->jsonFactory->create();
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
        $storeId = $this->storeManager->getStore()->getStoreId();
        $groups = $this->customerGroups->toOptionHash();

        $clnt = $this->request->getParam('clnt');
        $pass = $this->request->getParam('pass');
        $forename = $this->request->getParam('forename');
        $surename = $this->request->getParam('surename');

        $this->customer->setWebsiteId($websiteId);
        $this->customer->setStoreId($storeId);
        $this->customer = $this->customer->loadByEmail($clnt);
        if(null != $this->customer->getId()){
            $data = array(
                'cmd'=>$this->request->getActionName(),
                'res'=>'customer already exists'
            );
            $json->setData($data);
            return $json;
        }
        
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->setStoreId($storeId);
        $customer->setEmail($clnt); 
        $customer->setFirstname($forename);
        $customer->setLastname($surename);
        $customer->setPassword($pass);
        // {"groups":["NOT LOGGED IN","General","Wholesale","Retailer"]}
        $customer->setGroupId(1);
       
        // ./app/code/Magento/Customer/Model/ResourceModel/Customer.php::_beforeSave($customer);
        $validatorFactory = $this->validatorFactory;
        $validator = $validatorFactory->createValidator('customer', 'save');
        if(false == $validator->isValid($customer)) {
            $data = array(
                'cmd'=>$this->request->getActionName(),
                'res'=>'customer object is not valid',
                'messages'=>$validator->getMessages()
            );
            $json->setData($data);
            return $json;
        }
        
        $customer->save();
        $customer->sendNewAccountEmail();

        $data = array(
            'cmd'=>$this->request->getActionName(),
            'groups'=>$groups
        );
        
        $json->setData($data);
        return $json;    
    }
}