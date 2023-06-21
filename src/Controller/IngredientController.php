<?php

namespace App\Controller;

use App\Entity\Ingredient;

use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class IngredientController extends AbstractController
{
    /**
     *  Fonction qui affiche tout les ingredients
     * 
     * @param IngredientRepository $repository
     * @return PaginatorInterface $paginator
     * @return Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient', methods: ['GET'])]
    public function index(
        IngredientRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        /* dd($ingredients); */
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Fonction qui affiche un ingredient
     * 
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* dd($form->getData()); */
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'ingrédient a bien été ajouté'
            );

            return $this->redirectToRoute('ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(IngredientRepository $repository, 
        int $id,
        Request $request,
        EntityManagerInterface $manager) : Response
    {

        $ingredient = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* dd($form->getData()); */
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'ingrédient a bien été modifié'
            );

            return $this->redirectToRoute('ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
    public function delete(
        IngredientRepository $repository,
        int $id,
        EntityManagerInterface $manager
    ) : Response
    {
        $ingredient = $repository->findOneBy(["id" => $id]);

        $manager->remove($ingredient);
        $manager->flush();
        
        $this->addFlash(
            'success',
            'L\'ingrédient a bien été supprimé'
        );

        return $this->redirectToRoute('ingredient');
    }
}
