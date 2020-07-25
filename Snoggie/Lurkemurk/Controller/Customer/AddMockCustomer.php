<?php
namespace Snoggie\Lurkemurk\Controller\Customer;


// https://magento.stackexchange.com/questions/78164/how-to-add-a-customer-programmatically-in-magento-2#78265
class AddMockCustomer extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->setEmail("email@domain.com"); 
        $customer->setFirstname("First Name");
        $customer->setLastname("Last name");
        $customer->setPassword("password");
        $customer->save();
        $customer->sendNewAccountEmail();
    }
}