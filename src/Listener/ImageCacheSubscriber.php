<?php 

namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber 
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }
    public function preRemove(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof Property) {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        
    }

    public function preUpdate(PreUpdateEventArgs $args) {

        $entity = $args->getEntity();
        if (!$entity instanceof Property) {
            return;
        }
        if ($entity->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
    }


    /**
     * Get the value of cacheManager
     *
     * @return  CacheManager
     */ 
    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    /**
     * Set the value of cacheManager
     *
     * @param  CacheManager  $cacheManager
     *
     * @return  self
     */ 
    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }

    /**
     * Get the value of uploaderHelper
     *
     * @return  UploaderHelper
     */ 
    public function getUploaderHelper()
    {
        return $this->uploaderHelper;
    }

    /**
     * Set the value of uploaderHelper
     *
     * @param  UploaderHelper  $uploaderHelper
     *
     * @return  self
     */ 
    public function setUploaderHelper(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;

        return $this;
    }
}


