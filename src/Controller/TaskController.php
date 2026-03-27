<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface; //save 
use Symfony\Component\HttpFoundation\Request; //read form
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{    
    //function create task  
    #[Route('/task/create', name: 'app_task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager) : Response
    {
            $task = new Task;
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted()&& $form->isValid()){
               
                $task->setIsDone(false);

                //save  
                $entityManager->persist($task);
                $entityManager->flush();   

                return $this->redirectToRoute('task_list');
            }
            
            return $this->render('task/createTask.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    //function get task list
    #[Route('/taskList', name: 'task_list')]
    public function getTask (TaskRepository $taskRepository) : Response 
    {
        $tasks = $taskRepository->findAll();
        
        return $this->render('task/taskList.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    //function toggle task isDone 
    #[Route('/task/toggle/{id}', name: 'app_task_toggle')]
    public function toggle(Task $task, EntityManagerInterface $entityManager): Response
    {
        //reverse current value isDone with "!"
        $task->setIsDone(!$task->isDone());

        $entityManager->flush();  

        return $this->redirectToRoute('task_list');
    }

    //function delete task
    #[Route('/task/delete/{id}', name: 'app_task_delete')]
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($task);

        $entityManager->flush(); 

        return $this->redirectToRoute('task_list');
    }


     #[Route('/task/edit/{id}', name: 'app_task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $entityManager) : Response
    {
        
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted()&& $form->isValid()){

                $entityManager->flush();   

                return $this->redirectToRoute('task_list');
            }
            
            return $this->render('task/createTask.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
        ]);
    }
}