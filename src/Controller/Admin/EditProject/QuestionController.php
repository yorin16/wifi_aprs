<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Question;
use App\Form\Question\QuestionCreateType;
use App\Repository\ProjectRepository;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function index($project): Response
    {
        /* @var Collection|Question[] $questions*/
        $questions = $this->projectRepository->findQuestionsByProjectId($project);
        return $this->render('admin/editProject/question/index.html.twig', [
            'project' => $project,
            'questions' => $questions
        ]);
    }

    public function add($project, Request $request): RedirectResponse|Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionCreateType::class, $question);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($question);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_question', ['project' => $project]);
        }

        return $this->render('admin/editProject/question/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}