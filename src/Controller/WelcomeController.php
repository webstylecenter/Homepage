<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Entity\FeedItemStatus;
use App\Entity\FeedSetting;
use App\Entity\Note;
use App\Entity\UserFeedItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @Route("/welcome/")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $notes = $entityManager->getRepository(Note::class)->findBy(['user' => $this->getUser()]);
        $checklistItems = $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>false, 'user'=>$this->getUser()], ['updatedAt'=> 'DESC']);

        return $this->render('welcome/index.html.twig', [
            'bodyClass' => 'welcome',
            'notes'=> $notes,
            'todos' => $checklistItems
        ]);
    }

    /**
     * @Route("/create/")
     */
    public function createTest()
    {
        echo 'test';

        $feed = new Feed();
        $feedSetting = new FeedSetting();
        $feedItem = new FeedItem();
        $feedItemStatus = new FeedItemStatus();


        $feed
            ->setFeedUrl('http://www.artiestennieuws.nl/feed/')
            ->setName('Artiesten Nieuws');

        $feedSetting
            ->setUser($this->getUser())
            ->setFeed($feed)
            ->setColor('#c3c3c3')
            ->setFeedIcon('video')
            ->setAutoPin(false);

        $feedItem
            ->setFeed($feed)
            ->setTitle('Eerste artikel')
            ->setDescription('Omschrijving')
            ->setUrl('http://google.com')
            ->setGuid('PvdTest');

        $feedItemStatus
            ->setFeedItem($feedItem)
            ->setUser($this->getUser())
            ->setViewed(false)
            ->setPinned(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($feed);
        $entityManager->persist($feedSetting);
        $entityManager->persist($feedItem);
        $entityManager->persist($feedItemStatus);
        $entityManager->flush();

        exit;
    }

    /**
     * @Route("/test/")
     */
    public function test()
    {



        echo 'test';
        $entityManager = $this->getDoctrine()->getManager();

        $feedItems = $entityManager->getRepository(FeedItem::class)->findByUser($this->getUser());
        dump($feedItems);
        exit;

//        return $this->render('test/index.html.twig', [
//            'bodyClass' => 'welcome',
//            'feedItems' => $feedItems,
//            'nextPageNumber' => 99999,
//            'addToChecklist' => ''
//        ]);
    }
}