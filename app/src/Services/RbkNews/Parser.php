<?php

namespace App\Services\RbcNews;

use App\Entity\RbcNews;
use App\Repository\RbcNewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use DateTimeImmutable;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    private const RBK_URL = 'https://www.rbc.ru/';
    private const NEWS_ELEMENT_CLASS_NAME = 'news-feed__item';
    private const NEWS_LIST_CLASS_NAME = 'js-news-feed-list';
    private const NEWS_TITLE_SELECTOR = 'h1[itemprop="headline"]';
    private const NEWS_CONTENT_SELECTOR = 'div[itemprop="articleBody"] p';
    private const REVIEW_CONTENT_SELECTOR = '.review';
    private const REVIEW_TITLE_SELECTOR = 'h1';
    private const NEWS_URL_ATTR = 'href';
    private const NEWS_TIMESTAMP_ATTR = 'data-modif';
    private const REVIEWS_CONTAINER_SELECTOR = '.reviews__container';
    private const NEWS_IMG_SELECTOR = 'img[itemprop="contentUrl"]';
    private const NEWS_IMAGE_URL_ATTR = 'src';
    private const NEWS_IMAGE_TITLE_ATTR = 'alt';

    /**
     * @var RbcNewsRepository
     */
    private $newsRepository;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Parser constructor.
     *
     * @param RbcNewsRepository      $newsRepository
     * @param Loader                 $loader
     * @param EntityManagerInterface $em
     */
    public function __construct(RbcNewsRepository $newsRepository, Loader $loader, EntityManagerInterface $em)
    {
        $this->newsRepository = $newsRepository;
        $this->loader = $loader;
        $this->em = $em;
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function process()
    {
        $mainPageCrawler = new Crawler($this->loader->getHtmlByUrl(self::RBK_URL));
        $news = $mainPageCrawler->filter(sprintf('.%s .%s', self::NEWS_LIST_CLASS_NAME, self::NEWS_ELEMENT_CLASS_NAME));
        $this->em->getConnection()->beginTransaction();

        try {
            foreach ($news as $newsItem) {
                $newsOriginalUrl = $newsItem->attributes->getNamedItem(self::NEWS_URL_ATTR)->nodeValue;
                $timestamp = (int)$newsItem->attributes->getNamedItem(self::NEWS_TIMESTAMP_ATTR)->nodeValue;

                if (empty($this->newsRepository->findBy(['originalUrl' => $newsOriginalUrl]))) {
                    $fullNewsPageCrawler = new Crawler($this->loader->getHtmlByUrl($newsOriginalUrl));

                    if ($isReview = $fullNewsPageCrawler->filter(self::REVIEWS_CONTAINER_SELECTOR)->count()) {
                        $title = $fullNewsPageCrawler->filter(self::REVIEW_TITLE_SELECTOR)->first()->text();
                        $content = $fullNewsPageCrawler->filter(self::REVIEW_CONTENT_SELECTOR)->children()->eq(1)->text();
                    } elseif ($hasTitle = $fullNewsPageCrawler->filter(self::NEWS_TITLE_SELECTOR)->count()) {
                        $title = $fullNewsPageCrawler->filter(self::NEWS_TITLE_SELECTOR)->first()->text();
                        $content = $fullNewsPageCrawler->filter(self::NEWS_CONTENT_SELECTOR)->text();
                    } else {
                        continue;
                    }

                    $newsDate = new DateTimeImmutable(false);
                    $newsToStore = new RbcNews($newsOriginalUrl, $title, $content, $newsDate->setTimestamp($timestamp));

                    $imageCrawler = $fullNewsPageCrawler->filter(self::NEWS_IMG_SELECTOR);

                    if ($hasImage = $imageCrawler->count()) {
                        $newsToStore->setImageOptions($imageCrawler->first()->attr(self::NEWS_IMAGE_URL_ATTR), $imageCrawler->first()->attr(self::NEWS_IMAGE_TITLE_ATTR));
                    }

                    $this->newsRepository->save($newsToStore);
                }
            }
            $this->em->getConnection()->commit();
        } catch (Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }
}
