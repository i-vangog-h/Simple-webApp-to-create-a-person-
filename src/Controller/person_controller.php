<?php

namespace App\Controller;

use App\Form\PersonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use Twig\Environment;


class person_controller extends AbstractController
{
    #[Route('/')]
    public function form (Environment $twig, Request $request, EntityManagerInterface $entityManager):Response{

        $person = new Person();
        $form = $this->createForm(PersonFormType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $name = $person->getFirstName();
            $entityManager->persist($person);
            $entityManager->flush();
            return new Response($twig->render('person/is_created.html.twig', ['name' => $name], ));
        }

        return new Response($twig->render('person/form.html.twig', ['person_form' => $form->createView()]));
    }

    #[Route('/show', name: 'persons_show')]
    public function show(Environment $twig, ManagerRegistry $doctrine): Response
    {
        $persons = $doctrine->getRepository(Person::class)->findAll();

        if (!$persons) {
            throw $this->createNotFoundException(
                'No persons have been created..'
            );
        }

        return new Response($twig->render('person/show.html.twig', ['persons' => $persons]));

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

   

}