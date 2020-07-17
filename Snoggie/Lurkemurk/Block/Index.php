<?php
namespace Snoggie\Lurkemurk\Block;



class Index extends \Magento\Framework\View\Element\Template
{

	protected $catFactory;
	protected $prodFactory;
	protected $jsonFactory;
	protected $prodStatus;
	protected $prodVisibility;

	public function __construct
		(
			\Magento\Framework\View\Element\Template\Context $context,
			array $data = [],
			\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catFactory,
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
			\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
			\Magento\Catalog\Model\Product\Attribute\Source\Status $prodStatus,
			\Magento\Catalog\Model\Product\Visibility $prodVisibility
		)
	{

		parent::__construct($context, $data);
	
		$this->catFactory = $catFactory;
		$this->prodFactory = $prodFactory;
		$this->jsonFactory = $jsonFactory;
		$this->prodStatus = $prodStatus;
		$this->prodVisibility = $prodVisibility;

	}

	public function getCategories() 
	{

		$coll = $this->catFactory->create();
        $coll
            ->addAttributeToSelect('*')
            ->addIsActiveFilter()
        ;

        $cats = [];
        foreach ($coll as $cat) {
            $cats[]= array(
                'name'=>$cat->getName(),
            );
        }
        
        $atrs = [];
        $atrs = $cat->getAttributes();
        
        $data = array(
            'categories'=>$cats,
            'attributes'=>$atrs
        );
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return $json;
    }

    public function getProducts() 
	{

		$coll = $this->prodFactory->create();
        $coll
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', ['in'=>$this->prodStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in'=>$this->prodVisibility->getVisibleInSiteIds()])
        ;

        $prods = [];
        foreach ($coll as $prod) {
            $prods[]= array(
                'name'=>$prod->getName(),
                'categoryIds'=>$prod->getCategoryIds()
            );
        }
        
        $atrs = [];
        $atrs = $prod->getAttributes();
        
        $data = array(
            'products'=>$prods,
            'attributes'=>$atrs
        );
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return $json;
    }
}