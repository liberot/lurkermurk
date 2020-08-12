<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class ProductsOfCategory extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $prodStatus;
    protected $prodVisibility;
    protected $request;
    protected $catFactory;
    
    public function __construct
    	(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
		\Magento\Catalog\Model\Product\Attribute\Source\Status $prodStatus,
		\Magento\Catalog\Model\Product\Visibility $prodVisibility,
		\Magento\Catalog\Model\CategoryFactory $catFactory,
		\Magento\Framework\App\RequestInterface $request
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->prodStatus = $prodStatus;
        $this->prodVisibility = $prodVisibility;
        $this->request = $request; 
        $this->catFactory = $catFactory;
        
        parent::__construct($context);
    }
 
    public function execute()
    {

        $rqp = $this->request->getParams();
        $catFactory = $this->catFactory->create();
                    
        $prdz = [];
        foreach ($rqp as $key=>$val) {
            if('ids' == $key){
                $val.= ',';
                $ids = explode(',', $val);
                foreach($ids as $id){
                    if(null == $id){ continue; }
                    $cat = $catFactory->load($id);
                    $coll = $cat->getProductCollection();
                    $coll
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', ['in'=>$this->prodStatus->getVisibleStatusIds()])
                        ->addAttributeToFilter('visibility', ['in'=>$this->prodVisibility->getVisibleInSiteIds()])
                    ; 
                    foreach ($coll as $prod) {
                        $prdz[]= array(
                            'sku'=>$prod->getSKU(),
                            'id'=>$prod->getId(),
                            'name'=>$prod->getName(),
                            'categoryIds'=>$prod->getCategoryIds(),
                            'description'=>$prod->getDescription(),
                            'shortDescription'=>$prod->getShortDescription(),
                            'image'=>$prod->getImage(),
                            'price'=>$prod->getPrice(),
                            'price'=>$prod->getPrice(),
                            'urlKey'=>$prod->getUrlKey(),
                            'quantity'=>$prod->getQuantityAndStockStatus(),
                            'mediaGallery'=>$prod->getMediaGallery()
                        );
                    };               
                }
            }
        }
        
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'products'=>$prdz
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
