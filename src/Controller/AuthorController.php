<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AdminAuthorType;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="app_author")
     */
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


     /**
     * @Route("/admin/auteurs/nouveau", name="app_author_create")
     */
    public function create(Request $request , AuthorRepository $repository): Response
    {
     
        //création du formulaire je n'ai pas besoin d'ajouter un objet Author en paramétre car je ne fait pas de préremplissage
        $form= $this->createForm(AdminAuthorType::class);
        //remplir le formulaire avec les données envoyées par l'utilisateur
        $form->handleRequest($request);


        //tester si le formulaire a était envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()){

            //récupérer les données du formulaire dans un objet author
            $author= $form->getData();

            //enregistrer l'auteur dans la bd grace au repository
            $repository->add($author, true);

            //redirection de l'utilisateur vers la liste des auteurs
            return $this->redirectToRoute('app_author_list');
        }
        return $this->render('author/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }




     /**
     * @Route("/admin/auteurs", name="app_author_list")
     */
    public function list(AuthorRepository $repository): Response
    {

        //recuperer les auteurs depuis la bd
        $authors= $repository->findAll(); //retourne la liste compléte des auteurs

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ] );

    }


     /**
     * @Route("/admin/auteurs/{id}", name="app_author_update")
     */
    public function update(int $id, Request $request , AuthorRepository $repository): Response
    {
        //recuperer l'auteur à partir de l'id
        $author = $repository->find($id);

        //création du formulaire et son préremplissage
        $form= $this->createForm(AdminAuthorType::class, $author);

        //remplir le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);

        //tester si la formulaire est envoyé et est valide
        if($form->isSubmitted() && $form->isValid()){
            
             //récupérer les données du formulaire dans un objet author
             $author = $form->getData();

            //enregistrer l'auteur
            $repository->add($author , true);

            //redirection vers la page de liste
            return $this->redirectToRoute('app_author_list');

        }


        return $this->render('author/update.html.twig', [ 
            'form' => $form->createView(),
            'author' => $author] ); // cet argument sert à afficher le nom de l'auteur dans le twig
    }




     /**
     * @Route("/admin/auteurs/{id}/supprimer", name="app_author_remove")
     */
    public function remove(int $id, Request $request , AuthorRepository $repository): Response
    {
        //recuperer l'auteur depuis son id
        $author = $repository->find($id);

        //suprimer l'auteur de la base de données
        $repository->remove($author, true);

        //redirection vers la liste des auteurs
        return $this->redirectToRoute('app_author_list');
    }
}
