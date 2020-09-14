<?php

namespace App\Entity;

use App\Repository\RbcNewsRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=RbcNewsRepository::class)
 */
class RbcNews
{
    const IMAGES_DIR = 'images';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $originalUrl;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $originalImageUrl;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $imageTitle;

    /**
     * @ORM\Column(type="string", length=100000)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable", length=1000)
     */
    private $timestamp;

    /**
     * RbcNews constructor.
     * @param                   $originalUrl
     * @param                   $title
     * @param                   $content
     * @param DateTimeImmutable $timestamp
     */
    public function __construct($originalUrl, $title, $content, DateTimeImmutable $timestamp)
    {
        $this->originalUrl = $originalUrl;
        $this->title = $title;
        $this->content = $content;
        $this->timestamp = $timestamp;
    }

    /**
     * @param string $url
     * @param string $title
     */
    public function setImageOptions($url, $title)
    {
        $this->originalImageUrl = $url;
        $this->imageTitle = $title;
        $imagePathInfo = pathinfo($url);

        $localImagePath = sprintf('%s/%s/%s.%s',
            $_SERVER['DOCUMENT_ROOT'],
            self::IMAGES_DIR,
            $imagePathInfo['filename'],
            $imagePathInfo['extension']);
        try {
            $this->saveImage($url, $localImagePath);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        $this->imageUrl = sprintf('/%s/%s.%s',
            self::IMAGES_DIR,
            $imagePathInfo['filename'],
            $imagePathInfo['extension']);
    }

    /**
     * @param string $url
     * @param string $path
     */
    private function saveImage($url, $path)
    {
        if (!file_exists($path)) {
            file_put_contents($path, file_get_contents($url));

        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getOriginalImageUrl()
    {
        return $this->originalImageUrl;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return mixed
     */
    public function getImageTitle()
    {
        return $this->imageTitle;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }


}
