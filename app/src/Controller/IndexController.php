<?php

namespace App\Controller;

use App\Repository\RbcNewsRepository;
use  App\Services\RbcNews\Loader;
use  App\Services\RbcNews\Parser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var RbcNewsRepository
     */
    private $newsRepository;

    /**
     * IndexController constructor.
     *
     * @param Loader                 $loader
     * @param RbcNewsRepository      $newsRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(Loader $loader, RbcNewsRepository $newsRepository, EntityManagerInterface $em)
    {
        $this->parser = new Parser($newsRepository, $loader, $em);
        $this->newsRepository = $newsRepository;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $news = $this->newsRepository->findBy([], ['timestamp' => 'DESC'], 15);

        if (empty($news)) {
            $this->updateNews();
            $news = $this->newsRepository->findBy([], ['timestamp' => 'DESC'], 15);
        }

        return $this->render('index.html.twig', ['news' => $news]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function renew()
    {
        $this->updateNews();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/news/show/{id}", methods={"GET","HEAD"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $id)
    {
        $news = $this->newsRepository->find($id);
        return $this->render('show.html.twig', ['news' => $news]);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    private function updateNews()
    {
        try {
            $this->parser->process();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
