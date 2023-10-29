<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Import the EntityManagerInterface

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/authorlist', name: 'app_author')]
    public function authorlist(AuthorRepository $repo): Response
    {
        $authors = $repo->listAuthorByEmail();
        return $this->render('author/authors.html.twig', [
            'authors'=>$authors,
        ]);
        
    }

    #[Route('/authoradd', name: 'add_author')]
    public function authoradd(Request $request,AuthorRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $author = new Author() ;
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirect('/authoradd');
        } 
        return $this->render('author/authoradd.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/authordelete/{id}', name: 'delete_author')]
    public function authordelete(Request $request,$id,AuthorRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $author = $repo->find($id);
        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirect('/authorlist');
    }
    #[Route('/authorupdate/{id}', name: 'update_author')]
    public function authorupdate(Request $request,$id,AuthorRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirect('/authorlist');

        }

        return $this->render('author/authorupdate.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/listAuthorByEmail', name: 'Email_author')]
    public function Emailauthor(Request $request,$id,AuthorRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirect('/authorlist');

        }

        return $this->render('author/authorupdate.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/authorNum', name: 'authorNum')]
    public function authorNum(AuthorRepository $repo,Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $max = $request->request->get('max');
            $min = $request->request->get('min');

            $authors=$repo->authorNum($min,$max);
        }
        $authors = $repo->listAuthorByEmail();
        return $this->render('author/authors.html.twig', [
            'authors'=>$authors,
        ]);
    }
}
