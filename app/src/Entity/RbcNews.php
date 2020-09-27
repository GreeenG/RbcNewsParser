<?php

namespace App\Entity;

use App\Repository\RbcNewsRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Exception;

/**
 * @ORM\Entity(repositoryClass=RbcNewsRepository::class)
 */
class RbcNews
{
    private const IMAGES_DIR = 'images';

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
     * @ORM\Column(type="datetime_immutable", length=1000)
     */
    private $updatedAt;

    /**
     * RbcNews constructor.
     *
     * @param string            $originalUrl
     * @param string            $title
     * @param string            $content
     * @param DateTimeImmutable $timestamp
     */
    public function __construct(
        string $originalUrl,
        string $title,
        string $content,
        DateTimeImmutable $timestamp)
    {
        $this->originalUrl = $originalUrl;
        $this->title = $title;
        $this->content = $content;
        $this->timestamp = $timestamp;
    }

    /**
     * @param string|null $url
     * @param string|null $title
     *
     * @return string|void
     */
    public function setImageOptions(?string $url, ?string $title)
    {
        $this->originalImageUrl = $url;
        $this->imageTitle = $title;
        if (null !== $url) {
            $imagePathInfo = pathinfo($url);

            $localImagePath = sprintf('%s/%s/%s.%s',
                $_SERVER['DOCUMENT_ROOT'],
                self::IMAGES_DIR,
                $imagePathInfo['filename'],
                $imagePathInfo['extension']);
            try {
                $this->saveImage($url, $localImagePath);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $this->imageUrl = sprintf('/%s/%s.%s',
                self::IMAGES_DIR,
                $imagePathInfo['filename'],
                $imagePathInfo['extension']);
        }

    }

    /**
     * @param string $url
     * @param string $path
     *
     * @return void
     */
    private function saveImage(string $url, string $path): void
    {
        if (!file_exists($path)) {
            file_put_contents($path, file_get_contents($url));
        }
    }

    /**
     * @param string            $title
     * @param string            $content
     * @param DateTimeImmutable $timestamp
     * @param string|null       $originalImageUrl
     * @param string|null       $imageTitle
     *
     * @return self
     */
    public function update(
        string $title,
        string $content,
        DateTimeImmutable $timestamp,
        ?string $originalImageUrl,
        ?string $imageTitle): self
    {
        $this->title = $title;
        $this->content = $content;
        $this->timestamp = $timestamp;
        $this->setImageOptions($originalImageUrl, $imageTitle);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getOriginalImageUrl(): ?string
    {
        return $this->originalImageUrl;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return string|null
     */
    public function getImageTitle(): ?string
    {
        return $this->imageTitle;
    }

    /**
     * @return string
     */
    public function getContent(): string
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

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
