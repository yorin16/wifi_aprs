<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Project;
use App\Entity\Question;
use App\Form\Question\QuestionCreateType;
use App\Form\Question\QuestionEditType;
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
    public function __construct(
        private ProjectRepository $projectRepository,
        private EntityManagerInterface $entityManager)
    {
    }

    public function index($project): Response
    {
        /* @var Collection|Question[] $questions*/
        $project = $this->projectRepository->find($project);

        $questions = $project->getQuestions();
        return $this->render('admin/editProject/question/index.html.twig', [
            'project' => $project,
            'questions' => $questions
        ]);
    }

    public function add($project, Request $request): RedirectResponse|Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionCreateType::class, ['question' =>$question,  'projectId' => $project] );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $question->setProject($this->projectRepository->find($project));

            $this->entityManager->persist($question);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_question', ['project' => $project]);
        }

        return $this->render('admin/editProject/question/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Question $question, Project $project): RedirectResponse|Response
    {
        $form = $this->createForm(QuestionEditType::class, ['question' =>$question,  'projectId' => $project]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($question);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_question', ['project' => $project->getId()]);
        }

        return $this->render('admin/editProject/question/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    public function delete(Project $project, Question $question, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete_question_' . $question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
            $this->addFlash('success', 'Question deleted');
        } else {
            $this->addFlash('error', 'Invalid CSRF token');
        }
        return $this->redirectToRoute('admin_question', ['project' => $project->getId()]);
    }

    public function confirmDelete(Project $project, Question $question): Response
    {
        return $this->render('admin/editProject/question/confirm_delete.html.twig',[
            'project' => $project,
            'question' => $question
        ]);
    }
}