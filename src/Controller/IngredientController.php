<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    #[Route('/', name: 'ingredient_index')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'ingredient_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form?->isSubmitted() && $form?->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();
            $this->addFlash('success', 'Votre ingrédient a bien été créé');

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form?->createView(),
        ]);
    }
    #[Route('/{id}/edit', name: 'ingredient_edit')]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
    
        if ($form?->isSubmitted() && $form?->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre ingrédient a bien été modifié');
    
            return $this->redirectToRoute('ingredient_index');
        }
    
        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form?->createView(),
        ]);
    }
    
    #[Route('/{id}/delete', name: 'ingredient_delete')]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
        $entityManager->remove($ingredient);
        $entityManager->flush();
        $this->addFlash('success', 'Supprimé avec succès');
    }

    return $this->redirectToRoute('ingredient_index');
}


}