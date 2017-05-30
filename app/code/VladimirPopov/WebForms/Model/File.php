<?php

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\DataObject\IdentityInterface;

class File extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{

    /**
     * Result cache tag
     */
    const CACHE_TAG = 'webforms_file';

    const THUMBNAIL_DIR = 'webforms/thumbs';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_file';

    /** @var  \VladimirPopov\WebForms\Model\Result */
    protected $_result;

    protected $resultFactory;

    protected $storeManager;

    protected $_helper;

    protected $_url;

    protected $_imageFactory;

    protected $_scopeConfig;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->resultFactory = $resultFactory;
        $this->storeManager = $storeManager;
        $this->_url = $url;
        $this->_imageFactory = $imageFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\File');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Retrieve URL instance
     *
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlInstance()
    {
        return $this->_url;
    }

    public function getResult()
    {
        if ($this->_result) return $this->_result;
        if ($this->getData('result_id')) {
            $this->_result = $this->resultFactory->create()->load($this->getData('result_id'));
            return $this->_result;
        }
        return false;
    }

    public function getWebform()
    {
        /** @var \VladimirPopov\WebForms\Model\Result $result */
        $result = $this->getResult();
        if ($result) return $result->getWebform();
        return false;
    }

    public function getStore()
    {
        $store = $this->storeManager->getStore($this->getResult()->getStoreId());

        return $store;
    }

    public function getFullPath()
    {
        return $this->getStore()->getBaseMediaDir() . '/' . $this->getPath();
    }

    public function getSizeText()
    {
        $size = $this->getSize();
        $sizes = [" bytes", " kb", " mb", " gb", " tb", " pb", " eb", " zb", " yb"];
        if ($size == 0) {
            return ('n/a');
        } else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[intval($i)]);
        }
    }

    public function getDownloadLink()
    {
        return $this->getUrlInstance()->getUrl('webforms/file/download', ['hash' => $this->getLinkHash()]);
    }

    public function loadByHash($hash)
    {
        if ($hash)
            return $this->getCollection()->addFilter('link_hash', $hash)->getFirstItem();
        return false;
    }

    protected function getThumbnailDir()
    {
        return $this->getStore()->getBaseMediaDir() . '/' . self::THUMBNAIL_DIR;
    }

    public function getThumbnail($width = false, $height = false)
    {
        $imageUrl = $this->getFullPath();

        $file_info = @getimagesize($imageUrl);

        if (!$file_info)
            return false;

        if (strstr($file_info["mime"], "bmp"))
            return false;

        if (file_exists($imageUrl)) {
            if (!$height) {
                $height = round($file_info[1] * ($width / $file_info[0]));
            }
            $imageResized = $this->getThumbnailDir() . '/' . $this->getId() . '_' . $width . 'x' . $height;
            if (!file_exists($imageResized) || $this->_scopeConfig->getValue('webforms/images/cache') == 0) {

                $this->setMemoryForImage();
                $imageObj = $this->_imageFactory->create($imageUrl);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepTransparency(true);
                $imageObj->resize($width, $height);
                $imageObj->save($imageResized);
                unset($imageObj);
            }
        } else {
            return false;
        }

        $url = $this->getStore()->getBaseUrl(\Magento\Framework\Url::URL_TYPE_MEDIA) . self::THUMBNAIL_DIR;
        $url .= '/' . $this->getId() . '_' . $width . 'x' . $height;
        return $url;
    }

    public function setMemoryForImage()
    {
        $filename = $this->getFullPath();
        $imageInfo = getimagesize($filename);
        $MB = 1048576;  // number of bytes in 1M
        $K64 = 65536;    // number of bytes in 64K
        $TWEAKFACTOR = 1.5;  // Or whatever works for you
        if (empty($imageInfo['bits']) || empty($imageInfo['channels'])) return false;
        $memoryNeeded = round(($imageInfo[0] * $imageInfo[1]
                * $imageInfo['bits']
                * $imageInfo['channels'] / 8
                + $K64
            ) * $TWEAKFACTOR
        );
        $defaultLimit = ini_get('memory_limit');
        $memoryLimit = $defaultLimit;
        if (preg_match('/^(\d+)(.)$/', $defaultLimit, $matches)) {
            if ($matches[2] == 'M') {
                $memoryLimit = intval($matches[1]) * 1024 * 1024; // nnnM -> nnn MB
            } else if ($matches[2] == 'K') {
                $memoryLimit = intval($matches[1]) * 1024; // nnnK -> nnn KB
            }
        }
        if (function_exists('memory_get_usage') &&
            memory_get_usage() + $memoryNeeded > $memoryLimit
        ) {
            $newLimit = $memoryLimit + ceil((memory_get_usage()
                        + $memoryNeeded
                        - $memoryLimit
                    ) / $MB
                );
            ini_set('memory_limit', $newLimit . 'M');
            return $defaultLimit;
        } else
            return false;
    }
}