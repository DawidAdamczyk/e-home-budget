<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Expenditure;

class ExpenditureController extends AbstractController
{
    public function index()
    {
        return $this->render('expenditure\index.html.twig');
    }

    public function list($month, $value)
    {
        $repository = $this->getDoctrine()->getRepository(Expenditure::class);

        $expenditiures = $repository->findBy(['month' => $month]);

        return $this->render('expenditure\list.html.twig', ['expenditiures' => $expenditiures]);
    }

    public function new(Request $request)
    {
        $expenditure = new Expenditure();

        $form = $this->createFormBuilder( $expenditure)
        ->add('name', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('price', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('category', TextType::class, ['attr' => ['class' => 'form-control']])
        ->add('month', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('save', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expenditure);
            $entityManager->flush();

            $this->addFlash('success', 'Expenditure created');

            return $this->redirectToRoute('index');

        }

        return $this->render('expenditure/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    public function edit($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Expenditure::class);

        $expenditure = $repository->find($id);

        $form = $this->createFormBuilder( $expenditure)
        ->add('name', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('price', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('category', TextType::class, ['attr' => ['class' => 'form-control']])
        ->add('month', TextType::class, ['attr' => ['class' => 'form-control']]) 
        ->add('save', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expenditure);
            $entityManager->flush();

            $this->addFlash('success', 'Expenditure updated');

            return $this->redirectToRoute('index');

        }

        return $this->render('expenditure/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $expenditure = $entityManager->getRepository(Expenditure::class)->find($id);

        $entityManager->remove($expenditure);

        $entityManager->flush();

        $this->addFlash('success', 'Expenditure deleted');

        return $this->redirectToRoute('index');
    }

    public function searchByCategory($value)
    {
        $repository = $this->getDoctrine()->getRepository(Expenditure::class);

        $expendituries = $repository->findByCategory($value);
    }
}