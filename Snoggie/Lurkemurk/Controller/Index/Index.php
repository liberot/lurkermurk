<?php
namespace Snoggie\Lurkemurk\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action 
{
	protected $pageFactory;
	protected $catFactory;
	protected $prodFactory;
	protected $jsonFactory;

	public function __construct
		(
			\Magento\Framework\App\Action\Context $context,
			\Magento\Framework\View\Result\PageFactory $pageFactory,
			\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catFactory,
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $prodFactory,
			\Magento\Framework\Controller\Result\JsonFactory $jsonFactory
		)
	{
		$this->catFactory = $catFactory;
		$this->pageFactory = $pageFactory;
		$this->prodFactory = $prodFactory;
		$this->jsonFactory = $jsonFactory;

		return parent::__construct($context);
	}

	public function execute()
	{
		$page = $this->pageFactory->create();
		$block = $page->getLayout()->getBlock('lurkemurk_index_index');
		return $page;
	}	
}