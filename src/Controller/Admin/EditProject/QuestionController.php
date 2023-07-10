<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Project;
use App\Entity\Question;
use App\Form\Question\QuestionCreateType;
use App\Form\Question\QuestionEditType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    public const TYPE_MULTI = 1;
    public const TYPE_OPEN = 2;
    public const TYPE_PHOTO = 3;

    public function __construct(
        private ProjectRepository      $projectRepository,
        private EntityManagerInterface $entityManager)
    {
    }

    //TODO: add correct answer option

    public function index($project): Response
    {
        /* @var Collection|Question[] $questions */
        $project = $this->projectRepository->find($project);

        $questions = $project->getQuestions();
        return $this->render('admin/editProject/question/index.html.twig', [
            'project' => $project,
            'questions' => $questions
        ]);
    }

    public function add(Project $project, Request $request): RedirectResponse|Response
    {
        $question = new Question();
        $locations = $project->getLocations();
        $form = $this->createForm(QuestionCreateType::class, ['question' => $question, 'projectId' => $project->getId(), 'locations' => $locations]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setProject($this->projectRepository->find($project->getId()));
            $question->setText($form->get('text')->getData());
            $question->setType($form->get('type')->getData());
            $question->setMulti1($form->get('multi1')->getData());
            $question->setMulti2($form->get('multi2')->getData());
            $question->setMulti3($form->get('multi3')->getData());
            $question->setMulti4($form->get('multi4')->getData());
            $question->setOpen($form->get('open')->getData());
            $question->setPoints($form->get('points')->getData());
            $question->setLocation($form->get('location')->getData());

            $this->entityManager->persist($question);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_question', ['project' => $project->getId()]);
        }

        return $this->render('admin/editProject/question/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Question $question, Project $project): RedirectResponse|Response
    {
        $locations = $project->getLocations();
        $form = $this->createForm(QuestionEditType::class, ['question' => $question, 'projectId' => $project, 'locations' => $locations]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $question->setText($formData['text']);
            $question->setType($formData['type']);
            $question->setMulti1($form->get('multi1')->getData());
            $question->setMulti2($form->get('multi2')->getData());
            $question->setMulti3($form->get('multi3')->getData());
            $question->setMulti4($form->get('multi4')->getData());
            $question->setOpen($form->get('open')->getData());
            if($formData['type'] == self::TYPE_MULTI){
                $question->setPoints($formData['points']);
            }else {
                $question->setPoints(NULL);
            }
            $question->setLocation($formData['location'] ?? $question->getLocation());
            $this->entityManager->persist($question);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_question', ['project' => $project->getId()]);
        }

        return $this->render('admin/editProject/question/edit.html.twig', [
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
        return $this->render('admin/editProject/question/confirm_delete.html.twig', [
            'project' => $project,
            'question' => $question
        ]);
    }
}