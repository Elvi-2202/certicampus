<?php

namespace App\Controller\Client;

use App\Entity\Certified;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ImportController extends AbstractController
{
    #[Route('/import', name: 'app_client_import', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('client/import/index.html.twig');
    }

    #[Route('/import', name: 'app_client_import_post', methods: ['POST'])]
    public function import(Request $request, EntityManagerInterface $em): Response
    {
        $file = $request->files->get('excel_file');

        if (!$file) {
            $this->addFlash('error', 'Veuillez sélectionner un fichier Excel.');
            return $this->redirectToRoute('app_client_import');
        }

        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            $this->addFlash('error', 'Format non supporté. Utilisez .xlsx, .xls ou .csv');
            return $this->redirectToRoute('app_client_import');
        }

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Ignore la première ligne (en-têtes)
            array_shift($rows);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $lineNumber = $index + 2;

                // Lecture des colonnes : Nom, Email, Niveau, Téléphone
                $studentName = trim($row[0] ?? '');
                $email       = trim($row[1] ?? '');
                $grade       = trim($row[2] ?? '');
                $phone       = trim($row[3] ?? '');

                if (empty($studentName) || empty($email)) {
                    $errors[] = "Ligne $lineNumber : Nom de l'étudiant et Adresse mail sont obligatoires.";
                    continue;
                }

                $certified = new Certified();
                
                // Mappage dynamique selon vos propriétés d'entité
                if (method_exists($certified, 'setStudentName')) {
                    $certified->setStudentName($studentName);
                } else {
                    $certified->setLabel($studentName);
                }

                if (method_exists($certified, 'setEmail')) {
                    $certified->setEmail($email);
                }

                if (method_exists($certified, 'setGrade')) {
                    $certified->setGrade($grade);
                }

                if (method_exists($certified, 'setPhone')) {
                    $certified->setPhone($phone);
                }

                if (method_exists($certified, 'setGraduationDate')) {
                    $certified->setGraduationDate(new \DateTimeImmutable());
                }

                $em->persist($certified);
                $imported++;
            }

            $em->flush();

            if ($imported > 0) {
                $this->addFlash('success', "$imported étudiant(s) importé(s) avec succès.");
            }

            foreach ($errors as $error) {
                $this->addFlash('error', $error);
            }

        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la lecture du fichier : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_client_import');
    }
}