<?php

namespace App\Controller\Frontend;

use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app.cart.show')]
    public function cart(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentcart();

        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $cartManager->save($cart);

            $this->addFlash('success','Le Panier a bien ét émis à jour');

            return $this->redirectToRoute('app.cart.show');
        }

        return $this->render('Frontend/Cart/index.html.twig', [
            'cart' => $cart,
            'form'=>$form,
        ]);
    }
}
