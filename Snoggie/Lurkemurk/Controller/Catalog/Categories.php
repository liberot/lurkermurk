<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class Categories extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $catFactory;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catFactory
        )
    {
        
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->catFactory = $catFactory;

        parent::__construct($context);
    }
 
    public function execute()
    {
        $coll = $this->catFactory->create();
        $coll
            ->addAttributeToSelect('*')
            ->addIsActiveFilter()
        ;

        $cats = [];
        foreach ($coll as $cat) {
            $cats[]= array(
                'id'=>$cat->getId(),
                'parentId'=>$cat->getParentId(),
                'name'=>$cat->getName(),
                'path'=>$cat->getPath(),
                'image'=>$cat->getImage()
            );
        }
        
        $data = array(
            'categories'=>$cats
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
