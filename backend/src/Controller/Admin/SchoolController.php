<?php
namespace App\Controller\Admin;
 
use App\Entity\School;
use App\Form\SchoolType;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
 
#[Route('/admin/schools', name: 'app_admin_schools')]
class SchoolController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(SchoolRepository $repo): Response
    {
        return $this->render('admin/schools/index.html.twig', [
            'schools' => $repo->findAll(),
        ]);
    }
 
    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($school);
            $em->flush();
            return $this->redirectToRoute('app_admin_schools');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouvelle école',
            'active'   => 'schools',
            'back_url' => $this->generateUrl('app_admin_schools'),
        ]);
    }
 
    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(School $school, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_schools');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier ' . $school->getLabel(),
            'active'   => 'schools',
            'back_url' => $this->generateUrl('app_admin_schools'),
        ]);
    }
 
    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(School $school, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $school->getId(), $request->request->get('_token'))) {
            $em->remove($school);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_schools');
    }
}