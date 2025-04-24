<?php

namespace SDN\WebpSupport\Model;

class ImageValidatorPlugin
{
    public function afterGetProtectedFileExtensions($subject, $result)
    {
        // Убедимся, что .webp не считается "защищённым"
        if (($key = array_search('webp', $result)) !== false) {
            unset($result[$key]);
        }
        return $result;
    }

    public function aroundIsValid(
        \Magento\Framework\Api\ImageContentValidator $subject,
        callable $proceed,
        \Magento\Framework\Api\Data\ImageContentInterface $imageContent
    ) {
        // Добавим поддержку MIME-типа image/webp
        $mimeType = $imageContent->getType();
        if ($mimeType === 'image/webp') {
            return true;
        }

        return $proceed($imageContent);
    }
}
