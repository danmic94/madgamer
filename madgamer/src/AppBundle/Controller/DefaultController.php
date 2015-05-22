<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public  function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
                           ->getRepository('AppBundle:Game');
        $games=$repository->selectAll();

        return $this->render('default/show.html.twig', array(
            'games'=>$games
        ));
    }
    // title genre release_date copiesSold Developer image path
    public function  createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $game = new Game();

        $form = $this->createForm(new GameType(),$game);

        $form->handleRequest($request);
        if($form->isValid()) {

            $em->persist($game);
            $em->flush();

            $this->get('session')->getFlashBag()->add('msg','You have succesfuly added a new game!');

        }

      return $this->render('default/new.html.twig', array(
          'form'=>$form->createView()
      ));

    }

    public  function  editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('AppBundle:Game')->find($id);

        $form = $this->createForm(new GameType(), $game, array(
            'action'=>$this->generateUrl('update', array('id'=>$game->getId())),
            'method'=>'PUT'
        ));
        return $this->render('default/edit.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    public function updateAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('AppBundle:Game')->find($id);

        $form = $this->createForm(new GameType(), $game ,array(
            'action'=>$this->generateUrl('edit', array('id'=>$game->getId())),
            'method'=>'PUT',
        ));
        $form->add('submit', 'submit',array('label'=>'Update Game'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('msg','Your game has been updated!');

            return $this->redirect($this->generateUrl('show'));
        }

        return $this->render('default/edit.html.twig', array(
            'form'=>$form->createView()));
    }

    public function deleteAction(Request $request, $id)
    {   $em= $this->getDoctrine()->getManager();
        $game=$em->getRepository('AppBundle:Game')->find($id);
        $em->remove($game);
        $em->flush();
        return $this->redirect($this->generateUrl('show'));
    }

    public function selectAction($id){
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('AppBundle:Game')->find($id);


        if ( isset($game)){
            return $this->render('default/info.html.twig', array(
                'game'=>$game
            ));
        }
    }
}
