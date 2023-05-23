<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/recipe')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'recipe_index')]
    public function index(RecipeRepository  $recipeRepository,IngredientRepository $ingredientRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'recipe_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form?->isSubmitted() && $form?->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();
            $this->addFlash('success', 'Votre recette a bien été créé');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form?->createView(),
        ]);
    }
    #[Route('/{id}/edit', name: 'recipe_edit')]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
    
        if ($form?->isSubmitted() && $form?->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre recette a bien été modifié');
    
            return $this->redirectToRoute('recipe_index');
        }
    
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form?->createView(),
        ]);
    }
    
    #[Route('/{id}/delete', name: 'recipe_delete')]
    public function delete(Request $request, recipe $recipe, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
        $entityManager->remove($recipe);
        $entityManager->flush();
        $this->addFlash('success', 'Supprimé avec succès');
    }

    return $this->redirectToRoute('recipe_index');
}


}