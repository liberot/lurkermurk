<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class ProductsById extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $prodStatus;
    protected $prodVisibility;
    protected $request;
    protected $catFactory;
    protected $productRepo; 
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Catalog\Model\Product\Attribute\Source\Status $prodStatus,
    		\Magento\Catalog\Model\Product\Visibility $prodVisibility,
            \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catFactory,
            \Magento\Catalog\Model\ProductFactory $prodFactory,
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Catalog\Api\ProductRepositoryInterface $productRepo
        )
    {
       
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->prodStatus = $prodStatus;
        $this->prodVisibility = $prodVisibility;
        $this->request = $request; 
        $this->catFactory = $catFactory;
        $this->productRepo = $productRepo; 

        parent::__construct($context);
    }
 
    public function execute()
    {

        $prodFactory = $this->prodFactory->create();
                        
        $rqp = $this->request->getParams();
        $prdz = [];

        foreach ($rqp as $key=>$val) {
            
            switch($key){
            
                case 'ids':
                    $val.= ',';
                    $ids = explode(',', $val);
                    foreach($ids as $id){
                        $prod = $prodFactory->load($id);
                        if(null != $prod->getId()){
                            $prdz[]= array(
                                'sku'=>$prod->getSKU(),
                                'id'=>$prod->getId(),
                                'name'=>$prod->getName(),
                                'categoryIds'=>$prod->getCategoryIds(),
                                'description'=>$prod->getDescription(),
                                'shortDescription'=>$prod->getShortDescription(),
                                'image'=>$prod->getImage(),
                                'price'=>$prod->getPrice(),
                                'urlKey'=>$prod->getUrlKey(),
                                'quantity'=>$prod->getQuantityAndStockStatus(),
                                'mediaGallery'=>$prod->getMediaGallery()
                            );    
                        }
                    }
                    break;
             
                case 'skus':
                    $val.= ',';
                    $skus = explode(',', $val);
                    foreach($skus as $sku){
                        $id = $prodFactory->getIdBySku($sku);
                        $prod = $prodFactory->load($id);
                        if(null != $prod->getId()){
                            $prdz[]= array(
                                'sku'=>$prod->getSKU(),
                                'id'=>$prod->getId(),
                                'name'=>$prod->getName(),
                                'categoryIds'=>$prod->getCategoryIds(),
                                'description'=>$prod->getDescription(),
                                'shortDescription'=>$prod->getShortDescription(),
                                'image'=>$prod->getImage(),
                                'price'=>$prod->getPrice(),
                                'urlKey'=>$prod->getUrlKey(),
                                'quantity'=>$prod->getQuantityAndStockStatus(),
                                'mediaGallery'=>$prod->getMediaGallery()
                            );    
                        }
                    }                
                    break;
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
