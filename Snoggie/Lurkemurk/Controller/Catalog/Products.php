<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class Products extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    
    public function __construct
    	(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
		\Magento\Catalog\Model\Product\Attribute\Source\Status $prodStatus,
		\Magento\Catalog\Model\Product\Visibility $prodVisibility
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->prodStatus = $prodStatus;
        $this->prodVisibility = $prodVisibility;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
        $coll = $this->prodFactory->create();
        $coll
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', ['in'=>$this->prodStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in'=>$this->prodVisibility->getVisibleInSiteIds()])
        ;

        $prdz = [];
        foreach ($coll as $prod) {
            $prdz[]= array(
                'name'=>$prod->getName(),
                'categoryIds'=>$prod->getCategoryIds(),
            ); 
        };

        $atrs = [];
        $atrs = $prod->getAttributes();
        
        $data = array(
            'products'=>$prdz,
            'attributes'=>$atrs
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
