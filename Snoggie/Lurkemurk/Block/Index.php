<?php
namespace Snoggie\Lurkemurk\Block;



class Index extends \Magento\Framework\View\Element\Template
{

	protected $catFactory;
	protected $prodFactory;
	protected $jsonFactory;
	protected $prodStatus;
	protected $prodVisibility;
    protected $cart;
    protected $request;

	public function __construct
		(
			\Magento\Framework\View\Element\Template\Context $context,
			array $data = [],
			\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catFactory,
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
			\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
			\Magento\Catalog\Model\Product\Attribute\Source\Status $prodStatus,
			\Magento\Catalog\Model\Product\Visibility $prodVisibility,
            \Magento\Checkout\Model\Session $cart,
            \Magento\Framework\App\RequestInterface $request  
		)
	{

		parent::__construct($context, $data);
	
		$this->catFactory = $catFactory;
		$this->prodFactory = $prodFactory;
		$this->jsonFactory = $jsonFactory;
		$this->prodStatus = $prodStatus;
		$this->prodVisibility = $prodVisibility;
        $this->request = $request; 
        $this->cart = $cart;
	}

    public function getCart()
    {
        $items = $this->cart->getQuote()->getAllVisibleItems();
        $data = array(
            'cart'=>$items
        );
        return json_encode($data, JSON_PRETTY_PRINT);
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
                'id'=>$cat->getId(),
                'parentId'=>$cat->getParentId(),
                'name'=>$cat->getName()
            );
        }
        
        // $atrs = [];
        // $atrs = $cat->getAttributes();
        
        $data = array(
            'categories'=>$cats
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
                'sku'=>$prod->getSKU(),
                'id'=>$prod->getId(),
                'name'=>$prod->getName(),
                'categoryIds'=>$prod->getCategoryIds(),
                'image'=>$prod->getImage(),
                'price'=>$prod->getPrice(),
                'urlKey'=>$prod->getUrlKey(),
                'quantity'=>$prod->getQuantityAndStockStatus(),
                'mediaGallery'=>$prod->getMediaGallery()
            );
        }
        
        // $atrs = [];
        // $atrs = $prod->getAttributes();
        
        $data = array(
            'products'=>$prods
        );
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return $json;
    }
}