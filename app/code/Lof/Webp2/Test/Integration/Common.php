<?php

declare(strict_types=1);

namespace Lof\Webp2\Test\Integration;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\TestCase\AbstractController;
use RuntimeException;
use Lof\Webp2\Image\ConvertWrapper;
use Lof\Webp2\Test\Utils\ImageProvider;
use Lof\Webp2\Test\Utils\ConvertWrapperStub;

class Common extends AbstractController
{
    protected function setUp(): void
    {
        parent::setUp();
        $convertWrapperStub = $this->_objectManager->get(ConvertWrapperStub::class);
        $this->_objectManager->addSharedInstance($convertWrapperStub, ConvertWrapper::class);
    }

    protected function fixtureImageFiles()
    {
        /** @var DirectoryList $directoryList */
        $directoryList = $this->_objectManager->get(DirectoryList::class);
        $root = $directoryList->getRoot();
        $imagesInThemePath = $root . '/pub/static/frontend/Magento/luma/en_US/Lof_Webp2/images/test';

        if (!is_dir($imagesInThemePath)) {
            mkdir($imagesInThemePath, 0777, true);
        }

        if (!is_dir($imagesInThemePath)) {
            throw new RuntimeException('Failed to create folder: ' . $imagesInThemePath);
        }

        $currentFiles = scandir($imagesInThemePath);
        foreach ($currentFiles as $currentFile) {
            if (!in_array($currentFile, ['.', '..'])) {
                unlink($imagesInThemePath . '/' . $currentFile);
            }
        }

        /** @var ImageProvider $imageProvider */
        $imageProvider = $this->_objectManager->get(ImageProvider::class);
        $images = $imageProvider->getImages();

        /** @var ComponentRegistrar $componentRegistrar */
        $componentRegistrar = $this->_objectManager->get(ComponentRegistrar::class);
        $modulePath = $componentRegistrar->getPath('module', 'Lof_Webp2');
        $moduleWebPath = $modulePath . '/view/frontend/web';

        foreach ($images as $image) {
            $sourceImage = $moduleWebPath . '/' . $image;
            $destinationImage = $imagesInThemePath . '/' . basename($image);
            copy($sourceImage, $destinationImage);
        }
    }

    /**
     * @return ImageProvider
     */
    protected function getImageProvider(): ImageProvider
    {
        return $this->_objectManager->get(ImageProvider::class);
    }

    /**
     * @param string $body
     */
    protected function assertImageTagsExist(string $body, $images)
    {
        foreach ($images as $image) {
            $webPImage = preg_replace('/\.(png|jpg)$/', '.webp', $image);
            $this->assertTrue((bool)strpos($body, $webPImage));
        }
    }
}
