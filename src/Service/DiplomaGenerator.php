<?php

namespace App\Service;

use App\Entity\Certified;
use App\Entity\Diploma;
use Doctrine\ORM\EntityManagerInterface;

class DiplomaGenerator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DiplomaPdfGenerator $pdfGenerator
    ) {
    }

    /**
     * Génère des diplômes pour les étudiants sélectionnés
     * 
     * @param array $studentIds
     * @return array
     */
    public function generateForStudents(array $studentIds): array
    {
        $generated = [];
        
        foreach ($studentIds as $studentId) {
            try {
                $certified = $this->entityManager->getRepository(Certified::class)->find($studentId);
                
                if (!$certified) {
                    continue;
                }

                // Créer une nouvelle entité Diploma
                $diploma = new Diploma();
                $diploma->setGeneratedAt(new \DateTimeImmutable());
                $diploma->setIsValid(true);
                
                // Générer le PDF
                $pdfContent = $this->pdfGenerator->generate($diploma);
                
                // Sauvegarder le fichier
                $filename = 'diploma_' . $studentId . '_' . time() . '.pdf';
                $filepath = 'uploads/diplomas/' . $filename;
                
                // Créer le dossier s'il n'existe pas
                if (!is_dir('uploads/diplomas')) {
                    mkdir('uploads/diplomas', 0755, true);
                }
                
                file_put_contents($filepath, $pdfContent);
                
                $diploma->setFileUrl($filepath);
                
                $this->entityManager->persist($diploma);
                $this->entityManager->flush();
                
                $generated[] = [
                    'student_id' => $studentId,
                    'diploma_id' => $diploma->getId(),
                    'success' => true
                ];
            } catch (\Exception $e) {
                $generated[] = [
                    'student_id' => $studentId,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $generated;
    }

    /**
     * Génère un diplôme unique pour un étudiant
     * 
     * @param Certified $certified
     * @return Diploma
     */
    public function generateForStudent(Certified $certified): Diploma
    {
        $diploma = new Diploma();
        $diploma->setGeneratedAt(new \DateTimeImmutable());
        $diploma->setIsValid(true);
        
        // Générer le PDF
        $pdfContent = $this->pdfGenerator->generate($diploma);
        
        // Sauvegarder le fichier
        $filename = 'diploma_' . $certified->getId() . '_' . time() . '.pdf';
        $filepath = 'uploads/diplomas/' . $filename;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir('uploads/diplomas')) {
            mkdir('uploads/diplomas', 0755, true);
        }
        
        file_put_contents($filepath, $pdfContent);
        
        $diploma->setFileUrl($filepath);
        
        $this->entityManager->persist($diploma);
        $this->entityManager->flush();
        
        return $diploma;
    }
}