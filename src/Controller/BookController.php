<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/bookadd',name:'add_book')]
    public function addbook(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($book);
            $entityManager->flush();
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() +1);
            $entityManager->persist($author);
            $entityManager->flush();
        }
        return $this->render('book/Bookadd.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/booklist', name: 'list_book')]
    public function booklist(BookRepository $repo): Response
    {
        $books = $repo->findAll();
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'msg'=>"sans recherche"
        ]);
    }
    #[Route('/bookupdate/{ref}', name: 'update_book')]
    public function bookadd(Request $request,BookRepository $repo,$ref,EntityManagerInterface $entityManager): Response
    {
        $book = $repo->find($ref);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirect('/booklist');
        }
        return $this->render('book/Bookadd.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/bookdelete/{ref}', name: 'delete_book')]
    public function bookdelete(Request $request,BookRepository $repo,$ref,EntityManagerInterface $entityManager): Response
    {
        $book = $repo->find($ref);
        $entityManager->remove($book);
        $entityManager->flush();
        $author = $book->getAuthor();
        $author->setNbBooks($author->getNbBooks() -1);
        $entityManager->persist($author);
        $entityManager->flush();
        if($author->getNbBooks()<=0){
            $entityManager->remove($author);
            $entityManager->flush();
        }

        return $this->redirect('/booklist');
    }
    #[Route('/book/{ref}', name: 'detail_book')]
    public function bookdetails(Request $request,BookRepository $repo,$ref,EntityManagerInterface $entityManager): Response
    {
        $book = $repo->find($ref);
        return $this->render('book/bookdetails.html.twig', [
            'book'=>$book,
        ]);
    }
    #[Route('/searchBookByRef', name: 'rech_book')]
    public function searchBookByRef(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            
            $ref = $request->request->get('ref');
            $books=$repo->searchBookByRef($ref);
        }
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'msg'=>"book recherche"
        ]);
    }
    #[Route('/searchByAuthor', name: 'searchbyauth_book')]
    public function searchBookByAuthor(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $author = $request->request->get('author');

            $books=$repo->booksListByAuthors($author);
        }
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'msg'=>"book recherche"
        ]);
    }
    #[Route('/searchBookBy10books', name: 'book10')]
    public function searchBookByAuthor2023(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $books=$repo->booksList2023();
        }
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'msg'=>"book recherche"
        ]);
    }
    #[Route('/SciencetoRomance', name: 'SciencetoRomance')]
    public function SciencetoRomance(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $repo->SciencetoRomance();
        }
        return $this->redirect('/booklist');
    }
    #[Route('/numberOfRomance', name: 'numberOfRomance')]
    public function numberOfRomance(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $numberOfRomanceBooks=$repo->numberOfRomance();
            $books = $repo->findAll();
        }
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'number0fRomanceBooks'=>$numberOfRomanceBooks,'msg'=>"book recherche"
        ]);
    }
    #[Route('/livresPublies2018', name: 'livresPublies')]
    public function livresPublies(Request $request,BookRepository $repo,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $books=$repo->livresPublies();
        }
        return $this->render('book/booklist.html.twig', [
            'books'=>$books,'msg'=>"livresPublies"
        ]);
    }
}

