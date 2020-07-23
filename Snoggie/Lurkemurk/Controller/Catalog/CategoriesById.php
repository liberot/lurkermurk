<?php
namespace Snoggie\Lurkemurk\Controller\Catalog;



class CategoriesById extends \Magento\Framework\App\Action\Action 
{

    protected $pageFactory;
    protected $jsonFactory;
    protected $prodFactory;
    protected $request;
    protected $catFactory;
    
    public function __construct
    	(
    		\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $pageFactory,
    		\Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Catalog\Model\CategoryFactory $catFactory,
            \Magento\Catalog\Model\ProductFactory $prodFactory,
            \Magento\Framework\App\RequestInterface $request
        )
    {
       
        $this->request = $request; 
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->prodFactory = $prodFactory;
        $this->catFactory = $catFactory;
        
        parent::__construct($context);
    }
 
    public function execute()
    {

        $catFactory = $this->catFactory->create();
                    
        $rqp = $this->request->getParams();
                    
        $cats = [];
        foreach ($rqp as $key=>$val) {
            switch($key){
            case 'ids':
                $val.= ',';
                $ids = explode(',', $val);
                foreach($ids as $id){
                    $cat = $catFactory->load($id);
                    if(null != $cat->getId()){
                        $cats[]= array(
                            'id'=>$cat->getId(),
                            'name'=>$cat->getName(),
                            'description'=>$cat->getDescription(),
                            'parent_id'=>$cat->getParentId(),
                            'url_key'=>$cat->getUrlKey(),
                            'image'=>$cat->getImage(),
                            'path'=>$cat->getPath()
                        );
                    }
                }
            }
        }
        
        $data = array(
            'cmd'=>$this->request->getActionName(),
            'categories'=>$cats
        );
        
        $json = $this->jsonFactory->create();
        $json->setData($data);
        
        return $json;
    }
}
